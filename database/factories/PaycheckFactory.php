<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paycheck>
 */
class PaycheckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paycheck_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('d-m-Y'),
            'amount' => $this->faker->randomFloat(2, 500, 5000), // Random amount between 100 and 10,000
            'balance' => $this->faker->randomFloat(2, 100, 2000),
        ];
    }
}
