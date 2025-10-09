<?php
namespace Modules\Recruitment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;
use Nnjeim\World\Models\Currency;

class JobOffer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_offers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_posting_id',
        'job_aplication_id',
        'candicate_id',
        'company_id',
        'department_id',
        'designation_id',
        'offer_letter_template_id',
        'salary_currency_id',
        'basic_salary',
        'employment_type',
        'approve_required',
        'approver_id',
        'approver_signature',
        'offer_date',
        'joined_date',
        'status',
        'offer_letter_file',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'approve_required' => 'boolean',
        'offer_date'       => 'date',
        'joined_date'      => 'date',
        'basic_salary'     => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the job posting associated with the job offer.
     */
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    /**
     * Get the job application associated with the job offer.
     */
    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_aplication_id');
    }

    /**
     * Get the candidate (applicant) associated with the job offer.
     */
    public function candidate()
    {
        return $this->belongsTo(Applicant::class, 'candicate_id');
    }

    /**
     * Get the company associated with the job offer.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the department associated with the job offer.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the designation associated with the job offer.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    /**
     * Get the offer letter template used for this offer.
     */
    public function offerLetterTemplate()
    {
        return $this->belongsTo(OfferLetterTemplate::class, 'offer_letter_template_id');
    }

    /**
     * Get the currency used for the salary.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'salary_currency_id');
    }

    /**
     * Get the user who approved the job offer.
     */
    public function approver()
    {
        // Assuming Approver is a User model
        return $this->belongsTo(User::class, 'approver_id');
    }
}
