<?php
namespace Modules\Recruitment\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Recruitment\Entities\JobFunction;

class JobFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user   = User::first();

        $functions = [
            'Administrative',
            'Architecture, Design',
            'Consulting',
            'Customer Service, Support',
            'Education, Teaching, Childcare',
            'Engineering, Technical, HSE',
            'Finance, Accounting, Audit',
            'Food and Beverage',
            'Hospitality, Hotel, Tourism',
            'HR, Training and Recruitment',
            'IT Hardware, Software',
            'Legal, Risk and Compliance',
            'Logistics, Warehousing, Port Management',
        ];

        foreach ($functions as $functionName) {
            JobFunction::firstOrCreate(
                ['name' => $functionName],
                [
                    'description' => 'General category for ' . strtolower($functionName) . ' jobs.',
                    'is_active'   => true,
                    'created_by'  => $user->id ?? 0,
                    'updated_by'  => $user->id ?? 0,
                ]
            );
        }
    }
}
