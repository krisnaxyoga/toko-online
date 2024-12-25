<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_variant;
use App\Models\Cart_item;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index() {
        $cart = session()->get('cart');
        return view('front.cart', compact('cart'));
    }
    public function cart(Request $request) {
        $product_id = $request->product_id;
        $variant_id = $request->variant;
        $quantity = $request->quantity;

        $product = Product::findOrFail($product_id);
        $variant = Product_variant::findOrFail($variant_id);

        $price = $product->price + $variant->price_adjustment;
        $total_price = $price * $quantity;

        // Save cart item to database
        $cartdb = Cart_item::create([
            'user_id' => auth()->id(),
            'product_id' => $product_id,
            'product_variant_id' => $variant_id,
            'quantity' => $quantity,
        ]);

        // Store cart item in session
        $cart = session()->get('cart', []);
        $cart_item = [
            'id' => $cartdb->id,
            'product_id' => $product_id,
            'image' => $product->images->where('is_primary', 1)->first()->image_url,
            'product_variant_id' => $variant_id,
            'name' => $product->name,
            'variant' => $variant->name,
            'quantity' => $quantity,
            'price' => $price,
            'total_price' => $total_price,
        ];
        $cart[] = $cart_item;
        session()->put('cart', $cart);


        return redirect()->route('cart.index')->with('success', 'Product has been added to your cart.');
    }

    public function destroy($id) {
        $cart = session()->get('cart');
        foreach ($cart as $index => $item) {
            if ($item['id'] == $id) {
                unset($cart[$index]);
            }
        }
        session()->put('cart', $cart);

        Cart_item::find('id', $id)
            ->delete();

        return back()->with('success', 'Product has been deleted from your cart.');

    }

}