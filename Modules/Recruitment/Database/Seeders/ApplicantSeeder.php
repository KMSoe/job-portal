<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\Applicant;

class ApplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Applicant::create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
