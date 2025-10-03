<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;

class JobApplicationSupportiveDocument extends Model
{
    protected $fillable = [
        'application_id',
        'path',
        'filename',
        'mime_type'
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }
}
