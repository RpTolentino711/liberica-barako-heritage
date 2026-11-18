<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1b100b">
    <meta name="description" content="Explore the history, cultivation, and culture of Batangas Kapeng Barako and Coffea liberica, with a focus on Lipa City.">
    <title>{{ ($settings['site_name'] ?? 'LIBÉRICA') }} — Batangas Kapeng Barako Heritage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/coffee_Liberica_bean.png') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v=collection-refined-3">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script defer src="{{ asset('js/landing.js') }}"></script>
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script defer src="{{ asset('js/heritage-farm-map.js') }}?v=1"></script>
</head>
<body>
    <a class="skip-link" href="#main">Skip to main content</a>
    <div class="announcement" id="top">✦ &nbsp; THE BOLD ORIGIN. THE BATANGAS STORY. &nbsp; ✦</div>
    <header class="site-header">
        <a href="{{ route('home') }}" class="brand" aria-label="LIBÉRICA homepage">
            <img class="brand-icon brand-bean-logo" src="{{ asset('assets/coffee_Liberica_bean.png') }}" alt="" width="52" height="52">
            <span>LIBÉRICA<small>KAPENG BARAKO HERITAGE</small></span>
        </a>
        <nav class="desktop-nav" aria-label="Main navigation">
            <a href="#heritage">Our Heritage</a>
            <a href="#journey">Bean to Cup</a>
            <a href="#culture">Coffee Culture</a>
            <a href="#gallery">Gallery</a>
            <a href="#heritage-collection">Collection</a>
            <a href="#farm-map">Farm Map</a>
        </nav>
        <a class="header-link" href="#references">OUR SOURCES <span aria-hidden="true">↗</span></a>
        <button class="menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false" aria-controls="mobile-nav"><span></span><span></span></button>
    </header>
    <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile navigation" hidden>
        <a href="#heritage">Our Heritage</a><a href="#discover">Discover Liberica</a><a href="#journey">Bean to Cup</a><a href="#culture">Coffee Culture</a><a href="#gallery">Gallery</a><a href="#heritage-collection">Collection</a><a href="#farm-map">Farm Map</a><a href="#references">Sources</a><a href="#contact">Contact</a>
    </nav>
    <main id="main">@yield('content')</main>
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-identity">
                <a href="{{ route('home') }}" class="footer-brand-link" aria-label="LIBÉRICA homepage">
                    <img class="footer-bean-logo" src="{{ asset('assets/coffee_Liberica_bean.png') }}" alt="" width="72" height="72" loading="lazy">
                    <span class="footer-logo">LIBÉRICA</span>
                </a>
                <p>Honoring the bold coffee heritage of Batangas.</p>
            </div>
            <p>An independent educational showcase.<br>Not a coffee shop or sales website.</p>
            <a href="#top">BACK TO TOP ↑</a>
        </div>
        <div class="footer-bottom"><span>© {{ date('Y') }} LIBÉRICA · LIPA, BATANGAS</span><span>COFFEE · CULTURE · COMMUNITY</span></div>
    </footer>
</body>
</html>
