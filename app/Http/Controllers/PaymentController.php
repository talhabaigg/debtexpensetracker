<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'debt_id' => 'required|exists:debts,id',
            'payment_amount' => 'required|numeric',
            'paid_at' => 'required|date',
            'balance' => 'required|numeric',
        ]);

       // Add the fixed 'type' value
        $validatedData['type'] = 'Repayment';

        // Fetch the debt
        $debt = Debt::findOrFail($request->debt_id);

        // Calculate the new remaining balance
        $newRemainingBalance = $debt->remaining_balance - $validatedData['payment_amount'];

        // Update the debt with the new remaining balance
        $debt->remaining_balance = $newRemainingBalance;
        $debt->save();

        // Create the payment with the validated data
        $payment = Payment::create($validatedData); 

        return redirect()->route('debts.show', $request->debt_id)->with('success', 'Payment added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
