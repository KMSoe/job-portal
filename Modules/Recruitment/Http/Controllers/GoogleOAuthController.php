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
        $user = auth()->user();
        $statePayload = null;

        if ($user) {
            $payload = [
                'user_id' => $user->id,
                'ts' => now()->timestamp,
            ];

            $statePayload = encrypt($payload);
        }

        $authUrl = $this->googleCalendarService->getAuthUrlWithState($statePayload);

        return response()->json(['auth_url' => $authUrl]);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');
        $state = $request->get('state');
        $userId = null;

        if ($state) {
            try {
                $decoded = decrypt($state);
                if (is_array($decoded) && isset($decoded['user_id'])) {
                    $userId = $decoded['user_id'];
                }
            } catch (\Exception $e) {
                $userId = null;
            }
        }
        
        try {
            $token = $this->googleCalendarService->authenticateWithCode($code);
            $user = null;
            if ($userId) {
                $user = User::find($userId);
            }

            if (!$user) {
                $user = auth()->user() ? User::find(auth()->user()->id) : null;
            }

            $user->update([
                'google_access_token' => json_encode($token),
                'google_refresh_token' => $token['refresh_token'] ?? null,
                'google_token_expires_at' => now()->addSeconds($token['expires_in']),
            ]);

            $frontend = 'http://150.95.24.71:3000/';
            $query = http_build_query([
                'google_calendar_connected' => 1,
                'user_id' => $user->id,
            ]);

            return redirect()->away($frontend . ($query ? ('?' . $query) : ''));
        } catch (\Exception $e) {
            $frontend = 'http://150.95.24.71:3000/';
            $query = http_build_query([
                'google_calendar_connected' => 0,
                'error' => $e->getMessage(),
            ]);
            return redirect()->away($frontend . ($query ? ('?' . $query) : ''));
        }
    }
}