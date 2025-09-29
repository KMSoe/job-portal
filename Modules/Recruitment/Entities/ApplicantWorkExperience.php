<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nnjeim\World\Models\Country;

class ApplicantWorkExperience extends Model
{
    use HasFactory;

    protected $table = 'applicant_work_experiences';

    protected $fillable = [
        'applicant_id',
        'job_title',
        'job_function_id',
        'experience_level_id',
        'company_name',
        'country_id',
        'from_date',
        'to_date',
        'is_current',
        'job_description',
    ];

    protected $casts = [
        'from_date'  => 'date',
        'to_date'    => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the applicant that owns the experience record.
     */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function jobFunction()
    {
        return $this->belongsTo(JobFunction::class);
    }

    public function experienceLevel()
    {
        return $this->belongsTo(ExperienceLevel::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
