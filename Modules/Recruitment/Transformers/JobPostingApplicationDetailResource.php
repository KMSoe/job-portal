<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Recruitment\App\Helpers\RecruitmentHelper;

class JobPostingApplicationDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $actions = RecruitmentHelper::getJobApplicationActions($this, $this->status);

        return array_merge([
            'id'                  => $this->id,
            'applicant'           => new ApplicantResource($this->applicant),
            'expected_salary'     => $this->expected_salary,
            'applicant_skills'    => $this->applicant?->skills,
            'resume'              => $this->resume,
            'extracted_data'      => $this->extractedData?->extract_data,
            'supportiveDocuments' => $this->supportiveDocuments,
            'reviewers'           => $this->reviewers,
            'interviews'          => $this->interviews,
            'applied_at'          => $this->applied_at,
            'application_status'  => $this->status,
        ], $actions);
    }
}
