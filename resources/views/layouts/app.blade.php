<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel App - @yield('title')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-white border-bottom shadow-sm mb-3">
        <h5 class="my-0 mr-md-auto font-weight-normal">Laravel App</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="{{route('home.index')}}">Home</a>
            <a class="p-2 text-dark" href="{{route('home.contact')}}">Contact</a>
            <a class="p-2 text-dark" href="{{route('posts.index')}}">Blog Posts</a>
            <a class="p-2 text-dark" href="{{route('posts.create')}}">Add Blog Posts</a>
        </nav>
    </div>
    <div class="container">
        @if (session('status'))
            <div style="background: red; color:white">
                {{session('status')}}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>
