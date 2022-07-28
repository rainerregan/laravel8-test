<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Menampilkan halaman index
        return view('posts.index', ['posts' => BlogPost::orderBy('created_at', 'desc')->take(5)->get()]); // Menampilkan semua data
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Menampilkan form create post
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        // Dumping data
        // dd($request);

        // Validation Menggunakan Custom Request Class
        $validated = $request->validated();

        // Instantiate Model
        // $new_post = new BlogPost();

        // Assign Values
        // $new_post->title = $validated['title'];
        // $new_post->content = $validated['content'];
        // $new_post->save();

        // Cara Lain Untuk buat Model
        $post = BlogPost::create($validated);

        // Flash message: Menampilkan message untuk 1 kali dengan menggunakan sessions
        session()->flash('status', 'The blog post was created!');

        // Redirect ke halaman lain
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Menampilkan halaman show
        // abort_if(!isset($this->posts[$id]), 404);
        return view('posts.show', ['post' => BlogPost::findOrFail($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('posts.edit', ['post' => BlogPost::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        // Get the post, check if exists
        $post = BlogPost::findOrFail($id);

        // Validating
        $validated = $request->validated();

        // Fill object with validated
        $post->fill($validated);

        // Save
        $post->save();

        // tell user from flash message
        session()->flash('status', 'Blog post was updated');

        return redirect()->route('posts.show', ['post' => $post->id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Dump data, making sure data is correct
        // dd($id);

        $post = BlogPost::findOrFail($id);
        $post->delete(); // Delete data

        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
