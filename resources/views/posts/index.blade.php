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
            <div class="container">

                <div class="row">

                    {{-- Most Commented Section --}}
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Commented</h5>
                            <h6 class="card-subtitle mb-2 text-muted">What people are currently talking about</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostCommented as $post)
                                <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                    <li class="list-group-item">{{ $post->title }}</li>
                                </a>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <div class="row mt-4">

                    {{-- Most Active Section --}}
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Most Active User</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                User with most blog posts
                            </h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach ($mostActive as $user)
                                <li class="list-group-item">
                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>


            </div>

        </div>
    </div>
@endsection
