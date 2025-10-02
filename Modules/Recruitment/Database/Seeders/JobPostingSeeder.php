<?php
namespace Modules\Recruitment\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;
use Modules\Organization\Entities\Designation;
use Modules\Recruitment\Entities\EducationLevel;
use Modules\Recruitment\Entities\ExperienceLevel;
use Modules\Recruitment\Entities\JobFunction;
use Modules\Recruitment\Entities\JobPosting;
use Modules\Recruitment\Entities\JobPostingTemplate;
use Nnjeim\World\Models\Currency;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure all required foreign key data exists
        $company     = Company::inRandomOrder()->firstOrFail();
        $department  = Department::inRandomOrder()->firstOrFail();
        $designation = Designation::inRandomOrder()->firstOrFail();
        $template    = JobPostingTemplate::inRandomOrder()->firstOrFail();
        $expLevel    = ExperienceLevel::orderBy('order')->firstOrFail(); // Mid-level
        $jobFunction = JobFunction::inRandomOrder()->firstOrFail();
        $eduLevel    = EducationLevel::orderBy('order')->firstOrFail(); // Bachelor's
        $currency    = Currency::first();
        $user        = User::firstOrFail();

        $title = 'Senior Backend Engineer - Laravel';

        JobPosting::create(
            [
                // Organizational
                'company_id'                             => $company->id,
                'department_id'                          => $department->id,
                'designation_id'                         => $designation->id,
                'template_id'                            => $template->id,

                // Job Details
                'title'                                  => $title,
                'experience_level_id'                    => $expLevel->id,
                'job_function_id'                        => $jobFunction->id,
                'min_education_level_id'                  => $eduLevel->id,
                'summary'                                => 'Design, build, and maintain efficient, reusable, and reliable PHP code.',
                'open_to'                                => 'Male/Female',
                'roles_and_responsibilities'             => "Design, Code, Review, Mentor.",
                'requirements'                           => "5+ years Laravel, solid MySQL, AWS experience.",
                'what_we_can_offer_include'              => true,
                'what_we_can_offer_benefits'             => 'Health insurance, annual bonuses.',
                'what_we_can_offer_highlights'           => 'Work with a great team on exciting projects.',
                'what_we_can_offer_career_opportunities' => 'Pathway to Principal Engineer.',

                // Type and Location
                'job_type'                               => 'Full-Time',
                'work_arrangement'                       => 'Hybrid',
                'location'                               => 'New York, NY',

                // Compensation (Example: Range Salary)
                'salary_type'                            => 'Range',
                'salary_currency_id'                     => $currency->id,
                'min_salary'                             => 90000.00,
                'max_salary'                             => 130000.00,
                'salary_notes'                           => 'Dependent on experience and location.',

                // Status and Dates
                'vacancies'                              => 2,
                'status'                                 => 'Published',
                'published_at'                           => now(),
                'deadline_date'                          => now()->addDays(30),

                // Auditing
                'created_by'                             => $user->id,
                'updated_by'                             => $user->id,
            ]
        );
    }
}
