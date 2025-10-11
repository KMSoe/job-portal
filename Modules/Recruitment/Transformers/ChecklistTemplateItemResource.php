<?php
namespace Modules\Recruitment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistTemplateItemResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'employees'              => $this->employees->map(function ($employee) {
                return [
                    'id'   => $employee->id,
                    'name' => $employee->name,
                ];
            }),
            'can_status_update'=> $this->employees()->pluck('id')->contains(auth()->id()),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
