<?php

namespace Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Organization\App\Enums\EmploymentTypes;
use Modules\Organization\App\Enums\GenderTypes;
use Modules\Organization\App\Enums\MaritalStatuses;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                              => 'required|string|max:255',
            'preferred_name'                    => 'required|string|max:255',
            'email'                             => ['required', 'email', Rule::unique('employees', 'email')->whereNull('deleted_at')],
            'work_mail'                         => ['required', 'email', Rule::unique('employees', 'work_mail')->whereNull('deleted_at')],
            'company_id'                        => 'required|exists:companies,id',
            'department_id'                     => 'required|exists:departments,id',
            'designation_id'                    => 'required|exists:designations,id',
            'employment_type'                   => ['required', Rule::enum(EmploymentTypes::class)],
            'gender'                            => ['nullable', Rule::enum(GenderTypes::class)],
            'marital_status'                    => ['nullable', Rule::enum(MaritalStatuses::class)],
            'nationality'                       => 'nullable|string|max:100',
            'race'                              => 'nullable|string|max:100',
            'religion'                          => 'nullable|string|max:100',
            'primary_phone_dial_code'           => 'nullable|string|max:5',
            'primary_phone_no'                  => 'nullable|string|max:20|regex:/^[0-9]{6,20}$/',
            'secondary_phone_dial_code'         => 'nullable|string|max:5',
            'secondary_phone_no'                => 'nullable|string|max:20|regex:/^[0-9]{6,20}$/',
            'id_nrc'                            => 'nullable|string|max:50',
            'passport_number'                   => 'nullable|string|max:50',
            'address'                           => 'nullable|string|max:255',
            'bank_name'                         => 'nullable|string|max:100',
            'bank_account_no'                   => 'nullable|string|max:50',
            'salary_currency_id'                => 'required|exists:currencies,id',
            'basic_salary'                      => 'required|numeric|min:0',
            'employee_code'                     => ['required', 'string', Rule::unique('employees', 'employee_code')->whereNull('deleted_at')],
            'offered_date'                      => 'required|date',
            'joined_date'                       => 'required|date|after_or_equal:offered_date',
            'last_date'                         => 'nullable|date|after_or_equal:joined_date',
            'password'                          => 'required',
            'onboarding_checklist_template_id'  => 'nullable|exists:checklist_templates,id',
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
