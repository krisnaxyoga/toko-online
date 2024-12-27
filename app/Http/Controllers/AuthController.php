<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Cart_item;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function dologin(Request $request) {
        // validasi
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {

            // buat ulang session login
            $request->session()->regenerate();

             // Mengambil dan menyimpan data wishlist ke session
             $this->storeWishlistToSession();

             // Mengambil dan menyimpan data cart ke session
             $this->storeCartToSession();
            if (auth()->user()->role_id === 1) {
                // jika user superadmin
                return redirect()->intended('/admin');
            } else {
                // jika user pegawai
                return redirect()->intended('/customer');
            }
        }


        // jika email atau password salah
        // kirimkan session error
        return back()->with('error', 'email atau password salah');
    }

    private function storeWishlistToSession()
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->with('product')  // Load relasi product
            ->pluck('product_id')
            ->toArray();

        session()->put('wishlist', $wishlist);
    }

    private function storeCartToSession()
    {
        $cart_items = Cart_item::where('user_id', auth()->id())
            ->with(['product.images', 'product_variant'])  // Load relasi yang dibutuhkan
            ->get();

        $cart = [];

        foreach ($cart_items as $item) {
            $product = $item->product;
            $variant = $item->product_variant;

            // Pastikan product dan image exists
            if (!$product) continue;

            $primaryImage = $product->images->where('is_primary', 1)->first();

            $cart[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'image' => $primaryImage ? $primaryImage->image_url : null,
                'product_variant_id' => $item->product_variant_id,
                'name' => $variant ? $variant->product->name : $product->name,
                'variant' => $variant ? $variant->name : null,
                'quantity' => $item->quantity,
                'price' => $item->product->price ?? $item->product_variant->price_adjustment,
                'max_stock' => $variant ? $variant->stock : $product->stock,
                'total_price' => $item->quantity * $item->price ?? $item->product_variant->price_adjustment,
            ];
        }

        session()->put('cart', $cart);
    }
    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->forget('wishlist');
        $request->session()->forget('cart');
        $request->session()->regenerateToken();
        return redirect('/');
    }
}