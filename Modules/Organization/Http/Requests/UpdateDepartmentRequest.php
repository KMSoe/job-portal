<?php

namespace Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         $departmentId = $this->route('department')->id ?? null;

        return [
            'company_id'    => 'required|exists:companies,id',
            'name'          => [
                'required',
                'string',
                'max:255',
                // Ignore the current department ID while checking unique name within the same company
                Rule::unique('departments', 'name')->where(fn ($query) => $query->where('company_id', $this->input('company_id')))->ignore($departmentId),
            ],
            'description'   => 'nullable|string',
            'is_active'     => 'nullable|boolean',
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
