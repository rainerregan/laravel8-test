{{-- Bagian dari HTML ini berfungsi untuk me-loop post dan menampilkan di index page --}}
{{-- Title --}}
<h3>
    @if ($post->trashed())
        <del>
    @endif
    <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">
        {{ $post->title }}
    </a>
    @if ($post->trashed())
        </del>
    @endif
</h3>

{{-- Timestamp Component --}}
<x-updated :date="$post->created_at" :name="$post->user->name"></x-updated>

{{-- Display Comment Count --}}
@if ($post->comments_count)
    <p>{{ $post->comments_count }} comments</p>
@else
    <p>No comments yet</p>
@endif

<div class="mb-3">

    {{-- Display tombol edit jika bisa update --}}
    @can('update', $post)
        {{-- Tombol Edit --}}
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">
            Edit
        </a>
    @endcan

    {{-- @cannot('delete', $post)
        <p>You can't delete this post</p>
    @endcannot --}}

    @if (!$post->trashed())
        {{-- Display tombol delete jika dapat delete --}}
        @can('delete', $post)
            {{-- Tombol Delete --}}
            {{-- Simulate DELETE HTTP request by using form --}}
            <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                @csrf
                @method('DELETE')

                <input type="submit" value="Delete!" class="btn btn-primary">
            </form>
        @endcan
    @endif
</div>
