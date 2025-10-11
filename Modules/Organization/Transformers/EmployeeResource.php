<?php

namespace Modules\Organization\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Recruitment\Transformers\OnboardingChecklistItemResource;

class EmployeeResource extends JsonResource
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
            'id'                               => $this->id,
            'user'                             => $this->user,
            'name'                             => $this->name,
            'preferred_name'                   => $this->perferred_name,
            'email'                   => $this->email,
            'work_mail'                   => $this->work_mail,
            'company'                   => $this->company,
            'department'                   => $this->department,
            'designation'                   => $this->designation,
            'employment_type'                   => $this->employment_type,
            'gender'                   => $this->gender,
            'marital_status'                   => $this->marital_status,
            'nationality'                   => $this->nationality,
            'race'                   => $this->race,
            'religion'                   => $this->religion,
            'primary_phone_dial_code'                   => $this->primary_phone_dial_code,
            'primary_phone_no'                   => $this->primary_phone_no,
            'secondary_phone_dial_code'                   => $this->secondary_phone_dial_code,
            'secondary_phone_no'                   => $this->secondary_phone_no,
            'id_nrc'                   => $this->id_nrc,
            'passport'                   => $this->passport,
            'address'                   => $this->address,
            'bank_name'                   => $this->bank_name,
            'bank_account_no'                   => $this->bank_account_no,
            'salary_currency'                   => $this->salaryCurrency,
            'basic_salary'                   => $this->basic_salary,
            'employee_code'                   => $this->employee_code,
            'offered_date'                   => $this->offered_date,
            'joined_date'                   => $this->joined_date,
            'last_date'                   => $this->last_date,
            'onboarding_checklist_template' => $this->onboardingChecklistTemplate,
            'onboarding_checklist_items'       => $this->onboardingChecklistItems ? OnboardingChecklistItemResource::collection($this->onboardingChecklistItems) : null,
            'created_by'  => $this->createdBy,
            'updated_by'  => $this->updatedBy,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
