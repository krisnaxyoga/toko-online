<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Slider;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product_image;
use App\Models\Store_setting;

class FrontEndController extends Controller
{
    public function index(){
        $this->checkStoreSetting();
        // dd(session('cart', []));
        // session()->flush(); // Uncomment this line to clear session
        $sliders = Slider::all();
        $products = Product::with('images','wishlist')->orderBy('created_at', 'DESC')->get();
        return view('front.index',compact('products','sliders'));
    }

    protected function checkStoreSetting(){
        $setting = Store_setting::first();
        if(session('store_setting.id') != $setting->id){
            session(['store_setting' => $setting]);
        }

    }

    public function productDetail($id){
        $product = Product::with('images','variants','reviews')->find($id);
        return view('front.detailproduct',compact('product'));
    }

    public function about(){
        $page = Page::first();
        return view('front.about',compact('page'));
    }

    public function categoryproduct(Request $request){

        $categories = Category::all();
        $products = Product::with('images','wishlist');

        if($request->query('category')){
            $products = $products->where('category_id',$request->query('category'));
        }

        if($request->query('price_range')){
            $price_range = explode('-',$request->query('price_range'));
            $products = $products->whereBetween('price',[$price_range[0],$price_range[1]]);
        }

        $products = $products->orderBy('created_at', 'DESC')->get();
        return view('front.category',compact('products','categories'));
    }

    public function contact(){
        return view('front.contact');
    }

    public function gallery(){
        $products_images = Gallery::all();
        return view('front.gallery',compact('products_images'));
    }

    public function product(){
        $product = Product::with('images','variants')->orderBy('created_at', 'DESC')->paginate(8);
        return view('front.shop',compact('product'));
    }
}