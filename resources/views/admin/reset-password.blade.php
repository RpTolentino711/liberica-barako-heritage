<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — LIBÉRICA Heritage</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/coffee_Liberica_bean.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-auth.css') }}?v=1">
</head>
<body class="login-body">
    <main class="login-card">
        <div class="login-mark" aria-hidden="true">✳</div>
        <h1>LIBÉRICA</h1>
        <p>Heritage Administration</p>

        <p class="auth-intro">Create a new password for your administrator account.</p>

        @if ($errors->any())
            <div class="alert-error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="username" readonly>

            <label for="password">New password</label>
            <input id="password" name="password" type="password" required minlength="12" autocomplete="new-password" autofocus>

            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required minlength="12" autocomplete="new-password">

            <p class="auth-password-hint">At least 12 characters, including uppercase and lowercase letters and numbers.</p>
            <button class="primary-button" type="submit">RESET PASSWORD →</button>
        </form>

        <a href="{{ route('admin.login') }}">← Back to login</a>
    </main>
</body>
</html>
