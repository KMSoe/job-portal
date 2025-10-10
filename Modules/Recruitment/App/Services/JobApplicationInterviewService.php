<?php

namespace Modules\Recruitment\App\Services;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;
use Modules\Recruitment\App\Services\GoogleCalendarService;
use Modules\Recruitment\Entities\JobApplicationInterview;
use Modules\Recruitment\Entities\JobApplicationInterviewInterviewer;

class JobApplicationInterviewService
{
    protected $googleCalendarService;

    public function __construct(GoogleCalendarService $googleCalendarService)
    {
        $this->googleCalendarService = $googleCalendarService;
    }

    public function findByParams($request)
    {
        $per_page = $request->input('per_page', 20);
        $query = JobApplicationInterview::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('application_id')) {
            $query->where('application_id', $request->input('application_id'));
        }
        if ($request->has('scheduled_from')) {
            $query->whereDate('scheduled_at', '>=', Carbon::parse($request->input('scheduled_from')));
        }
        if ($request->has('scheduled_to')) {
            $query->whereDate('scheduled_at', '<=', Carbon::parse($request->input('scheduled_to')));
        }

        return $query->with(['application.applicant', 'application.jobPosting', 'interviewers.user'])->paginate($per_page);
    }

    public function createInterview($data)
    {
        try {
            $interview = JobApplicationInterview::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'location' => $data['location'] ?? null,
                'status' => $data['status'] ?? 'scheduled',
                'application_id' => $data['application_id'],
                'interview_type' => $data['interview_type'],
                'scheduled_at' => $data['scheduled_at'],
                'duration_minutes' => $data['duration_minutes'] ?? 60,
                'notes' => $data['notes'] ?? null,
            ]);

            if(isset($data['interviewers']) && is_array($data['interviewers'])) {
                foreach ($data['interviewers'] as $interviewerData) {
                    $interview->interviewers()->create([
                        'user_id' => $interviewerData['user_id'],
                        'attendance_status' => $interviewerData['status'] ?? 'confirmed',
                    ]);
                }
            }

            // Load relationships needed for Google Meet event creation
            $interview->load('interviewers.user', 'application.applicant', 'application.jobPosting');

            if ($data['interview_type'] === 'online') {
                $this->createGoogleMeetEvent($interview, $data);
            }

            return $interview;
        } catch (Exception $e) {
            throw new Exception("Failed to create interview: " . $e->getMessage());
        }
    }

    protected function createGoogleMeetEvent($interview, $data)
    {
        try {
            $interviewers = $interview->interviewers;
            if ($interviewers->isEmpty()) {
                throw new Exception("No interviewers assigned to the interview");
            }
            
            $interviewer = $interviewers->first();
            $user = $interviewer->user;

            if (!$user) {
                throw new Exception("Interviewer user not found");
            }

            if (!$user->google_access_token) {
                throw new Exception("Interviewer has not connected their Google Calendar");
            }

            $this->googleCalendarService->setAccessToken($user->google_access_token);

            // Load the application with applicant relationship
            $interview->load('application.applicant', 'application.jobPosting');
            $applicant = $interview->application->applicant;
            
            if (!$applicant) {
                throw new Exception("Applicant not found for this application");
            }

            $attendees = [
                [
                    'email' => $applicant->email,
                    'name' => $applicant->name,
                ],
            ];

            // Add additional attendees if provided
            if (isset($data['additional_attendees'])) {
                foreach ($data['additional_attendees'] as $attendee) {
                    $attendees[] = [
                        'email' => $attendee['email'],
                        'name' => $attendee['name'] ?? null,
                    ];
                }
            }

            $startTime = Carbon::parse($data['scheduled_at']);
            $endTime = $startTime->copy()->addMinutes($data['duration_minutes'] ?? 60);

            $jobPosting = $interview->application->jobPosting;
            $jobTitle = $jobPosting ? $jobPosting->title : 'Job Interview';

            $eventData = [
                'summary' => "Job Interview - " . $jobTitle,
                'description' => "Interview for the position of " . $jobTitle . 
                                "\n\nCandidate: " . $applicant->name .
                                "\n\nNotes: " . ($data['notes'] ?? 'N/A'),
                'start_time' => $startTime->toRfc3339String(),
                'end_time' => $endTime->toRfc3339String(),
                'timezone' => $data['timezone'] ?? config('app.timezone'),
                'attendees' => $attendees,
            ];

            // Create Google Calendar event with Meet
            $result = $this->googleCalendarService->createEventWithMeet($eventData);

            // Update interview with Google event details
            $interview->update([
                'google_event_id' => $result['event_id'],
                'google_meet_link' => $result['meet_link'],
            ]);

            // Update interviewer's token if refreshed
            if ($this->googleCalendarService->isAccessTokenExpired()) {
                $user->update([
                    'google_access_token' => $this->googleCalendarService->getAccessToken(),
                ]);
            }

            return $result;
        } catch (Exception $e) {
            // Log error but don't fail the interview creation
            Log::error("Failed to create Google Meet event: " . $e->getMessage());
            throw $e;
        }
    }

    public function findById($interviewId)
    {
        return JobApplicationInterview::with(['application.applicant', 'application.jobPosting', 'interviewers.user'])->findOrFail($interviewId);
    }

    public function updateInterview($interviewId, $data)
    {
        try {
            $interview = JobApplicationInterview::findOrFail($interviewId);
            
            $interview->update([
                'title' => $data['title'] ?? $interview->title,
                'description' => $data['description'] ?? $interview->description,
                'location' => $data['location'] ?? $interview->location,
                'status' => $data['status'] ?? $interview->status,
                'interview_type' => $data['interview_type'] ?? $interview->interview_type,
                'scheduled_at' => $data['scheduled_at'] ?? $interview->scheduled_at,
                'duration_minutes' => $data['duration_minutes'] ?? $interview->duration_minutes,
                'notes' => $data['notes'] ?? $interview->notes,
            ]);

            if(isset($data['interviewers']) && is_array($data['interviewers'])) 
            {
                $interview->interviewers()->delete();
                foreach ($data['interviewers'] as $interviewerData) {
                    $interview->interviewers()->create([
                        'user_id' => $interviewerData['user_id'],
                        'attendance_status' => $interviewerData['status'] ?? 'confirmed',
                    ]);
                }
            }

            if ($data['interview_type'] === 'online') {
                if($interview->google_event_id)
                {
                    $this->updateGoogleMeetEvent($interview, $data);
                } else {
                    $this->createGoogleMeetEvent($interview, $data);
                }
            }

            return $interview;
        } catch (Exception $e) {
            throw new Exception("Failed to update interview: " . $e->getMessage());
        }
    }

    protected function updateGoogleMeetEvent($interview, $data)
    {
        try {
            $interviewer = $interview->interviewers()->first();
            if (!$interviewer) {
                throw new Exception("No interviewer assigned to this interview");
            }
            
            $user = $interviewer->user;
            $this->googleCalendarService->setAccessToken($user->google_access_token);

            $eventData = [];
            
            if (isset($data['scheduled_at']) || isset($data['duration_minutes'])) {
                $startTime = Carbon::parse($data['scheduled_at'] ?? $interview->scheduled_at);
                $endTime = $startTime->copy()->addMinutes($data['duration_minutes'] ?? $interview->duration_minutes);
                
                $eventData['start_time'] = $startTime->toRfc3339String();
                $eventData['end_time'] = $endTime->toRfc3339String();
            }

            if (isset($data['notes'])) {
                $eventData['description'] = $data['notes'];
            }

            $this->googleCalendarService->updateEvent($interview->google_event_id, $eventData);
        } catch (Exception $e) {
            Log::error("Failed to update Google Meet event: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($interviewId)
    {
        try {
            $interview = JobApplicationInterview::findOrFail($interviewId);

            if ($interview->google_event_id) {
                $interviewer = $interview->interviewers()->first();
                if ($interviewer) {
                    $user = $interviewer->user;
                    $this->googleCalendarService->setAccessToken($user->google_access_token);
                    $this->googleCalendarService->deleteEvent($interview->google_event_id);
                }
            }

            $interview->delete();
            return true;
        } catch (Exception $e) {
            throw new Exception("Failed to cancel interview: " . $e->getMessage());
        }
    }

    public function updateFeedback($data, $id)
    {
        try {
            $user = auth()->user();
            $interviewer = JobApplicationInterviewInterviewer::where('interview_id', $data['interview_id'])
                            ->where('user_id', $user->id)
                            ->first();

            if (!$interviewer) {
                throw new Exception("Interviewer not found");
            }

            $interviewer->update([
                'score' => $data['score'] ?? $interviewer->score,
                'feedback' => $data['feedback'] ?? $interviewer->feedback,
                'commented_at' => now(),
                'comment_status' => $data['comment_status'] ?? 'done',
            ]);
            return $interviewer;
        } catch (Exception $e) {
            throw new Exception("Failed to update interview feedback: " . $e->getMessage());
        }
    }
}