<?php
namespace Modules\Organization\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Organization\Entities\Company;
use Modules\Organization\Entities\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::all();
        $user      = User::first();

        if ($companies->isEmpty() || ! $user) {
            echo "Skipping DepartmentSeeder: Ensure User and Company Seeders run first.\n";
            return;
        }

        $commonDepartments = [
            'Human Resources',
            'Finance',
            'Marketing',
            'Sales',
            'Engineering',
            'Customer Support',
        ];

        foreach ($companies as $company) {
            foreach ($commonDepartments as $deptName) {
                // Ensure unique name per company
                $name = $deptName . ' - ' . $company->name;

                Department::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'name'       => $deptName, // Use the base name for commonality
                    ],
                    [
                        'description' => 'Handles all ' . strtolower($deptName) . ' functions for ' . $company->name,
                        'is_active'   => true,
                        'created_by'  => $user->id,
                        'updated_by'  => $user->id,
                    ]
                );
            }
        }
    }
}
