@extends('merchant.auth.master')
@section('title', 'Login')
@section('content')


<!-- Register -->
<div class="card">
    <div class="card-body">
        @include('merchant.auth.logo')
        <h4 class="mb-2">Welcome to Sneat! 👋</h4>
        <p class="mb-4">Please sign-in to your account and start the adventure</p>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form id="formAuthentication" class="mb-3" action="{{ route('merchant.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email or username" autofocus value="{{ old('email') }}" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            @if(config('verification.way') !== 'passwordLess' && config('verification.way') !== 'otp')
            <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>

                </div>
                <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="cursor-pointer input-group-text"><i class="bx bx-hide"></i></span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            @endif

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
        </form>

        <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{ route('merchant.register') }}">
                <span>Create an account</span>
            </a>
        </p>
    </div>
</div>
<!-- /Register -->

@endsection
