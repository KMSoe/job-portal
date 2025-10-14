<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Recruitment\App\Enums\JobPostingSalaryTypes;
use Modules\Recruitment\App\Enums\JobPostingStatusTypes;
use Modules\Recruitment\App\Enums\JobTypes;
use Modules\Recruitment\App\Enums\WorkArrangementTypes;

class StoreJobPostingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Organizational Foreign Keys
            'company_id'                             => 'required|exists:companies,id',
            'department_id'                          => 'required|exists:departments,id',
            'designation_id'                         => 'required|exists:designations,id',
            'template_id'                            => 'required|exists:job_posting_templates,id',

            // Job Details
            'title'                                  => 'required|string|max:255',
            'experience_level_id'                    => 'required|exists:experience_levels,id',
            'job_function_id'                        => 'required|exists:job_functions,id',
            'min_education_level_id'                 => 'required|exists:education_levels,id',
            'summary'                                => 'required|string|max:65535', // longText
            'open_to'                                => 'nullable|string|max:255',
            'roles_and_responsibilities'             => 'nullable|string|max:65535',
            'requirements'                           => 'nullable|string|max:65535',
            'what_we_can_offer_include'              => 'sometimes|boolean',

            // Conditional "What We Can Offer" Fields (Required if include is true)
            'what_we_can_offer_benefits'             => 'required_if:what_we_can_offer_include,true|nullable|string',
            'what_we_can_offer_highlights'           => 'required_if:what_we_can_offer_include,true|nullable|string',
            'what_we_can_offer_career_opportunities' => 'required_if:what_we_can_offer_include,true|nullable|string',

            // Type and Location
            'job_type'                               => ['required', Rule::in(JobTypes::values())],
            'work_arrangement'                       => ['required', Rule::in(WorkArrangementTypes::values())],

            // Conditional Location (Required if hybrid or on-site)
            'location'                               => 'required_if:work_arrangement,hybrid,on-site|nullable|string|max:255',

            'skill_ids'                              => 'present|array',
            'skill_ids.*'                            => 'required|exists:skills,id',

            // Compensation
            'salary_type'                            => ['required', Rule::in(JobPostingSalaryTypes::values())],
            'salary_currency_id'                     => 'required_unless:salary_type,negotiable|nullable|exists:currencies,id',
            'salary_notes'                           => 'nullable|string|max:255',

            // Conditional Salary Fields
            // Required if type is up_to, around, or fixed
            'salary_amount'                          => 'required_if:salary_type,up_to,around,fixed|nullable|numeric|min:0',
            // Required if type is range
            'min_salary'                             => 'required_if:salary_type,range|nullable|numeric|min:0',
            'max_salary'                             => 'required_if:salary_type,range|nullable|numeric|gt:min_salary',

            // Status and Dates
            'vacancies'                              => 'required|integer|min:1',
            'status'                                 => ['required', Rule::in(JobPostingStatusTypes::values())],
            'published_at'                           => 'required|date',
            'deadline_date'                          => 'nullable|date|after_or_equal:published_at',
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
