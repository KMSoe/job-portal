<?php
namespace Modules\Organization\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class DesignationResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'created_by'  => $this->createdBy ? $this->createdBy : [
                "id"   => 0,
                "name" => '',
            ],
            'updated_by'  => $this->updatedBy ? $this->updatedBy : [
                "id"   => 0,
                "name" => '',
            ],
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
