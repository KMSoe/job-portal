<?php

namespace Modules\Recruitment\Http\Requests;

use App\Http\Services\ErrorService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class ChecklistTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => isset($this->checklist_template) && !empty($this->checklist_template)
                    ? ['required', 'string', Rule::unique('checklist_templates', 'name')->ignore($this->checklist_template)->whereNull('deleted_at')]
                    : ['required', 'string', Rule::unique('checklist_templates', 'name')->whereNull('deleted_at')],
            'description' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.name' => 'distinct',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one checklist item is required.',
            'items.*.name.required' => 'Each checklist item must have a name.',
            'items.*.name.distinct' => 'Checklist item names must be unique.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'errors'      => $validator->errors()
        ], ErrorService::UNPROCESSABLE_CONTENT));
    }
}
