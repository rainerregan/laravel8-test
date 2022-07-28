@extends('layouts.app')

@section('title', 'Update the post')

@section('content')
    {{-- Membuat sebuah form yang ketika di submit akan mengirim data ke posts.store --}}
    <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST">
        @csrf
        @method('PUT')
        @include('posts.partials.form')

        <div>
            <input class="btn btn-primary btn-block" type="submit" value="Update">
        </div>
    </form>
@endsection
