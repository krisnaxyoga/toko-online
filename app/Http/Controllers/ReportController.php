<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function report()
    {
        $orders = Order::with('order_item', 'payment', 'user_address')->where('status', ['Diterima','Pengiriman','Pembayaran Diterima'])->get();
        return view('admin.report.order', compact('orders'));
    }

    public function pesanan()
    {
        $orders = Order::with('order_item', 'payment', 'user_address')->get();
        return view('admin.report.pesanan', compact('orders'));
    }

    public function detail($id)
    {
        $order = Order::with('order_item', 'payment', 'user_address')->find($id);
        return view('admin.report.detail', compact('order'));
    }

    public function updateorder(Request $request, $id)
    {
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipping_tracking_number = $request->resi;
        $order->save();
        return redirect()->back()->with('success', 'Order has been updated successfully');
    }

    public function paymentstatus(Request $request, $id)
    {
        $order = Order::find($id);
        $order->status = $request->status;
        $order->save();

        $payments = Payment::where('order_id', $order->id)->first();
        $payments->status = $request->status;
        $payments->save();

        return redirect()->back()->with('success', 'Order has been approved successfully');
    }
    public function approve(Request $request)
    {
        $order = Order::find($request->id);
        $order->status = 'Pembayaran Diterima';
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

    public function reportpayment(){
        $data = Payment::with('order')->latest()->get();
        return view('admin.payment.index', compact('data'));
    }

    public function detailpayment($id){
        $data = Order::find($id);
        return view('admin.payment.detail', compact('data'));
    }
}