<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistTemplateItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'checklist_template_id',
    ];

    public function checklistTemplate()
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }
}
