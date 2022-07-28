@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
    @if (count($posts))
        {{-- @each('posts.partials.post', $posts, 'post') --}}

        @foreach ($posts as $key => $post)
            {{-- Memasukkan partial template, semua variable akan tetap bisa masuk --}}
            @include('posts.partials.post')
        @endforeach
    @else
        No posts found!
    @endif
@endsection
