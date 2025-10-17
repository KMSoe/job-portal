<?php
namespace Modules\Recruitment\Transformers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationInterviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'description' => $this->description,
            'application_id' => $this->application_id,
            // 'application' => $this->application,
            'interview_type' => $this->interview_type,
            'scheduled_at' => $this->scheduled_at ? Carbon::parse($this->scheduled_at)
                                    ->setTimezone($this->timezone->name ?? $this->timezone_id ?? config('app.timezone'))
                                    ->format('Y-m-d H:i:s') : null,
            'scheduled_date' => $this->scheduled_at->toDateString(),
            'scheduled_time' => $this->scheduled_at->format('H:i:s'),
            'duration_minutes' => $this->duration_minutes,
            'location' => $this->location,
            'status' => $this->status,
            'notes' => $this->notes,
            'timezone_id' => $this->timezone_id,
            'can_comment' => $this->can_comment ?? false,
            'interviewers' => $this->interviewers,
            'google_event_id' => $this->google_event_id,
            'google_meet_link' => $this->google_meet_link,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
