<?php
namespace Modules\Recruitment\Transformers\Applicant;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
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
            'id'                 => $this->id,
            'job_posting'        => $this->jobPosting,
            'expected_salary'    => $this->expected_salary,
            'applied_at'         => $this->applied_at,
            'published_at'       => $this->jobPosting?->published_at,
            'job_posting_status' => $this->jobPosting?->status,
            'application_status' => $this->status,

        ];
    }
}
