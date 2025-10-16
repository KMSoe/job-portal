<?php

namespace Modules\Recruitment\Entities;

use Google\Service\Batch\Job;
use Illuminate\Database\Eloquent\Model;

class JobApplicationInterview extends Model
{
    protected $fillable = [
        'title',
        'description',
        'application_id',
        'interview_type',
        'scheduled_at',
        'duration_minutes',
        'location',
        'status',
        'notes',
        'google_event_id',
        'google_meet_link',
        'reminder_sent'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }

    public function interviewers()
    {
        return $this->hasMany(JobApplicationInterviewInterviewer::class, 'interview_id');
    }

    public function getCanCommentAttribute()
    {
        $currentUser = auth()->user();

        return $this->interviewers->contains(function ($interviewer) use ($currentUser) {
            return $interviewer->user_id === $currentUser->id;
        });
    }
}
