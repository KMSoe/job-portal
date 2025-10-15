<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;
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
            'id'                       => $this->id,
            'jobPosting'               => $this->jobPosting,
            'applicant'                => new ApplicantResource($this->applicant),
            'expected_salary_currency' => $this->applicant?->salaryCurrency,
            'expected_salary'          => $this->expected_salary ?? 0,
            'applicant_skills'         => $this->applicant?->skills,
            'resume'                   => $this->resume,
            'extracted_data'           => $this->extractedData?->extract_data,
            'supportiveDocuments'      => $this->supportiveDocuments,
            'reviewers'                => $this->reviewers,
            'interviews'               => $this->interviews,
            'jobOffer'                 => new JobOfferResource($this->jobOffer),
            'job_offer_actions'        => $this->jobOffer ? [
                'edit_action'                   => $this->jobOffer?->status == JobOfferStatusTypes::DRAFT->value ? true : false,
                'send_action'                   => $this->jobOffer?->status == JobOfferStatusTypes::DONE->value ? true : false,
                'mark_as_offer_accepted_action' => $this->jobOffer?->status == JobOfferStatusTypes::SENT->value || $this->jobOffer?->status == JobOfferStatusTypes::OFFER_DECLINED->value ? true : false,
                'mark_as_offer_declined_action' => $this->jobOffer?->status == JobOfferStatusTypes::SENT->value || $this->jobOffer?->status == JobOfferStatusTypes::OFFER_ACCEPTED->value ? true : false,
            ] :
            [
                'edit_action'                   => false,
                'send_action'                   => false,
                'mark_as_offer_accepted_action' => false,
                'mark_as_offer_declined_action' => false,
            ],
            'applied_at'               => $this->applied_at,
            'application_status'       => $this->status,
        ], $actions);
    }
}
