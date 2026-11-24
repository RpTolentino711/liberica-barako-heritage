document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.desktop-nav .nav-dropdown');
    if (dropdown) {
        const trigger = dropdown.querySelector('summary');
        let pinned = false;
        const close = () => {
            pinned = false;
            dropdown.open = false;
        };
        dropdown.addEventListener('pointerenter', event => {
            if (event.pointerType === 'mouse') dropdown.open = true;
        });
        dropdown.addEventListener('pointerleave', () => {
            if (!pinned && !dropdown.contains(document.activeElement)) close();
        });
        trigger.addEventListener('click', event => {
            event.preventDefault();
            if (pinned) close();
            else {
                dropdown.open = true;
                pinned = true;
            }
        });
        trigger.addEventListener('keydown', event => {
            if (event.key !== 'Enter' && event.key !== ' ') return;
            event.preventDefault();
            if (!event.repeat) trigger.click();
        });
        dropdown.addEventListener('focusout', event => {
            if (!dropdown.contains(event.relatedTarget)) close();
        });
        document.addEventListener('pointerdown', event => {
            if (!dropdown.contains(event.target)) close();
        });
        document.addEventListener('keydown', event => {
            if (event.key !== 'Escape' || !dropdown.open) return;
            if (dropdown.contains(document.activeElement)) trigger.focus();
            close();
        });
    }
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('#mobile-nav');
    if (!toggle || !nav) return;
    const closeMenu = () => {
        nav.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
    };
    toggle.addEventListener('click', () => {
        const open = toggle.getAttribute('aria-expanded') === 'true';
        nav.hidden = open;
        toggle.setAttribute('aria-expanded', String(!open));
    });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closeMenu();
    });
});
