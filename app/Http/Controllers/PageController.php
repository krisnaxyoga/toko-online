<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return view('page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $filename1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('images'), $filename1);
            $request->merge(['image1' => '/images/' . $filename1]);
        }

        if ($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $filename2 = time() . '_' . uniqid() . '.' . $image2->getClientOriginalExtension();
            $image2->move(public_path('images'), $filename2);
            $request->merge(['image2' => '/images/' . $filename2]);
        }

        Page::create([
            'title' => $request->title,
            'slug' => str()->slug($request->title),
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'image1' => '/images/page/' . $filename1,
            'image2' => '/images/page/' . $filename2,
        ]);

        return redirect()->route('page.index')->with('success', 'Page created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $filename1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('images'), $filename1);
            $request->merge(['image1' => '/images/' . $filename1]);

            // hapus gambar yang lama
            if (File::exists(public_path($page->image1))) {
                File::delete(public_path($page->image1));
            }
        }

        if ($request->hasFile('image2')) {
            $image2 = $request->file('image2');
            $filename2 = time() . '_' . uniqid() . '.' . $image2->getClientOriginalExtension();
            $image2->move(public_path('images'), $filename2);
            $request->merge(['image2' => '/images/' . $filename2]);

            // hapus gambar yang lama
            if (File::exists(public_path($page->image2))) {
                File::delete(public_path($page->image2));
            }
        }

        $page->update([
            'title' => $request->title,
            'slug' => str()->slug($request->title),
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'image1' => '/images/page/' . $filename1,
            'image2' => '/images/page/' . $filename2,
        ]);

        return redirect()->route('page.index')->with('success', 'Page updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // hapus gambar yang lama
        if (File::exists(public_path($page->image1))) {
            File::delete(public_path($page->image1));
        }

        if (File::exists(public_path($page->image2))) {
            File::delete(public_path($page->image2));
        }

        $page->delete();

        return redirect()->route('page.index')->with('success', 'Page deleted successfully');
    }
}