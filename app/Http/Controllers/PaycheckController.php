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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Paycheck $paycheck)
    {
        //
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
    public function update(Request $request, Paycheck $paycheck)
    {
        //
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
