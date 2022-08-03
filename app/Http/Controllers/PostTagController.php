<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class PostTagController extends Controller
{
    public function index($tag)
    {
        // Search for tag dengan tag yang dipass
        $tag = Tag::findOrFail($tag);

        return view('posts.index', [
            // Mendapatkan list posts yang berhubungan
            'posts' => $tag->blogPosts()
                ->latest()
                ->withCount('comments')
                ->with('user')
                ->with('tags')
                ->get()
        ]);
    }
}
