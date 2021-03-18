@extends('layouts.login')

@section('content')
<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
            </div>
            <form method="POST" action="{{ route('login') }}" class="user">
                @csrf
                <div class="form-group">
                    <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <input  id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                        <input class="custom-control-input" type="checkbox" name="remember" id="customCheck" {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="customCheck">{{ __('Remember Me') }}</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">
                    {{ __('Login') }}
                </button>
            </form>
            <hr>
            @if (Route::has('password.request'))
                <div class="text-center">
                    <a class="small" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
