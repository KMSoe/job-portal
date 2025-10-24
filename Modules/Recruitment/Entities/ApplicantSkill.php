<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Database\Eloquent\Model;

class ApplicantSkill extends Model
{
    protected $fillable = [
        'applicant_id',
        'skill_id',
    ];
}
