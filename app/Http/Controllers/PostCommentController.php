<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Mail;

/**
 * Post Comment Controller
 *
 * Relate dengan mengenai comments di dalam post.
 */
class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id
        ]);

        // Sending email
        Mail::to($post->user)->send(
            new CommentPostedMarkdown($comment)
        );

        return redirect()->back()->withStatus('Comment was created!');
    }
}
