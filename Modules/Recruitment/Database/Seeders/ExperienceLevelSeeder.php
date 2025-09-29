<?php
namespace Modules\Recruitment\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\ExperienceLevel;

class ExperienceLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get an existing user ID for audit columns
        $user   = User::first();
        $userId = $user->id;

        $levels = [
            // Ordered by experience (0 being the least experienced)
            ['name' => 'Internship', 'order' => 1],
            ['name' => 'Entry Level', 'order' => 2],
            ['name' => 'Associate', 'order' => 3],
            ['name' => 'Mid-Senior Level', 'order' => 4],
            ['name' => 'Director', 'order' => 5],
            ['name' => 'Executive', 'order' => 6],
        ];

        $i = 0;
        foreach ($levels as $level) {
            $i++;
            ExperienceLevel::firstOrCreate(
                ['name' => $level['name']],
                [
                    'description' => $level['name'] . ' experience level.',
                    'order'       => $level['order'],
                    'is_active'   => true,
                    'created_by'  => $userId,
                    'updated_by'  => $userId,
                ]
            );
        }
    }
}
