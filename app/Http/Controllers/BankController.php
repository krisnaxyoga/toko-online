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
        return view('admin.bank.index', compact('bank_accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $model = new BankAccount();
        return view('admin.bank.form',compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
        ]);

        BankAccount::create([
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'status' => $request->status,
        ]);

        return redirect()->route('bank-account.index')->with('success', 'Bank account created successfully');
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
        $model = BankAccount::findOrFail($id);
        return view('admin.bank.form', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
        ]);

        $bank_account = BankAccount::findOrFail($id);
        $bank_account->update([
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'bank_name' => $request->bank_name,
            'status' => $request->status,
        ]);

        return redirect()->route('bank-account.index')->with('success', 'Bank account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bank_account = BankAccount::findOrFail($id);
        $bank_account->delete();

        return redirect()->route('bank-account.index')->with('success', 'Bank account deleted successfully');
    }
}