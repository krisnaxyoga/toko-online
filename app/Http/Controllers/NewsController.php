<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Page::where('slug', 'news')->orderBy('created_at', 'desc')->get();
        return view('admin.news.index', compact('data'));
    }

    public function frontpage()
    {
        $data = Page::where('slug', 'news')->orderBy('created_at', 'desc')->get();
        return view('front.news', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $model = new Page();
        return view('admin.news.form',compact('model'));
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

        $image1 = null;
        $image2 = null;
        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $filename1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('images/page'), $filename1);
        }

        $image1 = '/images/page/' . $filename1;

        Page::create([
            'title' => $request->title,
            'slug' => 'news',
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'image1' => $image1,
            'image2' => $image2,
        ]);


        return redirect()->route('news.index')->with('success', 'Page created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = Page::find($id);
        return view('front.page', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = Page::find($id);
        return view('admin.news.form', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $page = Page::findOrFail($id);
        $page->title = $request->title;
        $page->content = $request->content;
        $page->meta_title = $request->meta_title;
        $page->meta_description = $request->meta_description;

        if ($request->hasFile('image1')) {
            // hapus gambar yang lama
            if (File::exists(public_path($page->image1))) {
                File::delete(public_path($page->image1));
            }

            $image1 = $request->file('image1');
            $filename1 = time() . '_' . uniqid() . '.' . $image1->getClientOriginalExtension();
            $image1->move(public_path('images/page'), $filename1);
            $page->image1 = '/images/page/' . $filename1;
        }

        $page->save();

        return redirect()->route('news.index')->with('success', 'Page updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::find($id);
         // hapus gambar yang lama
         if (File::exists(public_path($page->image1))) {
            File::delete(public_path($page->image1));
        }

        if (File::exists(public_path($page->image2))) {
            File::delete(public_path($page->image2));
        }

        $page->delete();

        return redirect()->route('news.index')->with('success', 'Page deleted successfully');
    }
}
