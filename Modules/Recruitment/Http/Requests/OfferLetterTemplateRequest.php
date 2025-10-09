<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferLetterTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'required|string|max:255|unique:job_posting_templates,name',
            'description'       => 'nullable|string',
            'company_id'        => 'required|exists:companies,id',
            'is_active'         => 'required|boolean',
            'is_salary_visible' => 'required|boolean',
            'content'           => 'required|string',
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
