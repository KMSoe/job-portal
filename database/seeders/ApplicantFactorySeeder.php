<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\Resume;
use Modules\Storage\App\Classes\LocalStorage;

class ApplicantFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Applicant::factory(30)->create([
            'open_to_work'        => true,
            'experience_level_id' => 1,
            'job_function_id'     => 1,
            'salary_currency_id'  => 1,
        ]);

        $applicants = Applicant::all();
        $storage    = new LocalStorage();

        foreach ($applicants as $applicant) {
            $resume = Resume::where('applicant_id', $applicant->id)->first();

            if (! $resume) {
                $naingaungRresumeLocalFilePath = public_path('sample_files/naingaung.pdf');
                $naingaungResumeLocalFile      = new File($naingaungRresumeLocalFilePath);
                $fileSize                      = $naingaungResumeLocalFile->getSize();
                $filePath                      = $storage->store('resumes/' . Str::slug($applicant->name), $naingaungResumeLocalFile);

                $resume = Resume::create(attributes: [
                    'applicant_id' => $applicant->id,
                    'resume_name'  => "$applicant->name.pdf",
                    'file_path'    => $filePath,
                    'size'         => $fileSize,
                    'uploaded_at'  => now(),
                    'is_default'   => true,
                ]);
            }
        }
    }
}
