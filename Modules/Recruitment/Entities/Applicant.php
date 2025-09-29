<?php
namespace Modules\Recruitment\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Storage\App\Classes\LocalStorage;
use Nnjeim\World\Models\Currency;

class Applicant extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'photo',
        'job_title',
        'phone_dial_code',
        'phone_no',
        'email',
        'password',
        'open_to_work',
        'experience_level_id',
        'job_function_id',
        'salary_currency_id',
        'expected_salary',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'open_to_work'      => 'boolean', // Cast the boolean field
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            $storage = new LocalStorage();
            return $storage->getUrl($this->photo);
        }
        return null;
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'applicant_skills', 'applicant_id', 'skill_id');
    }

    public function salaryCurrency()
    {
        return $this->belongsTo(Currency::class, 'salary_currency_id');
    }

    public function experienceLevel()
    {
        return $this->belongsTo(ExperienceLevel::class, 'experience_level_id');
    }

    public function jobFunction()
    {
        return $this->belongsTo(JobFunction::class, 'job_function_id');
    }

    public function workExperiences()
    {
        return $this->hasMany(ApplicantWorkExperience::class, 'applicant_id');
    }
}
