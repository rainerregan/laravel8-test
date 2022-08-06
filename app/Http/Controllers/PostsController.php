<?php

namespace App\Http\Controllers;

use App\Contracts\CounterContract;
use App\Events\BlogPostPosted;
use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    private $counter;

    public function __construct(CounterContract $counter)
    {
        // Melindungi pages yang dimasukkan dibawah untuk tidak dapat diakses dengan user yang tidak ter login.
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);

        // Dependecy injection
        $this->counter = $counter;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @see AppServiceProvider
     * @see ActivityComposer
     * @see BlogPost @scopeLatestWithRelations
     */
    public function index()
    {

        // Menampilkan halaman index
        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest() // Menggunakan local query method untuk mengambil latest. Function dipanggil dari nama belakang method scope. contoh: scopeLatest -> latest().
                    ->latestWithRelations()
                    ->get(),
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
        // Validation Menggunakan Custom Request Class
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id; // define user ID

        // Cara Lain Untuk buat Model
        $post = BlogPost::create($validated);

        // Handle Upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');
            $post->image()->save(
                Image::make(['path' => $path])
            );
        }

        // create event
        event(new BlogPostPosted($post));

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
        // Caching: menggunakan dynamic key
        // Cache ini akan dihapus ketika post di update
        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function () use ($id) {
            return BlogPost::with(['comments', 'tags', 'user', 'comments.user'])
                ->findOrFail($id);
        });

        // $counter = resolve(Counter::class);

        // Menampilkan halaman show
        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $this->counter->increment("blog-post-{$id}", ['blog-post'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Verify apakah user dapat mengedit data posting
        // User yang bukan owner dari postingan tidak dapat edit
        // if(Gate::denies('update-post', $post)){
        //     // Abort akan redirect ke error page
        //     // 403 adalah code untuk unauthorized
        //     abort(403, "You can't edit this blog post");
        // }

        // Cara lain gunakan Gate adalah dengan menggunakan authorize
        // Authorize hanya mengizinkan orang dengan id yang sama dengan owner postingan untuk melakukan edit
        // $this->authorize('posts.update', $post);

        $post = BlogPost::findOrFail($id); // Mendapatkan data blogpost

        /**
         * Ketika kita sudah menggunakan policies mapping pada AuthServiceProvider, kita dapat menggunakan nama langsung dan tidak panjang
         * @see AuthServiceProvider
         * @see BlogPostPolicy
         */
        $this->authorize('update', $post);
        #$this->authorize($post); // Cara Lain

        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     * @see AuthServiceProvider
     * @see BlogPostPolicy
     */
    public function update(StorePost $request, $id)
    {
        // Verify apakah user dapat mengedit data posting
        // if(Gate::denies('update-post', $post)){
        //     // Abort akan redirect ke error page
        //     // 403 adalah code untuk unauthorized
        //     abort(403, "You can't edit this blog post");
        // }

        // Cara lain gunakan Gate adalah dengan menggunakan authorize
        // Authorize hanya mengizinkan orang dengan id yang sama dengan owner postingan untuk melakukan edit
        // $this->authorize('posts.update', $post);

        $post = BlogPost::findOrFail($id); // Get the post, check if exists

        /**
         * Ketika kita sudah menggunakan policies mapping pada AuthServiceProvider, kita dapat menggunakan nama langsung dan tidak panjang
         * @see AuthServiceProvider
         * @see BlogPostPolicy
         */
        $this->authorize('update', $post);
        # $this->authorize($post); // Cara Lain

        $validated = $request->validated(); // Validating form
        $post->fill($validated); // Fill object with validated value

        // Handle Upload
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails');


            if($post->image){
                Storage::delete($post->image->path); // Jika sudah ada Image, Delete
                $post->image->path = $path; // Set new path untuk post yang diedit
                $post->image->save(); // Save Image dan changes
            } else {
                // Save path ke dalam model Image didalam post
                $post->image()->save(
                    Image::make(['path' => $path]) // Make image
                );
            }

        }

        $post->save(); // Save post
        session()->flash('status', 'Blog post was updated'); // tell user from flash message
        return redirect()->route('posts.show', ['post' => $post->id]); // Redirect
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

        // get blog post data
        $post = BlogPost::findOrFail($id);

        // Check auth with gate
        // Menolak user dengan id yang tidak sama untuk mendelete post
        // if(Gate::denies('delete-post', $post)){
        //     abort(403, "You can't delete this blog post!");
        // }

        // Cara lain gunakan Gate adalah dengan menggunakan authorize
        // Authorize hanya mengizinkan orang dengan id yang sama dengan owner postingan untuk melakukan delete
        // $this->authorize('posts.delete', $post);

        /**
         * Ketika kita sudah menggunakan policies mapping pada AuthServiceProvider, kita dapat menggunakan nama langsung dan tidak panjang
         * @see AuthServiceProvider
         * @see BlogPostPolicy
         */
        $this->authorize('delete', $post);
        # $this->authorize($post); // Cara Lain

        $post->delete(); // Delete data

        session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
