<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_name' => $this->faker->company(),
            'short_name' => $this->faker->companySuffix(),
            'business_address' => $this->faker->address(),
            'facility_address' => $this->faker->address(),
            'customer_category' => $this->faker->randomElement(['Residential', 'Commercial', 'Industrial']),
            'cooperation_period_start_date' => $this->faker->date(),
            'cooperation_period_end_date' => $this->faker->date(),
            'contract_price' => $this->faker->randomFloat(2, 1000, 10000),
            'contracted_demand' => $this->faker->randomFloat(2, 10, 100),
            'other_information' => $this->faker->paragraph(),
            'contact_name' => $this->faker->name(),
            'designation' => $this->faker->jobTitle(),
            'customer_id' => User::inRandomOrder()->first()?->id ?? null,
            'email' => $this->faker->unique()->safeEmail(),
            'mobile_number' => $this->faker->phoneNumber(),
            'contact_name_1' => $this->faker->name(),
            'designation_1' => $this->faker->jobTitle(),
            'mobile_number_1' => $this->faker->phoneNumber(),
            'email_1' => $this->faker->unique()->safeEmail(),
            'account_executive' => $this->faker->name(),
            'certificate_of_contestability_number' => strtoupper($this->faker->bothify('??##??##')),
        ];
    }
}
