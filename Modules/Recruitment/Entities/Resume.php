<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Storage\App\Classes\LocalStorage;

class Resume extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'applicant_id',
        'resume_name',
        'file_path',
        'size',
        'uploaded_at',
        'is_default',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
        'is_default'  => 'boolean',
    ];

    protected $appends = [
        'file_path_url',
    ];

    public function getFilePathUrlAttribute(): ?string
    {
        if ($this->file_path) {
            $storage = new LocalStorage();
            return $storage->getUrl($this->file_path);
        }
        return null;
    }

    /**
     * Get the applicant that owns the resume.
     */
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
