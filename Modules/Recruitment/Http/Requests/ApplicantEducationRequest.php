<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantEducationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location_division' => 'nullable|string|max:255',
            'school_university' => 'required|string|max:255',
            'degree_level'      => 'required|string|max:100',
            'area_of_study'     => 'required|string|max:255',

            'country_id'        => 'nullable|exists:countries,id',

            'from_date'         => 'required|date',
            // To date must be a date, after or equal to from_date, AND required if is_current is false
            'to_date'           => 'nullable|date|after_or_equal:from_date|required_if:is_current,false',
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
