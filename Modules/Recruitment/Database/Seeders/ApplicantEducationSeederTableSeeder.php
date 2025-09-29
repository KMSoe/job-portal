<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\ApplicantEducation;
use Nnjeim\World\Models\Country;

class ApplicantEducationSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicant = Applicant::first();
        $country   = Country::first();

        ApplicantEducation::firstOrCreate(
            [
                'applicant_id'      => $applicant->id,
                'school_university' => 'Yangon Technological University',
            ],
            [
                'location_division' => 'Yangon',
                'degree_level'      => 'Bachelor\'s Degree',
                'area_of_study'     => 'Computer Science',
                'country_id'        => $country->id,
                'from_date'         => '2015-10-01',
                'to_date'           => '2020-03-31',
                'is_current'        => false,
            ]
        );

        ApplicantEducation::firstOrCreate(
            [
                'applicant_id'      => $applicant->id,
                'school_university' => 'Online Course Platform',
            ],
            [
                'location_division' => 'Remote',
                'degree_level'      => 'Certificate',
                'area_of_study'     => 'Advanced Laravel Development',
                'country_id'        => $country->id,
                'from_date'         => now()->subMonths(6)->format('Y-m-d'),
                'to_date'           => null,
                'is_current'        => true,
            ]
        );
    }
}
