<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Storage\App\Classes\LocalStorage;

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
        'uploaded_cv_name',
        'uploaded_cv_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function getUploadedCvUrlAttribute(): ?string
    {
        if ($this->uploaded_cv) {
            $storage = new LocalStorage();
            return $storage->getUrl($this->uploaded_cv);
        }
        return null;
    }

    /**
     * Get the applicant that owns the job application.
     */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
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
}
