<?php

namespace Modules\Recruitment\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Modules\Recruitment\Entities\Applicant;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function applicantRegister(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Applicant::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = Applicant::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $otp = rand(100000, 999999);
            $otpExpires = now()->addMinutes(10);

            $user->update([
                'email_otp' => $otp,
                'email_otp_expires_at' => $otpExpires,
            ]);

            Mail::send('recruitment::emails.verificationmail', ['otp' => $otp, 'name' => $user->name], function($message) use($user) {
                $message->to($user->email);
                $message->subject('Your verification code');
            });

            return response()->json([
                'success' => true,
                'message' => 'Registration successful. An OTP has been sent to your email for verification.',
                'user' => $user->only('id', 'name', 'email', 'created_at', 'updated_at'),
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resendOtp($id)
    {
        try {
            $user = Applicant::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email is already verified.'], 400);
            }

            $otp = rand(100000, 999999);
            $otpExpires = now()->addMinutes(10);

            $user->update([
                'email_otp' => $otp,
                'email_otp_expires_at' => $otpExpires,
            ]);

            Mail::send('recruitment::emails.verificationmail', ['otp' => $otp, 'name' => $user->name], function($message) use($user) {
                $message->to($user->email);
                $message->subject('Your verification code');
            });

            return response()->json([
                'status' => true,
                'message' => 'A new OTP has been sent to your email for verification.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyEmail($id, Request $request)
    {
        try {
            $user = Applicant::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }
            
            $providedOtp = $request->input('otp');

            if ($providedOtp) {
                if ($user->email_otp && $user->email_otp_expires_at && now()->lessThanOrEqualTo($user->email_otp_expires_at) && (string) $user->email_otp === (string) $providedOtp) {
                    if (! $user->hasVerifiedEmail()) {
                        $user->markEmailAsVerified();
                    }

                    $user->update(['email_otp' => null, 'email_otp_expires_at' => null]);

                    Mail::send('recruitment::emails.welcomemail', ['name' => $user->name], function($message) use($user) {
                        $message->to($user->email);
                        $message->subject('Welcome to SHIFANOVA');
                    });

                    return response()->json([
                        'success' => true,
                        'message' => 'Email verified successfully via OTP.'
                    ], 200);
                }

                return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 403);
            } else {
                return response()->json(['success' => false, 'message' => 'OTP is required for verification.'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'exists:'.Applicant::class.',email'],
            ]);

            $user = Applicant::where('email', $request->email)->first();

            $reset_link = config('app.frontend_url').'/reset-password?id='.$user->id.'&hash='.sha1($user->email);

            Mail::send('recruitment::emails.forgotpasswordmail', ['reset_link' => $reset_link , 'name' => $user->name], function($message) use($user) {
                $message->to($user->email);
                $message->subject('Password Reset Request');
            });

            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email.',
                'user' => $user->only('id', 'name', 'email', 'created_at', 'updated_at')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'id' => ['required', 'integer', 'exists:'.Applicant::class.',id'],
                'hash' => ['required', 'string'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = Applicant::find($request->id);

            if (!hash_equals((string) $request->hash, sha1($user->email))) {
                return response()->json(['message' => 'Invalid reset link.'], 403);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Password reset successfully.'], 200);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
