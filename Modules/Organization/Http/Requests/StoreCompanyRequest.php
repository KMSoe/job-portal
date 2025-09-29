<?php
namespace Modules\Organization\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo'                      => 'nullable|file|mimes:jpeg,png,jpg,gif,svg',      // Image or SVG
            'name'                      => 'required|string|max:255|unique:companies,name', // Must be unique
            'registration_name'         => 'nullable|string|max:255',
            'registration_no'           => 'nullable|string|max:255|unique:companies,registration_no',
            'founded_at'                => 'nullable|date',
            'phone_dial_code'           => 'required|string|max:10',
            'phone_no'                  => 'required|string|max:20',
            'secondary_phone_dial_code' => 'nullable|string|max:10',
            'secondary_phone_no'        => 'nullable|string|max:20',
            'email'                     => 'required|email|max:255|unique:companies,email',
            'secondary_email'           => 'nullable|email|max:255|unique:companies,secondary_email',
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
