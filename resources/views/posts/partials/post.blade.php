{{-- Bagian dari HTML ini berfungsi untuk me-loop post dan menampilkan di index page --}}
{{-- Title --}}
<h3>
    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
        {{$post->title}}
    </a>
</h3>

{{-- Timestamp --}}
<p class="text-muted">Added {{$post->created_at->diffForHumans()}}
    by {{ $post->user->name }}
</p>

{{-- Display Comment Count --}}
@if ($post->comments_count)
    <p>{{ $post->comments_count }} comments</p>
@else
    <p>No comments yet</p>
@endif

<div class="mb-3">
    {{-- Tombol Edit --}}
    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">
        Edit
    </a>

    {{-- Tombol Delete --}}
    <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
        @csrf
        @method('DELETE')

        <input type="submit" value="Delete!" class="btn btn-primary">
    </form>
</div>
