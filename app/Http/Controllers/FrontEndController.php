<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function index(){
        // dd(session('cart', []));
        // session()->flush(); // Uncomment this line to clear session
        $products = Product::with('images','wishlist')->orderBy('created_at', 'DESC')->paginate(8);
        return view('front.index',compact('products'));
    }

    public function about(){
        return view('front.about');
    }

    public function contact(){
        return view('front.contact');
    }

    public function product(){
        $product = Product::with('images','variants')->orderBy('created_at', 'DESC')->paginate(8);
        return view('front.shop',compact('product'));
    }
}