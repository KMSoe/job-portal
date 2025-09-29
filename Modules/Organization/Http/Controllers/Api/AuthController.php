<?php
namespace Modules\Organization\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'data'    => [],
                'message' => "Incorrect Email and/or Password.",
            ], 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = $user->createToken('AUTH_TOKEN')->accessToken;

            $remember_me = intval($request->remember_me) == 1 ? 1 : 0;
            Auth::login($user, $remember_me);

            return response()->json([
                'status'  => false,
                'data'    => [
                    'accessToken' => $token,
                    'user'        => new UserResource($user),
                    'user_type'   => 'Admin',
                ],
                'message' => "Success",
            ], 200);

        } else {
            return response()->json([
                'status'  => false,
                'data'    => [],
                'message' => "Incorrect Email and/or Password.",
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => "Logged out successfully."
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        $token = $request->user()->token();
        $token->revoke();

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => 'Password Changed successfully',
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Fail',
                'errors'  => $validator->getMessageBag(),
            ], 422);
        }

        $user = User::where('email', $request->get('email'))->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => "User doesn\'t exist.",
            ], 404);
        }

        $token = Str::random(10);

        try {
            DB::table('password_resets')->updateOrInsert(
                [
                    'email' => $user->email,

                ],
                [
                    'token'      => Hash::make($token),
                    'created_at' => Carbon::now(),
                ]
            );

            $mailData = [
                "email" => $user->email,
                "token" => $token,
                "name"  => $user->name,
            ];

            // Send Mail
            Mail::to($user->email)->send(new ResetPasswordMail($mailData));

            return response()->json([
                'status'  => true,
                'message' => 'Password reset Email Sent successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => "Something Went Wrong!",
            ], 500);
        }
    }

    public function checkResetPasswordToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Fail',
                'errors'  => $validator->getMessageBag(),
            ], 422);
        }

        $passwordReset = DB::table('password_resets')->where('email', $request->get('email'))
            ->where('created_at', '>', Carbon::now()->subHours(1))
            ->first();

        if ($passwordReset) {
            if (Hash::check($request->get('token'), $passwordReset->token)) {
                return response()->json([
                    'status'  => true,
                    'message' => "Token Valid",
                ], 200);
            }
        } else {
            return response()->json([
                'status'  => false,
                'message' => "Token Invalid",
            ], 422);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $request->validated();

        $email = $request->get('email');
        $token = $request->get('token');

        $passwordReset = DB::table('password_resets')->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHours(1))
            ->first();

        if (! $passwordReset) {
            return response()->json([
                'status'  => false,
                'message' => 'Token is Invalid or expired',
            ], 422);
        } else {
            if (! Hash::check($token, $passwordReset->token)) {
                return response()->json([
                    'status'  => true,
                    'message' => "Token is Invalid or expired",
                ], 422);
            }
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User doesn\'t exists.',
            ], 404);
        }

        $user->password          = Hash::make($request->get('password'));
        $user->is_activated      = 1;
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'status'  => true,
            'message' => 'Password changed successfully. Login again!',
        ], 200);
    }
}
