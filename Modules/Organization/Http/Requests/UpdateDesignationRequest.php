<?php
namespace Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $designationId = $this->route('designation')->id ?? null;

        return [
            'name'        => [
                'required',
                'string',
                'max:255',
                Rule::unique('designations', 'name')->ignore($designationId),
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
