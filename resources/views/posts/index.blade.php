@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
    <div class="row">
        <div class="col-8">
            @if (count($posts))
                {{-- @each('posts.partials.post', $posts, 'post') --}}

                @foreach ($posts as $key => $post)
                    {{-- Memasukkan partial template, semua variable akan tetap bisa masuk --}}
                    @include('posts.partials.post')
                @endforeach
            @else
                No posts found!
            @endif
        </div>
        {{-- Right Column --}}
        <div class="col-4">
            {{-- Menggunakan include untuk view composer --}}
            @include('posts.partials.activity')
        </div>
    </div>
@endsection
