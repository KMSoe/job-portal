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

            $verification_link = URL('/api/v1/applicant/verify-email/'.$user->id.'/'.sha1($user->email));

            Mail::send('recruitment::emails.verificationmail', ['verification_link' => $verification_link , 'name' => $user->name], function($message) use($user) {
                $message->to($user->email);
                $message->subject('Email Verification For New User');
            });

            return response()->json(['message'    => 'Registration successful. Please check your email for verification link.'], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function verifyEmail($id, $hash, Request $request)
    {
        try {
            $user = Applicant::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            if (!hash_equals((string) $request->hash, sha1($user->email))) {
                return response()->json(['message' => 'Invalid verification link.'], 403);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email already verified.'], 200);
            }

            $user->markEmailAsVerified();

            Mail::send('recruitment::emails.welcomemail', ['name' => $user->name], function($message) use($user) {
                $message->to($user->email);
                $message->subject('Welcome to SHIFANOVA');
            });

            return response()->json(['message' => 'Email verified successfully.'], 200);
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

            $reset_link = URL('/api/v1/applicant/reset-password?id='.$user->id.'&hash='.sha1($user->email));

            Mail::send('recruitment::emails.forgotpasswordmail', ['reset_link' => $reset_link , 'name' => $user->name], function($message) use($user) {
                $message->to($user->email);
                $message->subject('Password Reset Request');
            });

            return response()->json(['message' => 'Password reset link sent to your email.'], 200);
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
