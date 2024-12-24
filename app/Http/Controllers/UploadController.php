<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    // Fungsi untuk mengunggah gambar
    public function upload(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'images' => 'required|mimes:jpg,jpeg,png|max:2048', // Validasi jenis dan ukuran file
        ]);

        // Memeriksa jika file ada
        if ($request->hasFile('images')) {
            // Menentukan nama file unik
            $imageName = time() . '.' . $request->image->extension();
            // Menyimpan file ke folder public/images
            $request->image->move(public_path('images'), $imageName);

            // Mengembalikan respons JSON dengan URL file dan nama file
            return response()->json([
                'path' => url('images/' . $imageName),
                'fileName' => $imageName,
            ], 200);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    // Fungsi untuk menghapus gambar
    public function revert(Request $request)
    {
        // Mendapatkan nama file dari request body
        $fileName = $request->getContent();

        // Validasi nama file untuk mencegah file berbahaya
        if (!preg_match('/^[a-zA-Z0-9_\-]+\.(jpg|jpeg|png)$/', $fileName)) {
            return response()->json(['error' => 'Invalid file name'], 400);
        }

        // Menentukan path file di folder public/images
        $fullPath = public_path('images/' . $fileName);

        // Memeriksa apakah file ada
        if (file_exists($fullPath)) {
            unlink($fullPath); // Menghapus file
            return response()->json(['message' => 'File deleted'], 200);
        }

        return response()->json(['error' => 'File not found'], 404); // Jika file tidak ditemukan
    }
}