@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="left-panel">
        <img src="{{ asset('assets/images/techy-solutions.png') }}" alt="Techy Solutions Logo">
    </div>
    <div class="right-panel">
        <div class="mb-3">
            <img src="{{asset('assets/images/profile.png')}}" alt="User Icon" id="login-profile">
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group mt-5">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <img src="{{asset('assets/images/login_username.png')}}" alt="User Icon" id="login-icon">
                        </span>
                    </div>
                    <input id="login-email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="EMAIL" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="form-group mt-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <img src="{{asset('assets/images/password.png')}}" alt="Password Icon" id="login-icon">
                        </span>
                    </div>
                    <input id="login-password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="PASSWORD" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
            </div>

            <div class="form-group form-check mt-3">
                <input type="checkbox" class="form-check-input" id="login-remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label remember-me" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            <button type="submit" class="btn btn-block mt-3" id="login-submit">
                {{ __('LOG IN') }}
            </button>
        </form>        
    </div>
</div>
@endsection
