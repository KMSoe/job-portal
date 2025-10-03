<?php
namespace Modules\Recruitment\Transformers;

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
            'application' => $this->application,
            'interview_type' => $this->interview_type,
            'scheduled_at' => $this->scheduled_at,
            'duration_minutes' => $this->duration_minutes,
            'location' => $this->location,
            'status' => $this->status,
            'notes' => $this->notes,
            'interviewers' => $this->interviewers,
            'google_event_id' => $this->google_event_id,
            'google_meet_link' => $this->google_meet_link,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
