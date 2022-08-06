<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted as EventsCommentPosted;
use App\Http\Requests\StoreComment;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottleMail;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedMarkdown;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Mail;
use App\Jobs\ThrottledMail;

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

        // Create event untuk menghandle semua pengiriman email.
        event(new EventsCommentPosted($comment));

        return redirect()->back()->withStatus('Comment was created!');
    }

    public function index(BlogPost $post)
    {
        return $post->comments()->with('user')->get();
    }
}
