<?php
namespace Modules\Recruitment\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\EducationLevel;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        $levels = [
            // Ordered by precedence (1 being the lowest required level)
            ['name' => 'High School or Equivalent', 'order' => 1],
            ['name' => 'Vocational/Technical School', 'order' => 2],
            ['name' => 'Associate Degree', 'order' => 3],
            ['name' => 'Bachelor\'s Degree', 'order' => 4],
            ['name' => 'Master\'s Degree', 'order' => 5],
            ['name' => 'Doctorate (Ph.D.)', 'order' => 6],
        ];

        foreach ($levels as $level) {
            EducationLevel::firstOrCreate(
                ['name' => $level['name']],
                [
                    'description' => 'Minimum education requirement: ' . $level['name'],
                    'order'       => $level['order'],
                    'is_active'   => true,
                    'created_by'  => $user->id ?? 0,
                    'updated_by'  => $user->id ?? 0,
                ]
            );
        }
    }
}
