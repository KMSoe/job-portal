<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\Resume;
use Modules\Storage\App\Classes\LocalStorage;

class ApplicantResumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $applicant = Applicant::first();

        $localFilePath = public_path('sample_files/resume.pdf');
        $localFile     = new File($localFilePath);

        $fileName = $localFile->getFilename();
        $fileSize = $localFile->getSize();

        $storage  = new LocalStorage();
        $filePath = $storage->store('resumes', $localFile);

        $resume = Resume::create([
            'applicant_id' => $applicant->id,
            'resume_name'  => $fileName,
            'file_path'    => $filePath,
            'size'         => $fileSize,
            'uploaded_at'  => now(),
            'is_default'   => true,
        ]);
    }
}
