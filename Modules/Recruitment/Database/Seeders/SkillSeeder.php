<?php
namespace Modules\Recruitment\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user   = User::first();
        $userId = $user->id;

        $sampleSkills = [
            // Hard Skills
            'PHP',
            'Laravel',
            'JavaScript',
            'React',
            'SQL (MySQL/PostgreSQL)',
            'Git',
            'AWS',
            'Data Analysis',

            // Soft Skills
            'Communication',
            'Teamwork',
            'Problem Solving',
            'Time Management',
            'Adaptability',
            'Leadership',
        ];

        foreach ($sampleSkills as $skillName) {
            Skill::firstOrCreate(
                ['name' => $skillName],
                [
                    'description' => 'A fundamental skill in ' . strtolower($skillName) . '.',
                    'is_active'   => true,
                    'created_by'  => $userId,
                    'updated_by'  => $userId,
                ]
            );
        }
    }
}
