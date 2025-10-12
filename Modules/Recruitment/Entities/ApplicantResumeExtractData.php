<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantResumeExtractData extends Model
{
    use HasFactory;

    protected $table = 'applicant_resume_extract_data';

    protected $fillable = [
        'job_application_id',
        'extract_data',
    ];

    protected $casts = [
        'extract_data' => 'array',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
}
