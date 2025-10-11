<?php
namespace Modules\Organization\Entities;

use App\Helpers\General;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Modules\Organization\App\Enums\EmploymentTypes;
use Modules\Recruitment\Entities\ChecklistTemplate;
use Modules\Recruitment\Entities\OnboardingChecklistItem;
use Nnjeim\World\Models\Currency;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'name',
        'preferred_name',
        'email',
        'work_mail',
        'company_id',
        'department_id',
        'designation_id',
        'employment_type',
        'gender',
        'marital_status',
        'nationality',
        'race',
        'religion',
        'primary_phone_dial_code',
        'primary_phone_no',
        'secondary_phone_dial_code',
        'secondary_phone_no',
        'id_nrc',
        'passport',
        'address',
        'bank_name',
        'bank_account_no',
        'salary_currency_id',
        'basic_salary',
        'employee_code',
        'offered_date',
        'joined_date',
        'last_date',
        'created_by',
        'updated_by',
        'onboarding_checklist_template_id'
    ];

    protected $casts = [
        'offered_date'    => 'date',
        'joined_date'     => 'date',
        'last_date'       => 'date',
        'employment_type' => EmploymentTypes::class,
    ];

    public function setBasicSalaryAttribute($value)
    {
        $this->attributes['basic_salary'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getBasicSalaryAttribute($value)
    {
        return $value ? (float) Crypt::decryptString($value) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function salaryCurrency()
    {
        return $this->belongsTo(Currency::class, 'salary_currency_id');
    }

    public function onboardingChecklistTemplate()
    {
        return $this->belongsTo(ChecklistTemplate::class, 'onboarding_checklist_template_id');
    }

    public function onboardingChecklistItems()
    {
        return $this->hasMany(OnboardingChecklistItem::class, 'employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}