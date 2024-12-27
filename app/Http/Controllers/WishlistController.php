<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->get();
        return view('front.wishlist', compact('wishlist'));
    }

    public function destroy($id)
    {
        Wishlist::where('id', $id)->delete();
        return redirect()->back();
    }

    public function store($id)
    {
        $exists = Wishlist::where('user_id', auth()->id())->where('product_id', $id)->exists();
        if (!$exists) {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $id
            ]);

            $wishlist = Wishlist::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
            session()->put('wishlist', $wishlist);
        }else{
            Wishlist::where('user_id', auth()->id())->where('product_id', $id)->delete();

            $wishlist = session('wishlist');
            $key = array_search($id, $wishlist);
            if ($key !== false) {
                unset($wishlist[$key]);
            }
            session()->put('wishlist', $wishlist);
        }
        return redirect()->back();
    }
}