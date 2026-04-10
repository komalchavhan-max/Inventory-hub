<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Inventory Hub</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('spike-bootstrap-free-v2/src/assets/images/logos/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('spike-bootstrap-free-v2/src/assets/css/styles.min.css') }}" />
</head>
<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="{{ url('/') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('spike-bootstrap-free-v2/src/assets/images/logos/logo.svg') }}" alt="Logo">
                                </a>
                                <p class="text-center">Reset Your Password</p>
                                <p class="text-center text-muted small mb-4">Enter your email address and we'll send you a link to reset your password.</p>
                                
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        @foreach($errors->all() as $error)
                                            <p class="mb-0">{{ $error }}</p>
                                        @endforeach
                                    </div>
                                @endif
                                
                                @if(session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" value="{{ old('email') }}" required autofocus>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Send Password Reset Link</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <a class="text-primary fw-bold" href="{{ route('login') }}">Back to Login</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('spike-bootstrap-free-v2/src/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('spike-bootstrap-free-v2/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>