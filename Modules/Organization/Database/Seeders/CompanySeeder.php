<?php
namespace Modules\Organization\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Modules\Organization\Entities\Company;
use Modules\Storage\App\Classes\LocalStorage;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $localFilePath = public_path('sample_files/avator.png');
        $localFile     = new File($localFilePath);

        $storage  = new LocalStorage();
        $filePath = $storage->store('company_logo', $localFile);

        Company::create(
            [
                'logo'                      => $filePath,
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
