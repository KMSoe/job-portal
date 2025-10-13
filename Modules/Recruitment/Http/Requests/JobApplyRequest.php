<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Modules\Recruitment\Entities\Resume;

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
        $applicant            = auth()->guard('applicant')->user();
        $applicant_resume_ids = Resume::where('applicant_id', $applicant->id)->pluck('id')->toArray();

        return [
            'expected_salary'        => 'nullable|numeric|min:0',
            'resume_id'              => ['required', Rule::in($applicant_resume_ids)],
            'supportive_documents'   => 'nullable|array',
            'supportive_documents.*' => [
                'required',
                'file',
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
