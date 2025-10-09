<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferLetterTemplateResource extends JsonResource
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
            'name'              => $this->name,
            'description'       => $this->description,
            'company'           => $this->company,
            'is_salary_visible' => (bool) $this->is_salary_visible,
            'template_data'     => $this->template_data,
            'is_active'         => (bool) $this->is_active,
            'created_by'        => $this->createdBy,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
