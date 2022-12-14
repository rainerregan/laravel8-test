<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserController;
use App\Mail\CommentPostedMarkdown;
use App\Models\Comment;
use App\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Home
// Route::get('/', function () {
//     return view('home.index', []);
// })->name('home.index'); // Nama route disarankan mengikuti nama view
// Route::view('/', 'home.index')->name('home.index'); // Bentuk simple untuk route dengan 1 view

// Penamaan Route
// Route::get('/contact', function(){
//     return view('home.contact');
// })->name('home.contact');
// Route::view('/contact', 'home.contact')->name('home.contact'); // Bentuk simple untuk route dengan 1 view

// Route Optional Parameter
// Route::get('/recent-posts/{days_ago?}', function ($days_ago = 20) {
//     return "Posts from " . $days_ago . " days ago";
// });

// $posts = [
//     1 => [
//         'title' => 'Intro to Laravel',
//         'content' => 'This is a short intro to Laravel',
//         'is_new' => true,
//         'has_comments' => true
//     ],
//     2 => [
//         'title' => 'Intro to PHP',
//         'content' => 'This is a short intro to PHP',
//         'is_new' => false
//     ]
// ];

// Route Parameter Constraint
// Route::get('/posts/{id}', function ($id) use ($posts) {
//     abort_if(!isset($posts[$id]), 404); // Menampilkan error jika tidak ketemu
//     return view('posts.show', ['post' => $posts[$id]]);
// })->where([
//     'id' => '[0-9]+' // Menggunakan Regex
// ])->name('posts.show');


// Get HTML Requests
// Route::get('/posts', function () use ($posts) {

//     // dd adalah dump data
//     // Mendapatkan request dari URL
//     // dd(request()->all());

//     // Mendapatkan data dari request parameter
//     // dd(request()->input('page', 1));

//     // Query parameters, bersifat sama
//     dd(request()->query('page', 1));

//     return view('posts.index', ['posts' => $posts]);
// });


// Route Grouping
// Route::prefix('/fun')->name('fun.')->group(function () use($posts) {
//     // Creating API responses
//     Route::get('responses', function () use ($posts) {
//         return response($posts, 201)
//             ->header('Content-Type', 'application/json')
//             ->cookie('MY_COOKIE', 'Rainer Regan', 3600);
//     })->name('responses');

//     // Redirect Page
//     Route::get('redirect', function () {
//         return redirect('/contact');
//     })->name('redirect');

//     // Redirect to back
//     Route::get('back', function () {
//         return back();
//     })->name('back');

//     // Redirect to named route
//     Route::get('named-route', function () {
//         return redirect()->route('posts.show', ['id' => 1]);
//     })->name('named-route');

//     // Redirect to another website
//     Route::get('away', function () {
//         return redirect()->away('https://google.com');
//     })->name('away');

//     // Return JSON
//     Route::get('json', function () use ($posts) {
//         return response()->json($posts);
//     })->name('json');

//     // Return Downloadable File
//     Route::get('download', function () use ($posts) {
//         return response()->download(public_path('/laravel.jpg'), 'abc.jpg');
//     })->name('download');
// });

// Single Action Controller
// Route::get('/single', AboutController::class);

// Middleware
// Contoh menggunakan middleware auth adalah dibawah
// Route::get('/recent-posts/{days_ago?}', function ($days_ago = 20) {
//     return "Posts from " . $days_ago . " days ago";
// })->name('posts.recent.index')->middleware('auth');



// Contoh menggunakan controller
Route::get('/', [HomeController::class, 'home'])->name('home.index');
    // ->middleware('auth'); // Requesting Auth to access

Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');

// Resource Controller
Route::resource('posts', PostsController::class); // Using all controller method

Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Menggunakan gate 'home.secret' yang telah dibuat di AuthServiceProvider
 * untuk allow hanya admin pada page ini.
 *
 * @see AuthServiceProvider
 */
Route::get('/secret', [HomeController::class, 'secret'])
    ->name('secret')->middleware('can:home.secret'); // Menggunakan can middleware untuk menggunakan gate

Route::get('/posts/tag/{tag}', [PostTagController::class, 'index'])->name('posts.tags.index');

Route::resource('posts.comments', PostCommentController::class)->only(['store', 'index']);
Route::resource('users', UserController::class)->only(['show', 'edit', 'update']);
Route::resource('users.comments', UserCommentController::class)->only(['store']);

Route::get('mailable', function() {
    $comment = Comment::find(1);
    return new CommentPostedMarkdown($comment);
});
