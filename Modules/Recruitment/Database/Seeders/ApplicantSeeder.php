<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Hash;
use Modules\Recruitment\Entities\Applicant;
use Modules\Storage\App\Classes\LocalStorage;

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

        $storage  = new LocalStorage();
        $filePath = $storage->store('applicant_profiles', $localFile);

        Applicant::create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => Hash::make('password'),
            'photo'    => $filePath,
        ]);
    }
}
