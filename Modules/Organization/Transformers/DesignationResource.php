<?php
namespace Modules\Organization\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DesignationResource extends Resource
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
            'created_by'  => $this->createdBy,
            'updated_by'  => $this->updatedBy,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
