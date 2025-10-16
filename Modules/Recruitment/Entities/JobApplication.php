<?php
namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_applications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'applicant_id',
        'job_posting_id',
        'status',
        'applied_at',
        'expected_salary',
        'resume_id',
        'recruiter_comment',
        'last_updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'applied_at' => 'datetime',
    ];

    /**
     * Get the applicant that owns the job application.
     */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    /**
     * Get the job posting associated with the application.
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function supportiveDocuments()
    {
        return $this->hasMany(JobApplicationSupportiveDocument::class);
    }

    public function resume()
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }

    public function extractedData()
    {
        return $this->hasOne(ApplicantResumeExtractData::class, 'job_application_id');
    }

    public function reviewers()
    {
        return $this->hasMany(JobApplicationReviewer::class, 'application_id')->where('status', 'done');
    }

    public function interviews()
    {
        return $this->hasMany(JobApplicationInterview::class, 'application_id');
    }

    public function jobOffer()
    {
        return $this->hasOne(JobOffer::class, 'job_application_id');
    }
}
