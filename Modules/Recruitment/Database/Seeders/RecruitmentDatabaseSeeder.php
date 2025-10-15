<?php
namespace Modules\Recruitment\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class RecruitmentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(JobFunctionSeeder::class);
        $this->call(ExperienceLevelSeeder::class);
        $this->call(EducationLevelSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(JobPostingTemplateSeeder::class);
        $this->call(JobPostingSeeder::class);
        $this->call(ApplicantSeeder::class);
        $this->call(ApplicantSkillSeeder::class);
        $this->call(OfferLetterTemplateSeeder::class);
    }
}
