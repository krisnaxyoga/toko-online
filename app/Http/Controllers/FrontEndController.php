<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function index(){
        // session()->flush(); // Uncomment this line to clear session
        $products = Product::with('images')->orderBy('created_at', 'DESC')->paginate(8);
        return view('front.index',compact('products'));
    }
}