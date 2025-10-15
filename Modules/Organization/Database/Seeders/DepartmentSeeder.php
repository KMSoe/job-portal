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
                Department::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'name'       => $deptName, // Use the base name for commonality
                    ],
                    [
                        'description' => 'Handles all ' . strtolower($deptName) . ' functions for ' . $company->name,
                        'is_active'   => true,
                        'created_by'  => $user->id ?? 0,
                        'updated_by'  => $user->id ?? 0,
                    ]
                );
            }
        }
    }
}
