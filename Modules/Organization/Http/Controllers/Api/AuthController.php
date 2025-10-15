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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'data'    => [],
                'message' => "Incorrect Email and/or Password.",
            ], 401);
        }

        $token = $user->createToken('AUTH_TOKEN')->plainTextToken;

        // $remember_me = intval($request->remember_me) == 1 ? 1 : 0;
        // Auth::guard('applicant')->login($applicant, $remember_me);

        return response()->json([
            'status'  => true,
            'data'    => [
                'accessToken' => $token,
                'user'        => new UserResource($user),
                'user_type'   => 'Admin',
            ],
            'message' => "Success",
        ], 200);

        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     $token = $user->createToken('AUTH_TOKEN')->plainTextToken;

        //     $remember_me = intval($request->remember_me) == 1 ? 1 : 0;
        //     Auth::login($user, $remember_me);

        //     return response()->json([
        //         'status'  => true,
        //         'data'    => [
        //             'accessToken' => $token,
        //             'user'        => new UserResource($user),
        //             'user_type'   => 'Admin',
        //         ],
        //         'message' => "Success",
        //     ], 200);

        // } else {
        //     return response()->json([
        //         'status'  => false,
        //         'data'    => [],
        //         'message' => "Incorrect Email and/or Password.",
        //     ], 401);
        // }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => "Logged out successfully.",
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
        try {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'exists:'.User::class.',email'],
            ]);

            $user = User::where('email', $request->email)->first();

            $reset_link = config('app.frontend_url').'/admin/reset-password?id='.$user->id.'&hash='.sha1($user->email);

            Mail::send('organization::emails.forgotpasswordmail', ['reset_link' => $reset_link , 'name' => $user->name], function($message) use($user) {
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
                'id' => ['required', 'integer', 'exists:'.User::class.',id'],
                'hash' => ['required', 'string'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $user = User::find($request->id);

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
