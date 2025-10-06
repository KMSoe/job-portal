<?php
namespace Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo'                      => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name'                      => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'name')->ignore($this->company),
            ],
            'registration_name'         => 'nullable|string|max:255',
            'registration_no'           => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('companies', 'registration_no')->ignore($this->company),
            ],

            'founded_at'                => 'nullable|date',
            'phone_dial_code'           => 'required|string|max:10',
            'phone_no'                  => 'required|string|max:20',
            'secondary_phone_dial_code' => 'nullable|string|max:10',
            'secondary_phone_no'        => 'nullable|string|max:20',
            'email'                     => [
                'required',
                'email',
                'max:255',
                Rule::unique('companies', 'email')->ignore($this->company),
            ],
            'secondary_email'           => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('companies', 'secondary_email')->ignore($this->company),
            ],
            'country_id'                => 'required|exists:countries,id',
            'city_id'                   => 'required|exists:cities,id',
            'address'                   => 'nullable|string',
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
