<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;
use Nnjeim\World\Models\Currency;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class JobPosting extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $table = 'job_postings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Organizational
        'company_id',
        'department_id',
        'designation_id',
        'template_id',

        // Job Details
        'title',
        'slug',
        'experience_level_id',
        'job_function_id',
        'min_eduction_level_id',
        'summary',
        'open_to',
        'roles_and_responsibilities',
        'requirements',
        'what_we_can_offer_include',
        'what_we_can_offer_benefits',
        'what_we_can_offer_highlights',
        'what_we_can_offer_career_opportunities',

        // Type and Location
        'job_type',
        'work_arrangement',
        'location',

        // Compensation
        'salary_type',
        'salary_currency_id',
        'salary_amount',
        'min_salary',
        'max_salary',
        'salary_notes',

        // Status and Dates
        'vacancies',
        'status',
        'published_at',
        'deadline_date',

        // Auditing
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'what_we_can_offer_include' => 'boolean',
        'salary_amount'             => 'float',
        'min_salary'                => 'float',
        'max_salary'                => 'float',
        'published_at'              => 'datetime',
        'deadline_date'             => 'datetime',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    // --- Relationships ---

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function template()
    {
        return $this->belongsTo(JobPostingTemplate::class, 'template_id');
    }

    public function experienceLevel()
    {
        return $this->belongsTo(ExperienceLevel::class); // Assuming this model exists
    }

    public function jobFunction()
    {
        return $this->belongsTo(JobFunction::class); // Assuming this model exists
    }

    public function minimumEducationLevel()
    {
        return $this->belongsTo(EducationLevel::class, 'min_eduction_level_id'); // Assuming EducationLevel model exists
    }

    public function salaryCurrency()
    {
        return $this->belongsTo(Currency::class, 'salary_currency_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_posting_skills', 'job_posting_id', 'skill_id');
    }
}
