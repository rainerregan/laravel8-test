<div class="form-group">
    {{-- Menggunakan old() untuk memunculkan kembali input lama --}}
    <label for="title">Title</label>
    <input class="form-control" id="title" type="text" name="title" value="{{ old('title', optional($post ?? null)->title) }}">

    {{-- Display error untuk field spesifik --}}
    {{-- @error('title')
    <div class="alert alert-danger">{{$message}}</div>
    @enderror --}}
</div>
<div class="form-group">
    <label for="content">Content</label>
    <textarea class="form-control" id="content" name="content" id="content" cols="30" rows="10">{{ old('content', optional($post ?? null)->content) }}</textarea>

    {{-- Display error untuk field spesifik --}}
    {{-- @error('content')
        <div>{{$message}}</div>
    @enderror --}}
</div>

{{-- Check Errors --}}
@if ($errors->any())
    <div class="mb-3">
        <ul class="list-group">
            {{-- Loop through errors, and display it --}}
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
