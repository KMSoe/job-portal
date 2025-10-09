<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Storage\App\Classes\LocalStorage;

class JobOfferAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_offer_id',
        'file_path',
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
     * Get the job offer that owns the attachment.
     */
    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class, 'job_offer_id');
    }
}
