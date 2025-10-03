<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'applicant_id',
        'resume_name',
        'size',
        'uploaded_at'
    ];

    protected $dates = ['uploaded_at'];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id');
    }
}
