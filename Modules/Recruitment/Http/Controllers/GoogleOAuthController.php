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

    public function redirect(Request $request)
    {
        $user = auth()->user();
        $statePayload = [];

        if ($user) {
            $statePayload['user_id'] = $user->id;
        }

        $redirectPath = null;
        if ($request->filled('redirect')) {
            $redirectPath = $request->input('redirect'); // e.g. /admin/job-posting-list/1
        } elseif ($request->filled('job_posting_id')) {
            $id = intval($request->input('job_posting_id'));
            $redirectPath = "/admin/job-posting-list/{$id}";
        } else {
            $redirectPath = '/';
        }

        if ($redirectPath) {
            $statePayload['redirect'] = $redirectPath;
        }

        $statePayload['ts'] = now()->timestamp;

        $state = encrypt($statePayload);
        $authUrl = $this->googleCalendarService->getAuthUrlWithState($state);

        return response()->json(['auth_url' => $authUrl]);
    }

    public function callback(Request $request)
    {
        $code = $request->get('code');
        $state = $request->get('state');

        $frontendBase = rtrim(config('app.frontend_url', 'http://150.95.24.71:3000'), '/');

        $redirectPath = '/';

        if ($state) {
            try {
                $decoded = decrypt($state);
                if (is_array($decoded) && isset($decoded['redirect'])) {
                    $candidate = $decoded['redirect'];

                    if (is_string($candidate) && str_starts_with($candidate, '/')) {
                        $allowedPrefixes = [
                            '/admin',
                        ];
                        $allowed = false;
                        foreach ($allowedPrefixes as $prefix) {
                            if (str_starts_with($candidate, $prefix)) {
                                $allowed = true;
                                break;
                            }
                        }
                        if ($allowed) {
                            $redirectPath = $candidate;
                        }
                    }
                }

                $userIdFromState = $decoded['user_id'] ?? null;
            } catch (\Exception $e) {
                //
            }
        }

        try {
            $token = $this->googleCalendarService->authenticateWithCode($code);

            $user = User::find($userIdFromState);
            $user->update([
                'google_access_token' => json_encode($token),
                'google_refresh_token' => $token['refresh_token'] ?? null,
                'google_token_expires_at' => now()->addSeconds($token['expires_in']),
            ]);

            $query = http_build_query([
                'google_calendar_connected' => 1,
                'user_id' => $user?->id ?? $userIdFromState ?? null,
            ]);

            // return redirect()->away($frontendBase . $redirectPath . ($query ? ('?' . $query) : ''));
            return redirect()->away($frontendBase . $redirectPath);

        } catch (\Exception $e) {
            $query = http_build_query([
                'google_calendar_connected' => 0,
                'error' => $e->getMessage(),
            ]);
            return redirect()->away($frontendBase . $redirectPath . ($query ? ('?' . $query) : ''));
        }
    }
}