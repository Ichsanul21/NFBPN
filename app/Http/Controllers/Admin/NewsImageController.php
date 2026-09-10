<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\ImageService;
use Illuminate\Http\Request;

class NewsImageController extends Controller
{
    public function upload(Request $request, ImageService $images)
    {
        $this->authorize('create', News::class);

        $request->validate([
            'upload' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $stored = $images->storePhoto($request->file('upload'), 'berita/inline', 1200);

        return response()->json(['url' => asset('storage/'.$stored['path'])]);
    }
}
