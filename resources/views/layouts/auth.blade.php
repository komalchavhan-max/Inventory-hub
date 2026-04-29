<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Helpers\TitleHelper::getTitle() }} - Inventory Hub</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('favicon.svg')}}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --ih-primary: #4f46e5;
            --ih-primary-dark: #4338ca;
            --ih-primary-light: #eef2ff;
            --ih-text: #0f172a;
            --ih-text-muted: #64748b;
            --ih-border: #e5e7eb;
            --ih-bg: #f5f7fb;
            --ih-danger: #ef4444;
            --ih-danger-light: #fee2e2;
            --ih-success: #10b981;
            --ih-success-light: #d1fae5;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--ih-text);
            background: var(--ih-bg);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-size: 0.9375rem;
            line-height: 1.5;
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ---------- Brand panel ---------- */
        .auth-brand {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 55%, #6366f1 100%);
            color: #fff;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .auth-brand::before {
            content: '';
            position: absolute;
            right: -120px; top: -120px;
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(255,255,255,0.18), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .auth-brand::after {
            content: '';
            position: absolute;
            left: -80px; bottom: -80px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-brand-top {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }
        .auth-brand-mark {
            width: 42px; height: 42px;
            border-radius: 12px;
            background: rgba(255,255,255,0.18);
            display: grid; place-items: center;
            font-size: 1.2rem;
            backdrop-filter: blur(8px);
        }
        .auth-brand-name {
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
        }

        .auth-brand-hero {
            position: relative;
            z-index: 1;
            max-width: 440px;
        }
        .auth-brand-hero h1 {
            color: #fff;
            font-weight: 700;
            font-size: 2.1rem;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin: 0 0 16px;
        }
        .auth-brand-hero p {
            color: rgba(255,255,255,0.85);
            font-size: 1rem;
            line-height: 1.6;
            margin: 0;
        }

        .auth-feature-list {
            list-style: none;
            padding: 0;
            margin: 28px 0 0;
            display: grid;
            gap: 14px;
            position: relative;
            z-index: 1;
        }
        .auth-feature-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: rgba(255,255,255,0.92);
            font-size: 0.94rem;
        }
        .auth-feature-list li i {
            width: 22px; height: 22px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: inline-grid; place-items: center;
            font-size: 0.72rem;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .auth-brand-footer {
            position: relative;
            z-index: 1;
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
        }

        /* ---------- Form panel ---------- */
        .auth-form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
        }

        .auth-card-mobile-brand {
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        .auth-card-mobile-brand .brand-mark {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            display: grid; place-items: center;
            color: #fff;
            font-size: 1.05rem;
        }
        .auth-card-mobile-brand .brand-name {
            font-weight: 600;
            font-size: 1rem;
            color: var(--ih-text);
        }

        .auth-card h2 {
            font-weight: 700;
            font-size: 1.6rem;
            margin: 0 0 6px;
            letter-spacing: -0.02em;
        }
        .auth-card .auth-subtitle {
            color: var(--ih-text-muted);
            margin: 0 0 28px;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--ih-text);
            margin-bottom: 6px;
        }

        .form-control {
            border: 1px solid var(--ih-border);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 0.9375rem;
            background: #fff;
            color: var(--ih-text);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .form-control::placeholder { color: #94a3b8; }
        .form-control:focus {
            border-color: var(--ih-primary);
            box-shadow: 0 0 0 4px rgba(79,70,229,0.12);
            outline: none;
        }
        .form-control.is-invalid {
            border-color: var(--ih-danger);
            background-image: none;
            padding-right: 14px;
        }
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239,68,68,0.12);
        }
        .invalid-feedback { font-size: 0.8rem; color: var(--ih-danger); }

        .input-group-ih {
            position: relative;
        }
        .input-group-ih .form-control { padding-left: 42px; }
        .input-group-ih .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
        }
        .input-group-ih .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 6px 8px;
            font-size: 1rem;
            border-radius: 6px;
            transition: color 0.15s ease, background 0.15s ease;
        }
        .input-group-ih .password-toggle:hover {
            color: var(--ih-primary);
            background: var(--ih-primary-light);
        }

        .form-check-input {
            border-color: var(--ih-border);
            margin-top: 0.25rem;
        }
        .form-check-input:checked {
            background-color: var(--ih-primary);
            border-color: var(--ih-primary);
        }
        .form-check-input:focus {
            border-color: var(--ih-primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.18);
        }
        .form-check-label { font-size: 0.88rem; color: var(--ih-text); }

        .auth-row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 4px 0 22px;
        }
        .auth-link {
            color: var(--ih-primary);
            font-weight: 500;
            font-size: 0.88rem;
            text-decoration: none;
        }
        .auth-link:hover { color: var(--ih-primary-dark); text-decoration: underline; }

        .btn-auth {
            width: 100%;
            background: var(--ih-primary);
            border: 1px solid var(--ih-primary);
            color: #fff;
            font-weight: 600;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-auth:hover {
            background: var(--ih-primary-dark);
            border-color: var(--ih-primary-dark);
            box-shadow: 0 8px 20px -6px rgba(79,70,229,0.45);
        }

        .auth-divider-text {
            text-align: center;
            margin-top: 22px;
            color: var(--ih-text-muted);
            font-size: 0.9rem;
        }
        .auth-divider-text a { margin-left: 4px; font-weight: 600; }

        .alert {
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.88rem;
        }
        .alert-danger  { background: var(--ih-danger-light);  color: #991b1b; border-color: rgba(239,68,68,0.25); }
        .alert-success { background: var(--ih-success-light); color: #065f46; border-color: rgba(16,185,129,0.25); }
        .alert p:last-child { margin-bottom: 0; }

        /* ---------- Responsive ---------- */
        @media (max-width: 991.98px) {
            .auth-shell { grid-template-columns: 1fr; }
            .auth-brand { display: none; }
            .auth-card-mobile-brand { display: flex; }
            .auth-form-panel { padding: 32px 20px; min-height: 100vh; }
        }
        @media (min-width: 1280px) {
            .auth-brand { padding: 64px 72px; }
            .auth-brand-hero h1 { font-size: 2.4rem; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="auth-shell">
        <aside class="auth-brand">
            <div class="auth-brand-top">
                <span class="auth-brand-mark"><i class="bi bi-boxes"></i></span>
                <span class="auth-brand-name">Inventory Hub</span>
            </div>

            <div class="auth-brand-hero">
                <h1>@yield('brand_heading', 'Manage your inventory with confidence.')</h1>
                <p>@yield('brand_subheading', 'Track equipment, handle requests, and keep your team productive — all in one place.')</p>

                <ul class="auth-feature-list">
                    <li><i class="bi bi-check-lg"></i> A centralized platform designed to help administrators and employees manage equipment, track requests, and ensure seamless operational flow.</li>

            </div>

            <div class="auth-brand-footer">
                &copy; {{ date('Y') }} Inventory Hub. All rights reserved.
            </div>
        </aside>

        <main class="auth-form-panel">
            <div class="auth-card">
                <div class="auth-card-mobile-brand">
                    <span class="brand-mark"><i class="bi bi-boxes"></i></span>
                    <span class="brand-name">Inventory Hub</span>
                </div>

                @yield('auth_content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var targetId = btn.getAttribute('data-toggle-password');
                var input = document.getElementById(targetId);
                if (!input) return;
                var icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
                } else {
                    input.type = 'password';
                    if (icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
