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
        // if (! \Illuminate\Support\Facades\Storage::disk('public')->exists('logos')) {
        //     \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('logos');
        // }

        // 2. Create 10 companies using the factory
        // Company::factory()->count(3)->create();

        Company::create(
            [
                'logo'                      => '/images/default/company_logo.png',
                'name'                      => 'Example Corp',
                'registration_name'         => 'Example Corp Solutions Inc.',
                'registration_no'           => 'RNG-98765432',
                'founded_at'                => '2005-08-15',

                // Phone numbers
                'phone_dial_code'           => '+1',
                'phone_no'                  => '555-1234',
                'secondary_phone_dial_code' => '+44',
                'secondary_phone_no'        => '77000-0001',

                // Emails
                'email'                     => 'contact@examplecorp.com',
                'secondary_email'           => 'support@examplecorp.net',

                                                  // Relationships (Assuming Country and City seeders run first)
                'country_id'                => 1, // Example ID for a Country
                'city_id'                   => 1, // Example ID for a City

                'address'                   => '123 Main St, Anytown',

                                                  // Audit columns
                'created_by'                => 1, // Example ID for a User
                'updated_by'                => 1, // Example ID for a User
            ]
        );
    }
}
