<?php
namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplicationReviewer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_application_reviewers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'reviewer_id',
        'score',
        'comment',
        'status', // pending, draft, done
    ];

    /**
     * Get the job application associated with this review.
     */
    public function application()
    {
        // Assuming your Job Application model is named 'JobApplication'
        return $this->belongsTo(JobApplication::class, 'application_id');
    }

    /**
     * Get the user (reviewer) who submitted this review.
     */
    public function reviewer()
    {
        // Assuming your User model is named 'User'
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
