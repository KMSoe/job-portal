<?php
namespace Modules\Organization\Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\City;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ensure a user exists to be the creator
        $createdBy = User::first();

        $logoPath = null;
        try {
            // Use a placeholder image or create a simple dummy file
            $logoContent  = file_get_contents(public_path('images/placeholder.png') ?? 'https://via.placeholder.com/150x150.png?text=Logo');
            $logoFileName = 'logo_' . $this->faker->unique()->randomNumber(5) . '.png';
            $logoPath     = 'logos/' . $logoFileName;
            Storage::disk('public')->put($logoPath, $logoContent);
        } catch (\Exception $e) {
            // Fallback if file creation fails
            $logoPath = null;
        }

        $country = Country::first();
        $city    = City::where('country_id', $country->id ?? 0)->first();

        return [
            'logo'                      => $logoPath,
            'name'                      => $this->faker->unique()->company,
            'registration_name'         => $this->faker->unique()->company . ' Inc.',
            'registration_no'           => $this->faker->unique()->numerify('RNG-########'),
            'founded_at'                => $this->faker->dateTimeBetween('-30 years', 'now')->format('Y-m-d'),

            // Phone numbers
            'phone_dial_code'           => '+1',
            'phone_no'                  => $this->faker->numerify('555-####'),
            'secondary_phone_dial_code' => '+44',
            'secondary_phone_no'        => $this->faker->numerify('77####-####'),

            // Emails
            'email'                     => $this->faker->unique()->companyEmail,
            'secondary_email'           => $this->faker->unique()->safeEmail,

            // Relationships (Assuming Country and City seeders run first)
            'country_id'                => $country->id ?? 1,
            'city_id'                   => $city->id ?? 1,

            'address'                   => $this->faker->streetAddress . ', ' . $this->faker->city,

            // Audit columns
            'created_by'                => $createdBy->id,
            'updated_by'                => $createdBy->id,
        ];
    }
}
