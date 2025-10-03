<?php

namespace Modules\Recruitment\Http\Requests;

use App\Http\Services\ErrorService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class JobApplyRequest extends FormRequest
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
            'expected_salary' => 'nullable|numeric|min:0',
            'resume_id' => 'nullable|exists:resumes,id',
            'supportive_documents' => 'nullable|array',
            'supportive_documents.*.filename' => 'required_with:supportive_documents|string|max:255',
            'supportive_documents.*.path' => 'required_with:supportive_documents|string|max:255',
            'supportive_documents.*.mime_type' => 'required_with:supportive_documents|string|max:100',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'errors'      => $validator->errors()
        ], 422));
    }
}
