<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationInterviewInterviewerResource extends JsonResource
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
            'interview_id' => $this->interview,
            'user_id' => $this->user,
            'attendance_status' => $this->attendance_status,
            'score' => $this->score,
            'feedback' => $this->feedback,
            'commented_at' => $this->commented_at,
            'comment_status' => $this->comment_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
