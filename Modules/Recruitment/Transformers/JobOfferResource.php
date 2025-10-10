<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;

class JobOfferResource extends JsonResource
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
            // Core Identifiers
            'id'                            => $this->id,
            'status'                        => $this->status,
            'employment_type'               => $this->employment_type,

            // Dates
            'offer_date'                    => $this->offer_date,
            'joined_date'                   => $this->joined_date,

            // Salary Details
            'salary_currency'               => $this->salaryCurrency,
            'basic_salary'                  => (float) $this->basic_salary,

            // Offer Letter Content
            'offer_letter_subject'          => $this->offer_letter_subject,
            'offer_letter_content'          => $this->offer_letter_content,
            'content_file_path'             => $this->offer_letter_file_path,
            'content_file_path_url'         => $this->content_file_path_url,

            // Approval & Auditing
            'approve_required'              => (bool) $this->approve_required,
            'approver_id'                   => $this->approver_id,
            'approver_signature'            => $this->approver_signature,
            'created_by'                    => $this->createdBy,
            'updated_by'                    => $this->updatedBy,

            // Timestamps
            'created_at'                    => $this->created_at,
            'updated_at'                    => $this->updated_at,

            // --- Eager Loaded Relationships ---

            // Show main relationships when loaded
            'company'                       => $this->company,
            'designation'                   => $this->designation,
            'candidate'                     => $this->candidate,
            'approver'                      => $this->approver,

            // Attachments (HasMany)
            'attachments'                   => $this->attachments,

            // Departments to Inform (BelongsToMany)
            'informed_departments'          => $this->informedDepartments,

            // CC Users (BelongsToMany)
            'cc_users'                      => $this->ccUsers,
            'edit_action'                   => $this->status == JobOfferStatusTypes::DRAFT->value ? true : false,
            'send_action'                   => $this->status == JobOfferStatusTypes::DONE->value ? true : false,
            'mark_as_offer_accepted_action' => $this->status == JobOfferStatusTypes::SENT->value || $this->status == JobOfferStatusTypes::OFFER_DECLINED->value ? true : false,
            'mark_as_offer_declined_action' => $this->status == JobOfferStatusTypes::SENT->value || $this->status == JobOfferStatusTypes::OFFER_ACCEPTED->value ? true : false,
        ];
    }
}
