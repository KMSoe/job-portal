<?php

namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistTemplate extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function items()
    {
        return $this->hasMany(ChecklistTemplateItem::class, 'checklist_template_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
