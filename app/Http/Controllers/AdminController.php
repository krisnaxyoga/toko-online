<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $revenue = Order::where('status', ['Diterima','dalam pengiriman','Pembayaran Diterima'])->sum('grand_total');

        $penjualan = Order::where('status', ['Diterima','dalam pengiriman','Pembayaran Diterima'])->count();

        $customer = User::where('role_id', 2)->count();
        $orders = Order::with('order_item', 'payment', 'user_address')->latest()->get();
        return view('admin.dashboard',compact('revenue','penjualan','customer','orders'));
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
    public function resiadd(Request $request)
    {
        $request->validate([
            'resi' => 'nullable',
        ]);

        Order::where('id', $request->order_id)->update([
            'shipping_tracking_number' => $request->resi,
            'status' => 'Pengiriman'
        ]);

        return redirect()->back()->with('success', 'Resi has been added successfully');
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