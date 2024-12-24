<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_variant;
use App\Models\Category;
use App\Models\Product_image;
use Illuminate\Http\Request;

class ProductVariant extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $product = Product::find($id);
        $productvariants = Product_variant::where('product_id',$id)->get();
        $images_primary = Product_image::where('product_id',$id)->where('is_primary',1)->first();
        return view('admin.product_variant.index',compact('productvariants','product','images_primary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $model = new Product_variant();
        return view('admin.product_variant.form',compact('model','id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        // Remove dd() unless needed for debugging

        Product_variant::create([
            'product_id' => $request->product_id,
            'name' => $request->name,
            'price_adjustment' => $request->price_adjustment,
            'stock' => $request->stock,
        ]);

        return redirect()->route('productvariant.creatus.index',$request->product_id)->with('success','Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = Product_variant::find($id);
        return view('admin.product_variant.form',compact('model','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $product = Product_variant::find($id);

        $product->update([
            'name' => $request->name,
            'price_adjustment' => $request->price_adjustment,
            'stock' => $request->stock,
        ]);

        return redirect()->route('productvariant.creatus.index',$product->product_id)->with('success','Data has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product_variant::find($id);
        $product->delete();
        return redirect()->back()->with('success','Data has been deleted successfully');
    }
}