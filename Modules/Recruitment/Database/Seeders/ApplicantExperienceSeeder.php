<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\ApplicantWorkExperience;
use Modules\Recruitment\Entities\ExperienceLevel;
use Modules\Recruitment\Entities\JobFunction;
use Nnjeim\World\Models\Country;

class ApplicantExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure all required foreign key data exists
        $applicant   = Applicant::first();
        $country     = Country::first();
        $jobFunction = JobFunction::first();
        $expLevel    = ExperienceLevel::first();

        ApplicantWorkExperience::firstOrCreate(
            [
                'applicant_id' => $applicant->id,
                'company_name' => 'Alpha Solutions Tech',
            ],
            [
                'job_title'           => 'Senior Laravel Developer',
                'job_function_id'     => $jobFunction->id,
                'experience_level_id' => $expLevel->id,
                'country_id'          => $country->id,
                'from_date'           => '2021-01-15',
                'to_date'             => null,
                'is_current'          => true,
                'job_description'     => 'Led a team of three developers in building scalable microservices using Laravel and AWS.',
            ]
        );
    }
}
