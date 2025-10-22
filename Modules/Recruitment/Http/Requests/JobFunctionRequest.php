<?php
namespace Modules\Recruitment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobFunctionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => isset($this->job_function) && ! empty($this->job_function)
                ? ['required', 'string', Rule::unique('job_functions', 'name')->ignore($this->job_function)->whereNull('deleted_at')]
                : ['required', 'string', Rule::unique('job_functions', 'name')->whereNull('deleted_at')],
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
