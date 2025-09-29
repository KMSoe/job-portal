<?php
namespace Modules\Organization\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Organization\Entities\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // 1. Ensure the directory for the logos exists
        if (! \Illuminate\Support\Facades\Storage::disk('public')->exists('logos')) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('logos');
        }

        // 2. Create 10 companies using the factory
        Company::factory()->count(3)->create();
    }
}
