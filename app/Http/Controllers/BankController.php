<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bank_accounts = BankAccount::all();
        return view('bank.index', compact('bank_accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bank.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
        ]);

        BankAccount::create([
            'name' => $request->name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
        ]);

        return redirect()->route('bank.index')->with('success', 'Bank account created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bank_account = BankAccount::findOrFail($id);
        return view('bank.show', compact('bank_account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bank_account = BankAccount::findOrFail($id);
        return view('bank.edit', compact('bank_account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
        ]);

        $bank_account = BankAccount::findOrFail($id);
        $bank_account->update([
            'name' => $request->name,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
        ]);

        return redirect()->route('bank.index')->with('success', 'Bank account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank_account = BankAccount::findOrFail($id);
        $bank_account->delete();

        return redirect()->route('bank.index')->with('success', 'Bank account deleted successfully');
    }
}