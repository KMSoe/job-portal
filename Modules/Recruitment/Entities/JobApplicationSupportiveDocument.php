<?php

namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Storage\App\Classes\LocalStorage;

class JobApplicationSupportiveDocument extends Model
{
    protected $fillable = [
        'application_id',
        'path',
        'filename',
        'mime_type'
    ];

    protected $appends = ['path_url'];

    public function getPathUrlAttribute(): ?string
    {
        if ($this->path) {
            $storage = new LocalStorage();
            return $storage->getUrl($this->path);
        }
        return null;
    }

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }
}
