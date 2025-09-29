<?php
namespace Modules\Organization\Entities;

use App\Helpers\General;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nnjeim\World\Models\Currency;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'user_id',
        'company_id',
        'employee_code',
        'name',
        'phone_dial_code',
        'phone_no',
        'offered_date',
        'joined_date',
        'last_date',
        'department_id',
        'designation_id',
        'employment_type',
        'salary_currency_id',
        'basic_salary',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'offered_date'    => 'date',
        'joined_date'     => 'date',
        'last_date'       => 'date',
        'employment_type' => EmploymentTypes::class, 
    ];

    public function setBasicSalaryAttribute($value)
    {
        $this->attributes['basic_salary'] = $value ? General::encryptData($value) : null;
    }

    public function getBasicSalaryAttribute($value)
    {
        return $value ? (float) General::decryptData($value) : null;
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
