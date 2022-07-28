@extends('layouts.app')

@section('title', 'Create a post')

@section('content')
    {{-- Membuat sebuah form yang ketika di submit akan mengirim data ke posts.store --}}
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        @include('posts.partials.form')

        <div>
            <input class="btn btn-primary btn-block" type="submit" value="Create">
        </div>
    </form>

@endsection
