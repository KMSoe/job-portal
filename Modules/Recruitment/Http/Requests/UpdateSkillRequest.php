<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $skillId = $this->route('skill')->id ?? null;

        return [
            'name'        => [
                'required',
                'string',
                'max:255',
                // Ignore the current skill ID when checking for uniqueness
                Rule::unique('skills', 'name')->ignore($skillId),
            ],
            'description' => 'nullable|string',
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
