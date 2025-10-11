<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OnboardingChecklistItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                      => $this->id,
            'employee'                => $this->employee,
            'checklist_template_item' => $this->checklistTemplateItem ? new ChecklistTemplateItemResource($this->checklistTemplateItem) : null,
            'status'                  => $this->status ?? 'not_started',
        ];
    }
}
