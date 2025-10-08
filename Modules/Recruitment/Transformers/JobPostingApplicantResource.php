<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class JobPostingApplicantResource extends JsonResource
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
            'id'                  => $this->id,
            'applicant'           => new ApplicantResource($this->applicant),
            'expected_salary'     => $this->expected_salary,
            'skills'              => $this->applicant?->skills,
            'resume'              => $this->resume,
            'supportiveDocuments' => $this->supportiveDocuments,
            'applied_at'          => $this->applied_at,
            'application_status'  => $this->status,
        ];
    }
}
