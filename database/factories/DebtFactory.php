<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Debt>
 */
class DebtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'debt_name' => $this->faker->word,
            'debt_amount' => $this->faker->randomFloat(2, 500, 5000), // Random amount between 100 and 10,000
            'debt_supplier' => $this->faker->company,
            'debt_start_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('d-m-Y'),
            'expected_end_date' => $this->faker->dateTimeBetween('now', '+2 years')->format('d-m-Y'),
            'remaining_balance' => $this->faker->randomFloat(2, 100, 10000),
        
        ];
    }
}
