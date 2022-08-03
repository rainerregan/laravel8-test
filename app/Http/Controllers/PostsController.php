<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

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
        // Caching
        // Fungsi dibawah adalah untuk mendapatkan data dari cache dalam waktu tertentu
        // Jika tidak ada, maka kita akan membuat data tersebut di cache
        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', now()->addSeconds(10), function(){
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('users-most-active', now()->addSeconds(10), function(){
            return User::withMostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(10), function(){
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        // Menampilkan halaman index
        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest() // Menggunakan local query method untuk mengambil latest. Function dipanggil dari nama belakang method scope. contoh: scopeLatest -> latest().
                    ->withCount('comments')
                    ->with('user')
                    ->get(),
                'mostCommented' => $mostCommented, // Menggunakan Caching
                'mostActive' => $mostActive,
                'mostActiveLastMonth' => $mostActiveLastMonth
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

        $validated['user_id'] = $request->user()->id; // define user ID

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

        // Caching: menggunakan dynamic key
        // Cache ini akan dihapus ketika post di update
        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function() use($id){
            return BlogPost::with('comments')->findOrFail($id);
        });

        // Cache for Counter
        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        // Get data $userKey di cache dan dengan defaultvalue adalah [] empty
        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach($users as $session => $lastVisit)
        {
            if($now->diffInMinutes($lastVisit) >= 1){
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if(!array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1){

            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);

        // Mengecek counter apakah ada di cache
        if(!Cache::tags(['blog-post'])->has($counterKey)){
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $diffrence);
        }

        // Mengambil data counter dari cache
        $counter = Cache::tags(['blog-post'])->get($counterKey);

        // Menampilkan halaman show
        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter
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
        // Mendapatkan data blogpost
        $post = BlogPost::findOrFail($id);

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
     */
    public function update(StorePost $request, $id)
    {

        // Get the post, check if exists
        $post = BlogPost::findOrFail($id);

        // Verify apakah user dapat mengedit data posting
        // if(Gate::denies('update-post', $post)){
        //     // Abort akan redirect ke error page
        //     // 403 adalah code untuk unauthorized
        //     abort(403, "You can't edit this blog post");
        // }

        // Cara lain gunakan Gate adalah dengan menggunakan authorize
        // Authorize hanya mengizinkan orang dengan id yang sama dengan owner postingan untuk melakukan edit
        // $this->authorize('posts.update', $post);

        /**
         * Ketika kita sudah menggunakan policies mapping pada AuthServiceProvider, kita dapat menggunakan nama langsung dan tidak panjang
         * @see AuthServiceProvider
         * @see BlogPostPolicy
         */
        $this->authorize('update', $post);
        # $this->authorize($post); // Cara Lain

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
