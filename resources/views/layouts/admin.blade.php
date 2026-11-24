<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') — LIBÉRICA Heritage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/coffee_Liberica_bean.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v=farm-location-admin-2">
</head>
<body class="admin-body">
    <div class="sidebar-backdrop" id="sidebarBackdrop" aria-hidden="true"></div>
    <aside class="sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="admin-logo" aria-label="LIBÉRICA Heritage administration dashboard">
                <img src="{{ asset('assets/coffee_Liberica_bean.png') }}" alt="" class="admin-brand-image" width="48" height="48">
                <span class="admin-brand-name">LIBÉRICA<small>HERITAGE ADMIN</small></span>
            </a>
            <button type="button" id="adminCloseSidebar" class="admin-close-sidebar" aria-label="Close navigation">&times;</button>
        </div>
        <nav aria-label="Admin navigation">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">◫ &nbsp; Dashboard</a>
            <a href="{{ route('admin.articles') }}" class="{{ request()->routeIs('admin.articles*') ? 'active' : '' }}">☷ &nbsp; Educational Articles</a>
            <a href="{{ route('admin.encyclopedia') }}" class="{{ request()->routeIs('admin.encyclopedia*') ? 'active' : '' }}">▤ &nbsp; Encyclopedia</a>
            <a href="{{ route('admin.journey.index') }}" class="{{ request()->routeIs('admin.journey*') ? 'active' : '' }}"><svg aria-hidden="true" width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4h9v4a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4zM11 5h1.5a2 2 0 0 1 0 4H11M2 14h11"/></svg> &nbsp; Bean to Cup</a>
            <a href="{{ route('admin.gallery') }}" class="{{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">▣ &nbsp; Photo Gallery</a>
            <a href="{{ route('admin.references') }}" class="{{ request()->routeIs('admin.references*') ? 'active' : '' }}">≡ &nbsp; References</a>
            <a href="{{ route('admin.messages') }}" class="{{ request()->routeIs('admin.messages*') ? 'active' : '' }}">✉ &nbsp; Visitor Messages</a>
            <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings*') ? 'active' : '' }}">⚙ &nbsp; Site Settings</a>
            <a href="{{ route('admin.farm-locations.index') }}" class="{{ request()->routeIs('admin.farm-locations*') ? 'active' : '' }}">&#8982; &nbsp; Farm Map Locations</a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('home') }}" target="_blank" rel="noopener">VIEW PUBLIC WEBSITE ↗</a>
            <button type="button" id="openLogoutDialog" class="sidebar-logout-button" aria-haspopup="dialog" aria-controls="logoutDialog">LOG OUT</button>
        </div>
    </aside>
    <div class="admin-main">
        <header class="admin-top">
            <div class="admin-top-left">
                <button type="button" id="adminMenuToggle" class="admin-menu-toggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="adminSidebar">
                    <span></span><span></span><span></span>
                </button>
                <div>
                    <small>LIBÉRICA / ADMINISTRATION</small>
                    <h1>@yield('title', 'Dashboard')</h1>
                </div>
            </div>
            <span class="admin-account">{{ auth()->user()->name }}</span>
        </header>
        <div class="admin-content">
            @if(session('ok'))<div class="alert-success" role="status">{{ session('ok') }}</div>@endif
            @if($errors->any())<div class="alert-error" role="alert">{{ $errors->first() }}</div>@endif
            @yield('content')
        </div>
    </div>
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
    <dialog id="deleteConfirmDialog" class="delete-confirm-dialog" aria-labelledby="deleteConfirmTitle" aria-describedby="deleteConfirmDescription">
        <div class="delete-confirm-content">
            <div class="logout-warning-icon" aria-hidden="true">!</div>
            <p class="logout-dialog-eyebrow">PLEASE CONFIRM</p>
            <h2 id="deleteConfirmTitle">Delete this content?</h2>
            <p id="deleteConfirmDescription">This action cannot be undone.</p>
            <div class="logout-dialog-actions">
                <button type="button" class="logout-cancel" id="cancelDeleteDialog">CANCEL</button>
                <form id="deleteConfirmForm" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-confirm-button">YES, DELETE</button>
                </form>
            </div>
        </div>
    </dialog>
    <dialog id="publicationConfirmDialog" class="delete-confirm-dialog" aria-labelledby="publicationConfirmTitle" aria-describedby="publicationConfirmDescription">
        <div class="delete-confirm-content">
            <div class="logout-warning-icon" aria-hidden="true">?</div>
            <p class="logout-dialog-eyebrow">PUBLICATION STATUS</p>
            <h2 id="publicationConfirmTitle">Unpublish this article?</h2>
            <p id="publicationConfirmDescription">The article will no longer appear on the public encyclopedia.</p>
            <div class="logout-dialog-actions">
                <button type="button" class="logout-cancel" id="cancelPublicationDialog">CANCEL</button>
                <form id="publicationConfirmForm" method="POST" action="#">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="publication-confirm-button" id="confirmPublicationButton">YES, UNPUBLISH</button>
                </form>
            </div>
        </div>
    </dialog>
    <script>
        (() => {
            // Sidebar mobile toggle
            const sidebar = document.getElementById('adminSidebar');
            const menuToggle = document.getElementById('adminMenuToggle');
            const closeSidebar = document.getElementById('adminCloseSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            const toggleSidebar = (open) => {
                const isOpen = open !== undefined ? open : !sidebar.classList.contains('is-open');
                sidebar.classList.toggle('is-open', isOpen);
                backdrop.classList.toggle('is-open', isOpen);
                if (menuToggle) menuToggle.setAttribute('aria-expanded', String(isOpen));
                document.body.classList.toggle('sidebar-locked', isOpen);
            };

            if (menuToggle) menuToggle.addEventListener('click', () => toggleSidebar());
            if (closeSidebar) closeSidebar.addEventListener('click', () => toggleSidebar(false));
            if (backdrop) backdrop.addEventListener('click', () => toggleSidebar(false));
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('is-open')) toggleSidebar(false);
            });

            // Logout dialog
            const dialog = document.getElementById('logoutDialog');
            const openButton = document.getElementById('openLogoutDialog');
            const cancelButton = document.getElementById('cancelLogoutDialog');
            const logoutForm = document.getElementById('logoutConfirmForm');

            if (openButton && dialog) {
                openButton.addEventListener('click', () => {
                    if (typeof dialog.showModal === 'function') {
                        dialog.showModal();
                    } else if (window.confirm('Log out of LIBÉRICA Heritage administration?')) {
                        logoutForm.requestSubmit();
                    }
                });
            }
            if (cancelButton && dialog) cancelButton.addEventListener('click', () => dialog.close());
            if (dialog) dialog.addEventListener('click', (event) => {
                if (event.target === dialog) dialog.close();
            });

            // Reusable confirmation modal for destructive content actions.
            const deleteDialog = document.getElementById('deleteConfirmDialog');
            const deleteForm = document.getElementById('deleteConfirmForm');
            const deleteTitle = document.getElementById('deleteConfirmTitle');
            const deleteDescription = document.getElementById('deleteConfirmDescription');
            const cancelDelete = document.getElementById('cancelDeleteDialog');
            document.querySelectorAll('[data-confirm-delete]').forEach((button) => {
                button.addEventListener('click', () => {
                    deleteForm.action = button.dataset.deleteAction;
                    deleteTitle.textContent = button.dataset.deleteTitle || 'Delete this content?';
                    deleteDescription.textContent = button.dataset.deleteDescription || 'This action cannot be undone.';
                    if (typeof deleteDialog.showModal === 'function') deleteDialog.showModal();
                    else if (window.confirm(deleteDescription.textContent)) deleteForm.requestSubmit();
                });
            });
            if (cancelDelete && deleteDialog) cancelDelete.addEventListener('click', () => deleteDialog.close());
            if (deleteDialog) deleteDialog.addEventListener('click', (event) => {
                if (event.target === deleteDialog) deleteDialog.close();
            });

            // Confirm before changing an encyclopedia entry's public visibility.
            const publicationDialog = document.getElementById('publicationConfirmDialog');
            const publicationForm = document.getElementById('publicationConfirmForm');
            const publicationTitle = document.getElementById('publicationConfirmTitle');
            const publicationDescription = document.getElementById('publicationConfirmDescription');
            const publicationSubmit = document.getElementById('confirmPublicationButton');
            const cancelPublication = document.getElementById('cancelPublicationDialog');
            document.querySelectorAll('[data-confirm-publication]').forEach((button) => {
                button.addEventListener('click', () => {
                    const isPublished = button.dataset.entryPublished === '1';
                    const entryTitle = button.dataset.entryTitle || 'this article';
                    publicationForm.action = button.dataset.publicationAction;
                    publicationTitle.textContent = isPublished ? 'Are you sure you want to unpublish this article?' : 'Publish this article?';
                    publicationDescription.textContent = isPublished
                        ? `“${entryTitle}” will be hidden from public encyclopedia pages and remain available in admin.`
                        : `“${entryTitle}” will become visible on the public encyclopedia.`;
                    publicationSubmit.textContent = isPublished ? 'YES, UNPUBLISH' : 'YES, PUBLISH';
                    if (typeof publicationDialog.showModal === 'function') publicationDialog.showModal();
                    else if (window.confirm(publicationDescription.textContent)) publicationForm.requestSubmit();
                });
            });
            if (cancelPublication && publicationDialog) cancelPublication.addEventListener('click', () => publicationDialog.close());
            if (publicationDialog) publicationDialog.addEventListener('click', (event) => {
                if (event.target === publicationDialog) publicationDialog.close();
            });
        })();
    </script>
</body>
</html>
