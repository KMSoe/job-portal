<?php

namespace Modules\Recruitment\Http\Requests;

use App\Http\Services\ErrorService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class JobApplicationInterviewRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'application_id' => 'required|exists:job_applications,id',
            'interview_type' => 'required|in:online,offline',
            'scheduled_at' => 'required|date_format:Y-m-d H:i:s',
            'duration_minutes' => 'nullable|integer|min:15',
            'location' => 'nullable|required_if:interview_type,offline|string|max:255',
            'status' => 'nullable|string',
            'notes' => 'nullable|string',
            'timezone' => 'nullable|string',
            'interviewers' => 'required|array|min:1',
            'interviewers.*.user_id' => 'required|exists:users,id',
            'interviewers.*.status' => 'required|string|in:confirmed,declined,tentative'
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
