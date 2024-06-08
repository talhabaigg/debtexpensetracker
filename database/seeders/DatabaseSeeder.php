<?php

namespace Database\Seeders;

use App\Models\Debt;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(1)->create();

        // Debt::factory(10)->create()->each(function ($debt) {
        //     $paymentSum = 0;
        //     // Create a random number of payments for each debt
        //     Payment::factory(rand(1, 5))->create([
        //         'debt_id' => $debt->id
        //     ])->each(function ($payment) use ($debt, &$paymentSum) {
        //         $paymentSum += $payment->amount;
        //     });

        //     // Calculate and update the debt's remaining balance
        //     $debt->update([
        //         'balance' => $debt->debt_amount - $paymentSum,
        //     ]);
        // Payment::factory(10)->create();
        Debt::factory()->count(10)->create();

    }
}
