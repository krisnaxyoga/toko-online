<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Product_image;
use App\Models\Product_variant;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderByDesc('id')->get();
        return view('admin.product.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $model = new Product();
        $categories = Category::whereColumn('id', 'parent_id')->get();
        $image_primary = null;
        return view('admin.product.form',compact('model','categories','image_primary'));
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

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => str()->slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'stock' => $request->stock,
            'status' => $request->status
        ]);

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);

                Product_image::create([
                    'product_id' => $product->id,
                    'image_url' => '/images/' . $imageName,
                    'is_primary' => false
                ]);
            }
        }

        if($request->hasFile('images_primary')) {
            $image = $request->file('images_primary');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);
            Product_image::create([
                'product_id' => $product->id,
                'image_url' => "/images/" . $filename,
                'is_primary' => true
            ]);
        }

        return redirect()->route('product.index')->with('success', 'Product has been created successfully');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = Product::find($id);
        $categories = Category::whereColumn('id', 'parent_id')->get();
        $image_primary = Product_image::where('product_id', $id)->where('is_primary', true)->first();
        return view('admin.product.form',compact('model','categories','image_primary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $product = Product::find($id);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => str()->slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'stock' => $request->stock,
            'status' => $request->status
        ]);

        // Handle multiple images
        if ($request->hasFile('images')) {
            // Delete old images
            $oldImages = Product_image::where('product_id', $product->id)->where('is_primary', false)->get();
            foreach ($oldImages as $oldImage) {
                if (File::exists(public_path($oldImage->image_url))) {
                    File::delete(public_path($oldImage->image_url));
                }
                $oldImage->delete();
            }

            // Upload new images
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);

                Product_image::create([
                    'product_id' => $product->id,
                    'image_url' => '/images/' . $imageName,
                    'is_primary' => false
                ]);
            }
        }

        if ($request->hasFile('images_primary')) {
            // Delete old primary image
            $oldPrimaryImage = Product_image::where('product_id', $id)->where('is_primary', true)->first();
            if ($oldPrimaryImage && File::exists(public_path($oldPrimaryImage->image_url))) {
                File::delete(public_path($oldPrimaryImage->image_url));
            }

            $image = $request->file('images_primary');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $filename);
            Product_image::where('product_id', $id)->where('is_primary', true)->update(['image_url' => "/images/" . $filename]);
        }

        return redirect()->route('product.index')->with('success', 'Product has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        // Delete product images
        $productImages = Product_image::where('product_id', $id)->get();
        foreach ($productImages as $productImage) {
            if (File::exists(public_path($productImage->image_url))) {
                File::delete(public_path($productImage->image_url));
            }
            $productImage->delete();
        }

        // Delete product variant
        $productVariants = Product_variant::where('product_id', $id)->get();
        foreach ($productVariants as $productVariant) {
            $productVariant->delete();
        }

        // Delete product
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product has been Deleted successfully');
    }
}