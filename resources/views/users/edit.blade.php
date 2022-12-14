@extends('layouts.app')

@section('content')
    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-4">
                <img src="{{$user->image ? $user->image->url() : ''}}" alt="" class="img-thumbnail avatar">

                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload new photo</h6>
                        <input type="file" name="avatar" id="avatar" class="form-control-file">
                    </div>
                </div>
            </div>

            <div class="col-8">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name">
                </div>

                <div class="form-group">
                    <label for="locale"> {{__('Language')}} </label>
                    <select name="locale" id="locale" class="form-control">
                        @foreach (App\Models\User::LOCALES as $locale => $label)
                            <option value="{{$locale}}" {{$user->locale == $locale ? 'selected': ''}}>
                                {{$label}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <x-errors :errors="$errors"></x-errors>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Save changes">
                </div>
            </div>
        </div>
    </form>
@endsection
