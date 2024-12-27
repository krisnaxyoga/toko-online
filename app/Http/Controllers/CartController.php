<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Cart_item;
use App\Models\Order_item;
use Illuminate\Http\Request;
use App\Models\Store_setting;
use App\Models\Product_variant;

class CartController extends Controller
{

    public function index() {
        $cart = Cart_item::where('user_id', auth()->id())->with('product', 'product_variant')->get();
        $weight = 0;
        foreach ($cart as $item) {
            $product = $item->product;
            $weight += $product->weight * $item->quantity;
        }

        $store = Store_setting::first();
        $customer = User::where('id', auth()->id())->with('user_address')->first();
        return view('front.cart', compact('cart','weight','store','customer'));
    }
    public function cart(Request $request) {

        $product_id = $request->product_id;
        $variant_id = $request->variant;
        $quantity = $request->quantity;

        $product = Product::find($product_id);
        $variant = Product_variant::find($variant_id);

        if($variant_id){
            $price = $variant->price_adjustment;
        }else{
            $price = $product->price;
        }

        $total_price = $price * $quantity;

        // Cari apakah ada cart item yang sama
        $cartdb = Cart_item::where('user_id', auth()->id())
            ->where('product_id', $product_id)
            ->where('product_variant_id', $variant_id)
            ->first();

        if ($cartdb) {
            // Jika ada, tambahkan quantity
            $cartdb->quantity += $quantity;
            $cartdb->save();
        } else {
            // Jika belum ada, buat baru
            $cartdb = Cart_item::create([
                'user_id' => auth()->id(),
                'product_id' => $product_id,
                'product_variant_id' => $variant_id,
                'quantity' => $quantity,
            ]);
        }

        // Store cart item in session
        $cart = session()->get('cart', []);
        $found = false;

        foreach ($cart as &$item) {
            if ($item['id'] === $cartdb->id) {
                $item['quantity'] += $quantity;
                $item['total_price'] = $item['price'] * $item['quantity'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart_item = [
                'id' => $cartdb->id,
                'product_id' => $product_id,
                'image' => $product->images->where('is_primary', 1)->first()->image_url,
                'product_variant_id' => $variant_id,
                'name' => $product->name ?? $variant->product->name,
                'variant' => $variant->name ?? null,
                'quantity' => $cartdb->quantity,
                'price' => $price,
                'max_stock' => $variant->stock ?? $product->stock,
                'total_price' => $total_price * $cartdb->quantity,
            ];
            $cart[] = $cart_item;
        }

        session()->put('cart', $cart);

        // return redirect()->route('cart.index')->with('success', 'Product has been added to your cart.');
        return redirect()->back()->with('success', 'Product has been added to your cart.');

    }


    public function update(Request $request, $id)
    {
        try {
            // Ambil data cart dari session
            $cart = session()->get('cart', []);

            // Loop untuk mencari item di cart berdasarkan ID
            foreach ($cart as $index => $item) {
                if ($item['id'] == $id) {
                    // Update quantity
                    if ($request->quantity == 0) {
                        // Hapus session
                        unset($cart[$index]);
                        session()->put('cart', $cart);
                        // Hapus dari database
                        $cartItem = Cart_item::find($id);
                        if ($cartItem) {
                            $cartItem->delete();
                        }
                        return response()->json([
                            'success' => true,
                            'message' => 'Item has been removed from cart.'
                        ]);
                    } else {
                        $cart[$index]['quantity'] = $request->quantity;
                        // Recalculate total price using stored price
                        $cart[$index]['total_price'] = $cart[$index]['price'] * $request->quantity;

                        // Update session
                        session()->put('cart', $cart);

                        // Update database
                        $cartItem = Cart_item::find($id);
                        if ($cartItem) {
                            $cartItem->update([
                                'quantity' => $request->quantity
                            ]);
                        }

                        return response()->json([
                            'success' => true,
                            'message' => 'Cart has been updated.',
                            'cart' => $cart
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the cart.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id) {
        $cart = session()->get('cart');
        foreach ($cart as $index => $item) {
            if ($item['id'] == $id) {
                unset($cart[$index]);
            }
        }
        session()->put('cart', $cart);

        Cart_item::find($id)
            ->delete();

        return redirect()->route('cart.index')->with('success', 'Product has been added to your cart.');

    }

    public function checkout(Request $request) {

        $request->validate([
            'total_price' => 'required',
        ]);

        $order = new Order();
        $order->user_id = auth()->id();
        $order->invoice_number = 'INV-'.time();
        $order->status = 'Belum Dibayar';
        $order->total_price = $request->total_price;
        $order->shipping_cost = $request->shipping_cost;
        $order->grand_total = $request->grand_total;
        $order->shipping_address_id = $request->shipping_address_id;
        $order->shipping_courier = $request->courier;
        $order->notes = $request->notes;
        $order->save();

        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $orderItem = new Order_item();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['product_id'];
            $orderItem->product_variant_id = $item['product_variant_id'] ?? null;
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            $orderItem->subtotal = $item['total_price'];
            $orderItem->save();

            Cart_item::find($item['id'])->delete();
        }

        session()->forget('cart');

       return redirect()->route('checkout-success')->with('success', 'Order has been created successfully.');
    }

    public function checkoutSuccess() {
        $order = Order::where('user_id', auth()->id())->latest()->first();
        $order_items = Order_item::where('order_id', $order->id)->with('product', 'product_variant')->get();
        return view('front.checkout-success', compact('order', 'order_items'));
    }

    public function uploadBukti(Request $request) {
        $request->validate([
            'order_id' => 'required',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $order = Order::find($request->order_id);
        $image = $request->file('bukti_transfer');
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/bukti'), $filename);

        $imageName = '/images/bukti/' . $filename;

        $order->payment_proof = $imageName;
        $order->status = 'Menunggu Konfirmasi';
        $order->save();

        $payments = new Payment();
        $payments->order_id = $order->id;
        $payments->image = $imageName;
        $payments->save();


        return redirect()->back()->with('success', 'Bukti transfer has been uploaded successfully.');
    }
}