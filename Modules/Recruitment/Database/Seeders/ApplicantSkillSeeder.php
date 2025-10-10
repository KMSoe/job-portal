<?php
namespace Modules\Recruitment\Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\Applicant;
use Modules\Recruitment\Entities\Skill;

class ApplicantSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Applicant::count() === 0 || Skill::count() === 0) {
            echo "Skipping ApplicantSkillSeeder: Ensure ApplicantSeeder and SkillSeeder are run first.\n";
            return;
        }

        $applicantIds = Applicant::pluck('id');
        $skillIds = Skill::pluck('id');
        
        $pivotData = [];

        foreach ($applicantIds as $applicantId) {
            $randomSkills = $skillIds->random(rand(2, 4));

            foreach ($randomSkills as $skillId) {
                $pivotData[] = [
                    'applicant_id' => $applicantId,
                    'skill_id' => $skillId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('applicant_skills')->insert($pivotData);
    }
}
