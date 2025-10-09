<?php
namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_posting_id',
        'job_aplication_id', // Consider renaming to 'job_application_id'
        'candicate_id',      // Consider renaming to 'candidate_id'
        'company_id',
        'department_id',
        'designation_id',
        'offer_letter_template_id',
        'salary_currency_id',
        'basic_salary',
        'employment_type',
        'approve_required',
        'approver_id',
        'approver_position_id',
        'approver_signature',
        'offer_date',
        'joined_date',
        'status',
    ];

    protected $casts = [
        'approve_required' => 'boolean',
        'offer_date'       => 'date',
        'joined_date'      => 'date',
        'basic_salary'     => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Core Belongs To Relationships
    |--------------------------------------------------------------------------
    */

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_aplication_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candicate_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function template()
    {
        return $this->belongsTo(OfferLetterTemplate::class, 'offer_letter_template_id');
    }

    public function approver()
    {
        // Assuming the approver is a User
        return $this->belongsTo(User::class, 'approver_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Has Many / Belongs To Many Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the attachments associated with the job offer (One-to-Many).
     */
    public function attachments()
    {
        return $this->hasMany(JobOfferAttachment::class, 'job_offer_id');
    }

    /**
     * Get the departments to be informed (Many-to-Many via pivot table).
     */
    public function informedDepartments()
    {
        return $this->belongsToMany(
            Department::class,
            'job_offer_inform_to_departments',
            'job_offer_id',
            'department_id'
        );
    }

    /**
     * Get the users to be CC'd on the offer (Many-to-Many via pivot table).
     */
    public function ccUsers()
    {
        return $this->belongsToMany(
            User::class,
            'job_offer_inform_cc_users',
            'job_offer_id',
            'user_id'
        )->withPivot('designation_id');
    }
}
