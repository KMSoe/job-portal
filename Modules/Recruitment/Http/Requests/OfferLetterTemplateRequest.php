<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferLetterTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $name_rule = 'required|string|max:255|unique:offer_letter_templates,name';

        if($this->offer_letter_template) {
            $name_rule = ['required', 'string', Rule::unique('offer_letter_templates', 'name')->ignore($this->offer_letter_template)->whereNull('deleted_at')];
        }

        return [
            'name'              => $name_rule,
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
