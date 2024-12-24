<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function index(){
        $products = Product::orderBy('created_at', 'DESC')->paginate(8);
        return view('front.index',compact('products'));
    }
}