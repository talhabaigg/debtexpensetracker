<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Paycheck;
use Illuminate\Http\Request;

class PaycheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paychecks = Paycheck::all();


        // Log the debt and its payments for debugging
        
        // Return the Inertia.js page with the debts data
        return Inertia::render('Paycheck/Index', [
            'paychecks' => $paychecks,
            // 'flash' => [
            //     'success' => 'Page loaded successfully'
            // ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Paycheck/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'paycheck.date' => 'required|date',
            'paycheck.amount' => 'required|numeric',
            'paycheck.expense' => 'required|numeric',
            'paycheck.balance' => 'required|numeric',
            'paycheckItems' => 'required|array',
            'paycheckItems.*.item_name' => 'required|string',
            'paycheckItems.*.item_amount' => 'required|numeric',
            'paycheckItems.*.item_type' => 'required|in:income,expense',
        ]);

        // Create a new Paycheck
        $paycheck = Paycheck::create($data['paycheck']);

        // Create PaycheckItems
        foreach ($data['paycheckItems'] as $item) {
            $paycheck->items()->create($item);
        }

        return redirect()->route('paychecks.index')->with('success', 'Paycheck created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paycheck = Paycheck::with('items')->findOrFail($id);
        return inertia('Paycheck/Edit', [
            'paycheck' => $paycheck,
            'paycheckItems' => $paycheck->items,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paycheck $paycheck)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $paycheck = Paycheck::findOrFail($id);

        $validated = $request->validate([
            'paycheck.date' => 'required|date',
            'paycheck.amount' => 'required|numeric',
            'paycheck.expense' => 'required|numeric',
            'paycheck.balance' => 'required|numeric',
            'paycheckItems' => 'required|array',
            'paycheckItems.*.item_name' => 'required|string',
            'paycheckItems.*.item_amount' => 'required|numeric',
            'paycheckItems.*.item_type' => 'required|string|in:income,expense',
        ]);

        // Update paycheck
        $paycheck->update($validated['paycheck']);

        // Delete existing items
        $paycheck->items()->delete();

        // Create new items
        foreach ($validated['paycheckItems'] as $item) {
            $paycheck->items()->create($item);
        }

        return redirect()->route('paychecks.index', $id)->with('success', 'Paycheck updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paycheck = Paycheck::findOrFail($id);
    // Delete the debt
    $paycheck->delete();

    // Return a response, typically redirecting back with a success message
    return redirect()->route('paychecks.index')->with('warning', 'Paycheck deleted successfully.');
    }
    
}
