<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $debts = Debt::with('payments')->get();
        // Log the debt and its payments for debugging
        
        // Return the Inertia.js page with the debts data
        return Inertia::render('Debt/Index', [
            'debts' => $debts
        ]);
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Debt/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Debt::create($request->validate([
            'debt_name' => 'required|string|max:255',
            'debt_amount' => 'required|numeric',
            'debt_supplier' => 'required|string|max:255',
            'remaining_balance' => 'required|numeric',
            'debt_start_date' => 'required|date',
            'expected_end_date' => 'required|date|after_or_equal:debt_start_date',
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $debt = Debt::with('payments')->findOrFail($id);
        return Inertia::render('Debt/Show', [
            'debt' => $debt
        ]);

        dd($debt->payments);
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
