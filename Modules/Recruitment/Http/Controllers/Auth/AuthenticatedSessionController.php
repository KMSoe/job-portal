<?php
namespace Modules\Recruitment\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Http\Requests\ApplicantLoginRequest;
use Modules\Recruitment\Transformers\ApplicantResource;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(ApplicantLoginRequest $request)
    {
        $applicant = Applicant::where('email', $request->email)->first();

        // 1. Check if applicant exists AND verify password manually (most secure API way)
        if (! $applicant || ! Hash::check($request->password, $applicant->password)) {
            return response()->json([
                'status'  => false,
                'data'    => [],
                'message' => "Incorrect Email and/or Password.",
            ], 401);
        }

        $token = $applicant->createToken('AUTH_TOKEN')->plainTextToken;

        // $remember_me = intval($request->remember_me) == 1 ? 1 : 0;
        // Auth::guard('applicant')->login($applicant, $remember_me);

        return response()->json([
            'status'  => true,
            'data'    => [
                'accessToken' => $token,
                'user'        => new ApplicantResource($applicant),
                'user_type'   => 'Applicant',
            ],
            'message' => "Success",
        ], 200);

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('applicant')->logout();

        $token = Auth::guard('applicant')->user()->token();
        $token->revoke();

        return response()->json([
            'status'  => true,
            'data'    => [],
            'message' => "Logged out successfully.",
        ], 200);
    }
}
