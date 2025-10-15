<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'                       => $this->id,
            // 'jobPosting'                    => $this->jobPosting,
            // 'application'                   => $this->application,
            // 'candicate'                     => $this->candicate,
            'company_id'               => $this->company_id,
            'department_id'            => $this->department_id,
            'designation_id'           => $this->designation_id,
            'offer_letter_template_id' => $this->offer_letter_template_id,
            'salary_currency_id'       => $this->salary_currency_id,
            'approver_id'              => $this->approver_id,
            'company'                  => $this->company,
            'department'               => $this->department,
            'designation'              => $this->designation,
            'candidate'                => $this->candidate,
            'template'                 => $this->template,
            'employment_type'          => $this->employment_type,

            // Salary Details
            'salary_currency'          => $this->salaryCurrency,
            'basic_salary'             => (float) $this->basic_salary,

            'offer_date'               => $this->offer_date,
            'joined_date'              => $this->joined_date,

            // Offer Letter Content
            'offer_letter_ref'         => $this->offer_letter_ref,
            'offer_letter_subject'     => $this->offer_letter_subject,
            'offer_letter_content'     => $this->offer_letter_content,
            'content_file_path'        => $this->offer_letter_file_path,
            'content_file_path_url'    => $this->content_file_path_url,

            // Approval & Auditing
            'approve_required'         => (bool) $this->approve_required,
            'approver'                 => $this->approver,
            'approver_signature'       => $this->approver_signature,
            'approver_signature_url'   => $this->approver_signature_url,
            'created_by'               => $this->createdBy,
            'updated_by'               => $this->updatedBy,
            'created_at'               => $this->created_at,
            'updated_at'               => $this->updated_at,
            'attachments'              => $this->attachments,
            'informed_departments'     => $this->informedDepartments,
            'cc_users'                 => $this->ccUsers,
            'bcc_users'                => $this->bccUsers,
            'status'                   => $this->status,
            // 'edit_action'                   => $this->status == JobOfferStatusTypes::DRAFT->value ? true : false,
            // 'send_action'                   => $this->status == JobOfferStatusTypes::DONE->value ? true : false,
            // 'mark_as_offer_accepted_action' => $this->status == JobOfferStatusTypes::SENT->value || $this->status == JobOfferStatusTypes::OFFER_DECLINED->value ? true : false,
            // 'mark_as_offer_declined_action' => $this->status == JobOfferStatusTypes::SENT->value || $this->status == JobOfferStatusTypes::OFFER_ACCEPTED->value ? true : false,
        ];
    }
}
