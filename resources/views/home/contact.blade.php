@extends('layouts.app')

@section('title', 'Contact Page')

@section('content')
    <h1>Contact</h1>
    <p>Hello, this is contact page</p>

    @can('home.secret')
        <p>
            <a href="{{route('secret')}}">
                Special contact detail
            </a>
        </p>
    @endcan
@endsection
