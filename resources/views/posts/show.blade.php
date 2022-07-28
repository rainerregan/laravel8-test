@extends('layouts.app')

@section('title', $post['title'])

@section('content')

    @if ($post['is_new'])
        <div>
            <p>A new blog post!</p>
        </div>
    @elseif (!$post['is_new'])
        <div>
            <p>An old blog post!</p>
        </div>
    @endif

    @unless ($post['is_new'])
        <div>It is an old post using unless</div>
    @endunless

    <h1>{{ $post['title'] }}</h1>
    <p>{{ $post['content'] }}</p>

    @isset($post['has_comments'])
        <div>The post has some comments using isset.</div>
    @endisset
@endsection
