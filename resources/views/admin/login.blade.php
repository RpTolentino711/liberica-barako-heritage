<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — LIBÉRICA Heritage</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/coffee_Liberica_bean.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-auth.css') }}?v=4">
</head>
<body class="login-body">
    <main class="login-card">
        <div class="login-mark login-brand-mark" aria-hidden="true">
            <img src="{{ asset('assets/coffee_Liberica_bean.png') }}" alt="" width="62" height="62">
        </div>
        <h1>LIBÉRICA</h1>
        <p>Heritage Administration</p>

        @if (session('status'))
            <div class="alert-success" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.authenticate') }}" id="admin-login-form" novalidate>
            @csrf
            <label for="email">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                inputmode="email"
                value="{{ old('email') }}"
                required
                maxlength="254"
                autocomplete="username"
                aria-describedby="email-feedback"
                autofocus
            >
            {{-- Reserved-height feedback keeps the card steady as validation changes. --}}
            <div class="auth-email-feedback" id="email-feedback" role="status" aria-live="polite"></div>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password">
            <button class="primary-button" type="submit" id="admin-signin-button">SIGN IN →</button>
        </form>

        <a class="auth-secondary-link" href="{{ route('admin.password.request') }}">Forgot password?</a>
        <a href="{{ route('home') }}">← Back to website</a>
    </main>

    <script>
        (() => {
            'use strict';

            const form = document.getElementById('admin-login-form');
            const email = document.getElementById('email');
            const feedback = document.getElementById('email-feedback');
            const submit = document.getElementById('admin-signin-button');
            const emailPattern = /^[^\s@]+@[^\s@.]+(?:\.[^\s@.]+)+$/;
            let attempted = false;

            // Instant, client-side email FORMAT validation; no fetch request or debounce.
            // Actual account and password checks remain in Laravel's authenticate handler.
            function validateEmail() {
                const value = email.value.trim();
                const valid = emailPattern.test(value) && email.checkValidity();

                let error = '';
                if (!value && attempted) {
                    error = 'Please enter your email address.';
                } else if (value && !valid) {
                    error = 'Enter a valid email address (e.g. name@example.com).';
                }

                feedback.textContent = error;
                feedback.classList.toggle('is-error', Boolean(error));
                email.setAttribute('aria-invalid', String(Boolean(error)));
                submit.disabled = !valid;
                return valid;
            }

            email.addEventListener('input', validateEmail);

            form.addEventListener('submit', (event) => {
                attempted = true;
                if (!validateEmail()) {
                    event.preventDefault();
                    email.focus();
                }
            });

            validateEmail();
        })();
    </script>
</body>
</html>
