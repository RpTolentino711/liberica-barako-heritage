<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password — LIBÉRICA Heritage</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/coffee_Liberica_bean.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}?v=1">
</head>
<body class="login-body">
    <main class="login-card forgot-password-card">
        {{-- Keep the original login branding, but replace its decorative symbol with the existing logo. --}}
        <div class="login-mark forgot-brand-mark" aria-hidden="true">
            <img src="{{ asset('assets/coffee_Liberica_bean.png') }}" alt="" width="62" height="62">
        </div>
        <h1>LIBÉRICA</h1>
        <p>Heritage Administration</p>
        <p class="auth-intro">Enter your administrator email address to receive a password reset link.</p>

        @if (session('status'))
            <div class="alert-success" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}" id="forgot-password-form" novalidate>
            @csrf
            <label for="email">Email address</label>
            <input
                id="email"
                name="email"
                type="email"
                inputmode="email"
                autocomplete="email"
                maxlength="254"
                value="{{ old('email') }}"
                aria-describedby="forgot-email-feedback"
                required
                autofocus
            >
            {{-- Reserved height prevents layout shifts when validation messages appear. --}}
            <div id="forgot-email-feedback" class="forgot-email-feedback" role="status" aria-live="polite"></div>
            <button class="primary-button" type="submit" id="send-reset-link">SEND RESET LINK →</button>
        </form>

        <a href="{{ route('admin.login') }}">← Back to login</a>
    </main>

    <script>
        (() => {
            'use strict';
            const form = document.getElementById('forgot-password-form');
            const email = document.getElementById('email');
            const feedback = document.getElementById('forgot-email-feedback');
            const submit = document.getElementById('send-reset-link');
            const emailPattern = /^[^\s@]+@[^\s@.]+(?:\.[^\s@.]+)+$/;
            let attempted = false;

            function validate() {
                const value = email.value.trim();
                const valid = emailPattern.test(value) && email.checkValidity();
                let message = '';
                if (!value && attempted) {
                    message = 'Please enter your email address.';
                } else if (value && !valid) {
                    message = 'Enter a valid email address (e.g. name@example.com).';
                }
                feedback.textContent = message;
                feedback.classList.toggle('is-error', Boolean(message));
                email.setAttribute('aria-invalid', String(Boolean(message)));
                submit.disabled = !valid;
                return valid;
            }

            email.addEventListener('input', validate);
            form.addEventListener('submit', (event) => {
                attempted = true;
                if (!validate()) {
                    event.preventDefault();
                    email.focus();
                }
            });
            validate();
        })();
    </script>
</body>
</html>
