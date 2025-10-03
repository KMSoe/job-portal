<?php

namespace Modules\Recruitment\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Modules\Recruitment\App\Services\GoogleCalendarService;

class GoogleOAuthController extends Controller
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function redirect()
    {
        $authUrl = $this->googleCalendarService->getAuthUrl();
        return response()->json(['auth_url' => $authUrl]);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');
        
        try {
            $token = $this->googleCalendarService->authenticateWithCode($code);
            
            $user = User::find(auth()->user()->id);
            $user->update([
                'google_access_token' => json_encode($token),
                'google_refresh_token' => $token['refresh_token'] ?? null,
                'google_token_expires_at' => now()->addSeconds($token['expires_in']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Google Calendar connected successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect Google Calendar: ' . $e->getMessage(),
            ], 500);
        }
    }
}