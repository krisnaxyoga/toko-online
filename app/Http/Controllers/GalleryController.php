<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Gallery::latest()->get();

        return view('admin.gallery.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $model = new Gallery();
        return view('admin.gallery.create',compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('images/gallery'), $filename);

        $gallery = new Gallery();
        $gallery->image = '/images/gallery/' . $filename;
        $gallery->title = $request->title;
        $gallery->save();

        return redirect()->route('gallery.index')->with('success', 'Gallery created successfully.');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = Gallery::findOrFail($id);

        return view('admin.gallery.create', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $gallery = Gallery::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('images/gallery'), $filename);

            $gallery->image = '/images/gallery/' . $filename;
        }
        $gallery->title = $request->title;
        $gallery->save();

        return redirect()->route('gallery.index')->with('success', 'Gallery updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        if (File::exists(public_path($gallery->image))) {
            File::delete(public_path($gallery->image));
        }
        $gallery->delete();

        return redirect()->route('gallery.index')->with('success', 'Gallery deleted successfully.');
    }
}