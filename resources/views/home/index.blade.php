@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
    {{-- <h1>{{ __('messages.welcome') }}</h1> --}}
    <h1>@lang('messages.welcome')</h1>
    <p>@lang('messages.example_with_value', ['name' => 'John'])</p>
    <p>{{ trans_choice('messages.plural', 0, ['additional' => 'Additional']) }}</p>
    <p>{{ trans_choice('messages.plural', 1) }}</p>
    <p>{{ trans_choice('messages.plural', 2) }}</p>

    <p>Using JSON: {{ __('Welcome to laravel') }} </p>
    <p>Using JSON: {{ __('Hello :name', ['name' => 'Rainer']) }} </p>

    <p>This is the main page</p>
@endsection
