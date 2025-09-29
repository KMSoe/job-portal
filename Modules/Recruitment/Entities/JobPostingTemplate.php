<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Organization\Entities\Company;

class JobPostingTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_posting_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'company_id',
        'template_data',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'template_data' => 'array', // Casts the JSON column to a PHP array/object
        'is_active' => 'boolean',
    ];

    // --- Relationships ---

    /**
     * Get the company that owns the template.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
