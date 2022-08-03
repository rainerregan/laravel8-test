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
            'posts' => $tag->blogPosts, // Mendapatkan list posts yang berhubungan
            'mostCommented' => [],
            'mostActive' => [],
            'mostActiveLastMonth' => []
        ]);
    }
}
