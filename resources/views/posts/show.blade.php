@extends('layouts.app')

@section('title', $post['title'])

@section('content')
    <h1>
        {{ $post->title }}

        {{-- Nama components sudah di buat alias di AppServiceProvider, sehingga kita tidak perlu untuk memberikan parameter nama --}}
        {{-- Update Laravel 8: Kita tidak perlu untuk membuat alias, tapi dengan menggunakan x-namaComponent sudah bisa --}}
        <x-badge type="info" :show="now()->diffInMinutes($post->created_at) < 30">
            Brand new post!
        </x-badge>
    </h1>


    <p>{{ $post->content }}</p>

    {{-- Created Timestamp Component --}}
    <x-updated :date="$post->created_at" :name="$post->user->name"></x-updated>

    {{-- Updated Timestamp Component --}}
    <x-updated :date="$post->created_at">
        Updated
    </x-updated>

    <x-tags :tags="$post->tags"></x-tags>

    <p>Currently read by {{ $counter }}</p>


    <h4>Comments</h4>

    @forelse ($post->comments as $comment)
        <p>
            {{ $comment->content }},
        </p>
        {{-- <p class="text-muted">
            added {{ $comment->created_at->diffForHumans() }}
        </p> --}}
        {{-- Timestamp Component --}}
        <x-updated :date="$comment->created_at"></x-updated>

    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection
