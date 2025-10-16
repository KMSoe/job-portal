<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Recruitment\Entities\Applicant;

class ApplicantFactory extends Factory
{
    protected $model = Applicant::class;
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'phone_dial_code'   => '+95',
            'phone_no'          => fake()->phoneNumber(),
            'job_title'         => fake()->jobTitle(),
            'expected_salary'   => fake()->numberBetween(1000, 20000),
        ];
    }
}
