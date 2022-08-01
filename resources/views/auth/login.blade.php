@extends('layouts.app')
@section('content')
    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="form=group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" required
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}">

            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form=group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">

                @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{$errors->first('password')}}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember"
                    value="{{old('remember') ? 'checked': ''}}">
                <label for="remember" class="form-check-label" >Remember Me</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block mt-3">Login!</button>
    </form>
@endsection
