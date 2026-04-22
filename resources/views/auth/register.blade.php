@extends('layouts.auth')

@section('title', 'Create Account')
@section('brand_heading', 'Join Inventory Hub today.')
@section('brand_subheading', "Set up your account in seconds and start bringing order to your organization's equipment.")

@section('auth_content')
    <h2>Create your account</h2>
    <p class="auth-subtitle">Fill in your details to get started.</p>

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            @foreach($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <div class="input-group-ih">
                <i class="bi bi-person input-icon"></i>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Jane Doe" required autofocus>
            </div>
            @error('name')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group-ih">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="you@company.com" required>
            </div>
            @error('email')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group-ih">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Create a strong password" required>
                <button type="button" class="password-toggle" data-toggle-password="password" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')<div class="invalid-feedback d-block mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="input-group-ih">
                <i class="bi bi-shield-lock input-icon"></i>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control" placeholder="Repeat password" required>
                <button type="button" class="password-toggle" data-toggle-password="password_confirmation" aria-label="Show password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-auth">Create account</button>

        <p class="auth-divider-text">
            Already have an account?<a class="auth-link" href="{{ route('login') }}">Sign in</a>
        </p>
    </form>
@endsection
