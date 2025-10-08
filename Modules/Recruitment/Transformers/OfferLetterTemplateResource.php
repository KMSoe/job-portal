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
        return parent::toArray($request);
    }
}
