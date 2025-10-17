<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Organization\App\Enums\EmploymentTypes;
use Modules\Recruitment\App\Enums\JobOfferStatusTypes;

class JobOfferFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Core Job Offer Details
            'company_id'               => 'required|exists:companies,id',
            'department_id'            => 'required|exists:departments,id',
            'designation_id'           => 'required|exists:designations,id',
            'offer_letter_template_id' => 'required|exists:offer_letter_templates,id',

            // Salary and Employment
            'salary_currency_id'       => 'nullable|exists:currencies,id',
            'basic_salary'             => 'nullable|numeric|min:0',
            'employment_type'          => ['nullable', Rule::in(EmploymentTypes::values())],

            'approve_required'         => 'boolean',
            'approver_id'              => 'exists_or_null:users,id',

            // Dates
            'offer_date'               => 'required|date',
            'joined_date'              => 'nullable|date|after_or_equal:offer_date',

            'offer_letter_subject'     => 'required|string',
            'offer_letter_ref'         => 'nullable|string',

            // Departments to Inform (Pivot Data)
            'inform_departments'       => 'nullable|array',
            'inform_departments.*'     => 'required|exists:departments,id',

            // CC Users (Pivot Data)
            'cc_users'                 => 'nullable|array',
            'cc_users.*'               => 'required|exists:users,id',

            // CC Users (Pivot Data)
            'bcc_users'                => 'nullable|array',
            'bcc_users.*'              => 'required|exists:users,id',

            // Attachments (Handles file paths saved temporarily or from another step)
            'attachments'              => 'nullable|array',
            'attachments.*'            => ['file'],
            'status'                   => ['required', Rule::in([JobOfferStatusTypes::DRAFT->value, JobOfferStatusTypes::DONE->value])],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
