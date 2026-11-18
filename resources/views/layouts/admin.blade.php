<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration') — LIBÉRICA Heritage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/coffee_Liberica_bean.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=logout-confirm-1">
</head>
<body class="admin-body">
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-logo" aria-label="LIBÉRICA Heritage administration dashboard">
            <img src="{{ asset('assets/coffee_Liberica_bean.png') }}" alt="" class="admin-brand-image" width="48" height="48">
            <span class="admin-brand-name">LIBÉRICA<small>HERITAGE ADMIN</small></span>
        </a>
        <nav aria-label="Admin navigation">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">◫ &nbsp; Dashboard</a>
            <a href="{{ route('admin.articles') }}" class="{{ request()->routeIs('admin.articles*') ? 'active' : '' }}">☷ &nbsp; Educational Articles</a>
            <a href="{{ route('admin.gallery') }}" class="{{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">▣ &nbsp; Photo Gallery</a>
            <a href="{{ route('admin.references') }}" class="{{ request()->routeIs('admin.references*') ? 'active' : '' }}">≡ &nbsp; References</a>
            <a href="{{ route('admin.messages') }}" class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">✉ &nbsp; Visitor Messages</a>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">⚙ &nbsp; Site Settings</a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('home') }}" target="_blank" rel="noopener">VIEW PUBLIC WEBSITE ↗</a>
            <button type="button" id="openLogoutDialog" class="sidebar-logout-button" aria-haspopup="dialog" aria-controls="logoutDialog">LOG OUT</button>
        </div>
    </aside>
    <div class="admin-main"><header class="admin-top"><div><small>LIBÉRICA / ADMINISTRATION</small><h1>@yield('title', 'Dashboard')</h1></div><span class="admin-account">{{ auth()->user()->name }}</span></header><div class="admin-content">@if(session('ok'))<div class="alert-success" role="status">{{ session('ok') }}</div>@endif @if($errors->any())<div class="alert-error" role="alert">{{ $errors->first() }}</div>@endif @yield('content')</div></div>
    {{-- Confirmation is required before posting to the existing logout route. --}}
    <dialog id="logoutDialog" class="logout-dialog" aria-labelledby="logoutDialogTitle" aria-describedby="logoutDialogDescription">
        <div class="logout-dialog-content">
            <div class="logout-warning-icon" aria-hidden="true">!</div>
            <p class="logout-dialog-eyebrow">ACCOUNT SECURITY</p>
            <h2 id="logoutDialogTitle">Log out of LIBÉRICA?</h2>
            <p id="logoutDialogDescription">You are about to end your administrator session. You will need to sign in again to access the dashboard.</p>
            <div class="logout-dialog-actions">
                <button type="button" class="logout-cancel" id="cancelLogoutDialog">CANCEL</button>
                <form id="logoutConfirmForm" action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-confirm">YES, LOG OUT</button>
                </form>
            </div>
        </div>
    </dialog>
    <script>
        (() => {
            const dialog = document.getElementById('logoutDialog');
            const openButton = document.getElementById('openLogoutDialog');
            const cancelButton = document.getElementById('cancelLogoutDialog');
            const logoutForm = document.getElementById('logoutConfirmForm');

            openButton.addEventListener('click', () => {
                if (typeof dialog.showModal === 'function') {
                    dialog.showModal();
                } else if (window.confirm('Log out of LIBÉRICA Heritage administration?')) {
                    logoutForm.requestSubmit();
                }
            });
            cancelButton.addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) dialog.close();
            });
        })();
    </script>
</body>
</html>
