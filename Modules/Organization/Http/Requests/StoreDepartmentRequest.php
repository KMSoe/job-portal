<?php
namespace Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id'  => 'required|exists:companies,id',
            'name'        => [
                'required',
                'string',
                'max:255',
                // Ensure name is unique within the scope of a single company
                'unique:departments,name,NULL,id,company_id,' . $this->input('company_id'),
            ],
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean', // Since it's nullable in request, but cast to boolean
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
