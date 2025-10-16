<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organization\Entities\Company;
use Modules\Recruitment\Entities\JobPostingTemplate;

class JobPostingTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company   = Company::first();
        $companyId = $company ? $company->id : null;

        $templateData = [
            "title"                      => "Senior Developer",
            "summary"                    => "An Excellent Opportunity for a Mid Level Web Application Developer to join our team.",
            "open_to"                    => "Male/Female",
            "roles_and_responsibilities" => "
To build and maintain Secure/Fast/Scalable Web Applications using PHP Laravel Framework,
Participate in full web application development life cycle,
Write clean, high-quality, high-performance, maintainable code,
Works on project teams or individually, to develop new web applications,
            ",
            "job_requirements"           => "
Degree in Computer Science or equivalent,
Minimum 2 years of working experience in developing web application using PHP Laravel framework,
Proficient in PHP Laravel framework, MySQL database and APIs.,
Proficient understanding of code versioning tools, such as Git,
Experience in app deployment, optimization, and best practices,
Excellent analytic skill and problem-solving skill,
Good personality, excellent communication skill and hardworking,
            ",
            "what_we_can_offer_include"  => true,
            "what_we_can_offer"          => [
                "benefits"             => "Rewards for over performance",
                "highlights"           => "You will be working with very professional, flexible and goal oriented team. After contract expired, has a possibility of landed a full time job.",
                "career_opportunities" => "Learn new skill on the job * We do not focus on the profit alone, we want you to grow with the company.",
            ],
        ];

        JobPostingTemplate::firstOrCreate(
            ['name' => 'Mid-Level Web Developer Template (Default)'],
            [
                'description'   => 'Standard template for mid-level software roles.',
                'company_id'    => $companyId,
                'is_active'     => true,
                'template_data' => $templateData, // Laravel will automatically JSON encode this array
            ]
        );
    }
}
