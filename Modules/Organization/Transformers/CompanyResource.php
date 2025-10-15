<?php
namespace Modules\Organization\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'id'                        => $this->id,
            'logo_url'                  => $this->logo_url, // Uses the custom accessor defined in the model
            'name'                      => $this->name,
            'registration_name'         => $this->registration_name,
            'registration_no'           => $this->registration_no,
            'founded_at'                => $this->founded_at, // Formats the date object

            // Contact Information
            'phone_dial_code'           => $this->phone_dial_code,
            'phone_no'                  => $this->phone_no,
            'phone_no_full'             => $this->phone_dial_code . $this->phone_no,
            'primary_phone'             => $this->primary_phone,
            'secondary_phone_dial_code' => $this->secondary_phone_dial_code,
            'secondary_phone_no'        => $this->secondary_phone_no,
            'secondary_phone_no_full'   => $this->secondary_phone_dial_code . $this->secondary_phone_no,
            'secondary_phone'           => $this->secondary_phone,
            'email'                     => $this->email,
            'secondary_email'           => $this->secondary_email,

            // Location
            'country'                   => $this->country,
            'city'                      => $this->city,
            'address'                   => $this->address,

            // Audit Timestamps (Optional, but useful)
            'created_by'                => $this->createdBy ? $this->createdBy : [
                "id"   => 0,
                "name" => '',
            ],
            'updated_by'                => $this->updatedBy ? $this->updatedBy : [
                "id"   => 0,
                "name" => '',
            ],
            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,
        ];
    }
}
