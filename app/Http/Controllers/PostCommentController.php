<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Jobs\NotifyUsersPostWasCommented;
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

        // Sending email Immediate
        // Mail::to($post->user)->send(
        //     new CommentPostedMarkdown($comment)
        // );

        // Sending email queue
        Mail::to($post->user)->queue(
            new CommentPostedMarkdown($comment)
        );

        // Sending email using queue
        NotifyUsersPostWasCommented::dispatch($comment);

        // Sending email later
        // $when = now()->addMinutes(1);
        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedMarkdown($comment)
        // );


        return redirect()->back()->withStatus('Comment was created!');
    }
}
