<div class="mb-2 mt-2">

    @auth
        <form action="{{ route('posts.comments.store', ['post' => $post->id]) }}" method="POST">
            @csrf

            <div class="form-group">
                <textarea class="form-control" id="content" name="content"></textarea>
            </div>

            <div>
                <button class="btn btn-primary btn-block" type="submit">
                    Add comment
                </button>
            </div>
        </form>
        <x-errors :errors="$errors"></x-errors>
    @else
        <a href="{{ route('login') }}">Sign in</a> to post comments!
    @endauth
</div>
<hr>
