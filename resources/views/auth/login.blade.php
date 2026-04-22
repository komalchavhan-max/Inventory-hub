@extends('layouts.auth')

@section('title', 'Login')
@section('brand_heading', 'Welcome back to Inventory Hub.')
@section('brand_subheading', 'Sign in to access your dashboard, manage equipment, and keep your team moving.')

@section('auth_content')
    <h2>Sign in to your account</h2>
    <p class="auth-subtitle">Enter your credentials below to continue.</p>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            @foreach($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group-ih">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="you@company.com" required autofocus>
            </div>
            @error('email')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-2">
            <label for="password" class="form-label">Password</label>
            <div class="input-group-ih">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter your password" required>
                <button type="button" class="password-toggle" data-toggle-password="password" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="auth-row-between">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <a class="auth-link" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="btn-auth">Sign in</button>

        <p class="auth-divider-text">
            New to Inventory Hub?<a class="auth-link" href="{{ route('register') }}">Create an account</a>
        </p>
    </form>
@endsection
