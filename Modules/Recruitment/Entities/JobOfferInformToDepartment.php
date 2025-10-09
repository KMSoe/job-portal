<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Organization\Entities\Department;

class JobOfferInformToDepartment extends Model
{
    use HasFactory;

    protected $table = 'job_offer_inform_to_departments';

    protected $fillable = [
        'job_offer_id',
        'department_id',
    ];

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class, 'job_offer_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
