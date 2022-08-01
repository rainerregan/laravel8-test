<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostsController extends Controller
{

    public function __construct()
    {

        // Melindungi pages yang dimasukkan dibawah untuk tidak dapat diakses dengan user yang tidak ter login.
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Enable Query Logging
        // DB::enableQueryLog();

        // $posts = BlogPost::with('comments');

        // foreach($posts as $post){
        //     foreach($post->comments as $comment){
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());


        // Menampilkan halaman index
        return view(
            'posts.index',
            [
                'posts' => BlogPost::withCount('comments')->get()
            ]
        ); // Menampilkan semua data
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
        return view(
            'posts.show',
            [
                'post' => BlogPost::with('comments')->findOrFail($id)
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Mendapatkan data blogpost
        $post = BlogPost::findOrFail($id);

        // Verify apakah user dapat mengedit data posting
        if(Gate::denies('update-post', $post)){
            // Abort akan redirect ke error page
            // 403 adalah code untuk unauthorized
            abort(403, "You can't edit this blog post");
        }

        return view('posts.edit', ['post' => $post]);
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

        // Verify apakah user dapat mengedit data posting
        if(Gate::denies('update-post', $post)){
            // Abort akan redirect ke error page
            // 403 adalah code untuk unauthorized
            abort(403, "You can't edit this blog post");
        }

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
