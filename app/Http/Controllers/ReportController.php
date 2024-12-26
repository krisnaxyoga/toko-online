<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function report()
    {
        $orders = Order::with('order_item', 'payment', 'user_address')->get();
        return view('admin.report.order', compact('orders'));
    }


    public function approve(Request $request)
    {
        $order = Order::find($request->id);
        $order->status = 'Diterima';
        $order->save();
        return redirect()->back()->with('success', 'Order has been approved successfully');
    }

    public function cancel(Request $request)
    {
        $order = Order::find($request->id);
        $order->status = 'Dibatalkan';
        $order->save();
        return redirect()->back()->with('success', 'Order has been canceled successfully');
    }
}