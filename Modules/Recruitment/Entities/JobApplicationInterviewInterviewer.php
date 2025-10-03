<?php

namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class JobApplicationInterviewInterviewer extends Model
{
    protected $fillable = [
        'interview_id',
        'user_id',
        'attendance_status',
        'score',
        'feedback',
        'commented_at',
        'comment_status'
    ];

    protected $dates = ['commented_at'];

    public function interview()
    {
        return $this->belongsTo(JobApplicationInterview::class, 'interview_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}