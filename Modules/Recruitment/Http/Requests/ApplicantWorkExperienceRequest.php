<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantWorkExperienceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'job_title'           => 'required|string|max:255',
            'job_function_id'     => 'required|exists:job_functions,id',
            'experience_level_id' => 'required|exists:experience_levels,id',
            'company_name'        => 'required|string|max:255',
            'country_id'          => 'required|exists:countries,id',

            'from_date'           => 'required|date',
            // to_date is required IF is_current is false
            'to_date'             => 'nullable|date|after_or_equal:from_date|required_if:is_current,false',
            'is_current'          => 'nullable|boolean',

            'job_description'     => 'nullable|string|max:1000', // Matches the image's character limit
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
