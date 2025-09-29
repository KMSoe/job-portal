<?php
namespace Modules\Organization\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Organization\Entities\Designation;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::first();

        if (! $user) {
            echo "Skipping DesignationSeeder: Ensure User Seeder runs first.\n";
            return;
        }

        $commonDesignations = [
            'CEO'               => 'Chief Executive Officer',
            'Manager'           => 'Responsible for a team or department.',
            'Team Lead'         => 'Guides and directs a small team.',
            'Senior Developer'  => 'Experienced software engineer.',
            'Junior Associate'  => 'Entry-level position.',
            'HR Specialist'     => 'Focuses on personnel and compliance.',
            'Marketing Analyst' => 'Studies market data and trends.',
        ];

        foreach ($commonDesignations as $name => $description) {
            Designation::firstOrCreate(
                ['name' => $name],
                [
                    'description' => $description,
                    'created_by'  => $user->id,
                    'updated_by'  => $user->id,
                ]
            );
        }
    }
}
