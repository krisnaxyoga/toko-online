<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereColumn('id', 'parent_id')->get();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $model = new Category();
        $category = null;
        return view('admin.category.form', compact('model','category'));
    }

    public function subscategory($id)
    {
        $model = new Category();
        $category = $id;
        return view('admin.category.form', compact('model','category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => str()->slug($request->name),
            'description' => $request->description,
        ]);

        if($request->parent_id){
            $category->parent_id = $request->parent_id;
        }else{
            $category->parent_id = $category->id;
        }

        $category->save();

        return redirect()->route('category.index')->with('success', 'Category has been created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categories = Category::findOrFail($id);
        $subcategories = Category::whereColumn('id', '!=', 'parent_id')->where('parent_id', $id)->get();
        return view('admin.category.show', compact('categories','subcategories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = Category::findOrFail($id);
        $category = null;
        return view('admin.category.form', compact('model','category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'slug' => str()->slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('category.index')->with('success', 'Category has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category has been deleted successfully');
    }
}