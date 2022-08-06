<div class="container">

    <div class="row">

        {{-- Most Commented Section --}}
        {{-- Using Component --}}
        <x-card title="{{ __('Most Commented') }}" subtitle="{{ __('What people are currently talking about') }}">
            @slot('items')
                @foreach ($mostCommented as $post)
                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                        <li class="list-group-item">{{ $post->title }}</li>
                    </a>
                @endforeach
            @endslot
        </x-card>

    </div>

    <div class="row mt-4">

        {{-- Most Active Section --}}
        {{-- Menggunakan Component --}}
        <x-card title="{{__('Most Active')}}" subtitle="{{__('Writers with most posts written')}}" :items="collect($mostActive)->pluck('name')">
        </x-card>

    </div>

    <div class="row mt-4">

        {{-- Most Active Last Month Section --}}
        {{-- Menggunakan Component --}}
        <x-card title="{{__('Most Active Last Month')}}" subtitle="{{__('Users with most posts written in the month')}}" :items="collect($mostActiveLastMonth)->pluck('name')">
        </x-card>

    </div>


</div>
