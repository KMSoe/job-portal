<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Organization\Entities\Employee;

class OnboardingChecklistItem extends Model
{
    protected $fillable = [
        'employee_id',
        'checklist_template_item_id',
        'status',
    ];

    public function checklistTemplateItem()
    {
        return $this->belongsTo(ChecklistTemplateItem::class, 'checklist_template_item_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }       
}
