<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Hash;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\ApplicantWorkExperience;
use Modules\Recruitment\Entities\ExperienceLevel;
use Modules\Recruitment\Entities\JobFunction;
use Modules\Recruitment\Entities\Resume;
use Modules\Storage\App\Classes\LocalStorage;
use Nnjeim\World\Models\Country;

class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $localFilePath = public_path('sample_files/avator.png');
        $localFile     = new File($localFilePath);

        $storage = new LocalStorage();

        $country     = Country::first();
        $jobFunction = JobFunction::first();
        $expLevel    = ExperienceLevel::first();

        $storage     = new LocalStorage();
        $kmsFilePath = $storage->store('applicant_profiles', $localFile);
        $kms         = Applicant::create([
            'name'                => 'Kaung Myat Soe',
            'email'               => 'kaungmyatsoe.m192@gmail.com',
            'photo'               => $kmsFilePath,
            'password'            => Hash::make('password'),
            'job_title'           => 'Web Developer',
            'phone_dial_code'     => '+95',
            'phone_no'            => '9986507933',
            'open_to_work'        => true,
            'experience_level_id' => 1,
            'job_function_id'     => 1,
            'salary_currency_id'  => 1,
            'expected_salary'     => 200000,
        ]);
        $kmsRresumeLocalFilePath = public_path('sample_files/kms.pdf');
        $kmsResumeLocalFile      = new File($kmsRresumeLocalFilePath);
        $fileSize                = $kmsResumeLocalFile->getSize();
        $filePath                = $storage->store('resumes', $kmsResumeLocalFile);

        $resume = Resume::create([
            'applicant_id' => $kms->id,
            'resume_name'  => "kaungmyatsoe.pdf",
            'file_path'    => $filePath,
            'size'         => $fileSize,
            'uploaded_at'  => now(),
            'is_default'   => true,
        ]);

        ApplicantWorkExperience::firstOrCreate(
            [
                'applicant_id' => $kms->id,
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

        $yemyatFilePath = $storage->store('applicant_profiles', $localFile);
        $yemyat         = Applicant::create([
            'name'                => 'Ye Myat Sandi Oo',
            'email'               => 'yemyatsandi@gmail.com',
            'photo'               => $yemyatFilePath,
            'password'            => Hash::make('password'),
            'job_title'           => 'Web Developer',
            'phone_dial_code'     => '+95',
            'phone_no'            => '9986507934',
            'open_to_work'        => true,
            'experience_level_id' => 1,
            'job_function_id'     => 1,
            'salary_currency_id'  => 1,
            'expected_salary'     => 200000,
        ]);
        $yemyatRresumeLocalFilePath = public_path('sample_files/yemyat.pdf');
        $yemyatResumeLocalFile      = new File($yemyatRresumeLocalFilePath);
        $fileSize                   = $yemyatResumeLocalFile->getSize();
        $filePath                   = $storage->store('resumes', $yemyatResumeLocalFile);

        $resume = Resume::create([
            'applicant_id' => $yemyat->id,
            'resume_name'  => "yemyatsandi.pdf",
            'file_path'    => $filePath,
            'size'         => $fileSize,
            'uploaded_at'  => now(),
            'is_default'   => true,
        ]);

        ApplicantWorkExperience::firstOrCreate(
            [
                'applicant_id' => $yemyat->id,
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

        $thuyainFilePath = $storage->store('applicant_profiles', $localFile);
        $thuyain         = Applicant::create([
            'name'                => 'Thuyain Soe',
            'email'               => 'thuyainsoe163361@gmail.com',
            'photo'               => $thuyainFilePath,
            'password'            => Hash::make('password'),
            'job_title'           => 'Web Developer',
            'phone_dial_code'     => '+95',
            'phone_no'            => '9986507935',
            'open_to_work'        => true,
            'experience_level_id' => 1,
            'job_function_id'     => 1,
            'salary_currency_id'  => 1,
            'expected_salary'     => 200000,
        ]);
        $thuyainRresumeLocalFilePath = public_path('sample_files/thuyain.pdf');
        $thuyainResumeLocalFile      = new File($thuyainRresumeLocalFilePath);
        $fileSize                    = $thuyainResumeLocalFile->getSize();
        $filePath                    = $storage->store('resumes', $thuyainResumeLocalFile);

        $resume = Resume::create([
            'applicant_id' => $thuyain->id,
            'resume_name'  => "thuyainsoe.pdf",
            'file_path'    => $filePath,
            'size'         => $fileSize,
            'uploaded_at'  => now(),
            'is_default'   => true,
        ]);
        ApplicantWorkExperience::firstOrCreate(
            [
                'applicant_id' => $thuyain->id,
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

        $naingaungFilePath = $storage->store('applicant_profiles', $localFile);
        $naingaung         = Applicant::create([
            'name'                => 'Naing Aung Zaw',
            'email'               => 'naingaung9863@gmail.com',
            'photo'               => $naingaungFilePath,
            'password'            => Hash::make('password'),
            'job_title'           => 'Web Developer',
            'phone_dial_code'     => '+95',
            'phone_no'            => '9986507936',
            'open_to_work'        => true,
            'experience_level_id' => 1,
            'job_function_id'     => 1,
            'salary_currency_id'  => 1,
            'expected_salary'     => 200000,
        ]);
        $naingaungRresumeLocalFilePath = public_path('sample_files/naingaung.pdf');
        $naingaungResumeLocalFile      = new File($naingaungRresumeLocalFilePath);
        $fileSize                      = $naingaungResumeLocalFile->getSize();
        $filePath                      = $storage->store('resumes', $naingaungResumeLocalFile);

        $resume = Resume::create([
            'applicant_id' => $naingaung->id,
            'resume_name'  => "naingaung.pdf",
            'file_path'    => $filePath,
            'size'         => $fileSize,
            'uploaded_at'  => now(),
            'is_default'   => true,
        ]);

        ApplicantWorkExperience::firstOrCreate(
            [
                'applicant_id' => $naingaung->id,
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
