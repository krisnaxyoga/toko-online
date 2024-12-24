<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Product_image;
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

        return redirect()->route('category.index')->with('success', 'Product has been created successfully');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}