<?php

namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Organization\Entities\Designation;

class JobOfferInformBccUser extends Model
{
   use HasFactory;

    protected $table = 'job_offer_inform_bcc_users';

    protected $fillable = [
        'job_offer_id',
        'user_id',
        'designation_id',
    ];

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class, 'job_offer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }
}
