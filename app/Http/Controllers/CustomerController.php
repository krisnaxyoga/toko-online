<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Mail\InvoiceMail;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\User_address;
use Illuminate\Http\Request;
use App\Models\Product_review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $bank = BankAccount::where('status', 'active')->get();
        $orders = Order::with('order_item', 'payment', 'user_address','review')->where('user_id', auth()->id())->get();
        // dd($orders);
        return view('customer.index', compact('orders','bank'));
    }

    public function customerdata(){
        $customers = User::where('role_id', 2)->get();
        return view('admin.customer.index', compact('customers'));
    }

    public function barangditerima($id){
        $order = Order::find($id);
        $order->status = 'Diterima';
        $order->save();
        return redirect()->back()->with('success', 'Barang telah diterima');
    }
    public function sendInvoiceEmail($id)
    {
        try {
            $order = Order::find($id);
            Mail::to($order->user->email)->send(new InvoiceMail($order));
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function review(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
        ]);

        $review = new Product_review();
        $review->user_id = auth()->id();
        $review->product_id = $request->product_id;
        $review->order_id = $request->order_id;
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->save();

        return redirect()->back()->with('success', 'Review has been submitted successfully.');
    }

    public function register(Request $request){
        return view('auth.register');
    }
    public function doregister(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'nullable|string',
            'origin_province' => 'required|string',
            'origin_city' => 'required|string',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User ();
        $user->name  = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = 2;
        $user->save();

        $useraddress = new User_address();
        $useraddress->user_id = $user->id;
        $useraddress->province_id = $request->origin_province;
        $useraddress->city_id = $request->origin_city;
        $useraddress->address = $request->address;
        $useraddress->postal_code = $request->postal_code;
        $useraddress->is_primary = true;
        $useraddress->save();


        return redirect()->route('login')->with('success', 'Registration successful. Please login');
    }
    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */

     public function myorder()
    {
        $orders = Order::with('order_item', 'payment', 'user_address')->where('user_id', auth()->id())->latest()->get();
        return view('customer.myorder.index', compact('orders'));
    }
    public function showorder(string $id)
    {
        $order = Order::with('order_item', 'payment', 'user_address')->find($id);
        return view('customer.myorder.detail', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function mypayment()
    {
        $data = Payment::whereHas('order', function($query){
            $query->where('user_id', auth()->id());
        })->get();
        return view('customer.mypayment.index', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function mypaymentdetail($id)
    {
        $data = Order::find($id);
        return view('customer.mypayment.detail', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function myreview()
    {
        $data = Order::where('user_id', auth()->id())->with('review')->get();
        return view('customer.review.index', compact('data'));
    }
    public function createmyreview($id)
    {
        $data = Order::find($id);
        return view('customer.review.form', compact('data'));
    }

    public function profile(){
        $user = User::find(auth()->id());
        return view('customer.profile.index', compact('user'));
    }
}