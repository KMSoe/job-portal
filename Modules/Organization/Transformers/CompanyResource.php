<?php
namespace Modules\Organization\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class CompanyResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'logo_url'          => $this->logo_url, // Uses the custom accessor defined in the model
            'name'              => $this->name,
            'registration_name' => $this->registration_name,
            'registration_no'   => $this->registration_no,
            'founded_at'        => $this->founded_at?->format('Y-m-d'), // Formats the date object

            // Contact Information
            'phone'             => [
                'primary'   => [
                    'dial_code' => $this->phone_dial_code,
                    'number'    => $this->phone_no,
                    'full'      => $this->phone_dial_code . $this->phone_no,
                ],
                'secondary' => [
                    'dial_code' => $this->secondary_phone_dial_code,
                    'number'    => $this->secondary_phone_no,
                    'full'      => $this->secondary_phone_dial_code . $this->secondary_phone_no,
                ],
            ],
            'email'             => $this->email,
            'secondary_email'   => $this->secondary_email,

            // Location
            'country_id'        => $this->country_id,
            'city_id'           => $this->city_id,
            'address'           => $this->address,

            // Audit Timestamps (Optional, but useful)
            'created_by'        => $this->createdBy,
            'updated_by'        => $this->updatedBy,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
