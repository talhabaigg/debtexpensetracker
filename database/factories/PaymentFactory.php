<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Debt;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        // Ensure there is a debt available, otherwise create one
        $debt = Debt::inRandomOrder()->first() ?? Debt::factory()->create();
        
        // Define payment amount to be within the debt amount range
        $paymentAmount = $this->faker->randomFloat(2, 10, $debt->debt_amount);
        return [
            'debt_id' => $debt->id,  // Associate payment with the debt
            'type'=> "Repayment",
            'payment_amount' => $paymentAmount,  // Payment amount
            'paid_at' => $this->faker->dateTimeBetween('-1 year', 'now'), 
            'balance' => $this->faker->randomFloat(2, 100, 10000), // Payment date
        ];
    }
}
