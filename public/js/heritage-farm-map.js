/* LIBÉRICA Heritage — documented coffee areas on an accessible Leaflet map.
   Coordinates are barangay centroids, NOT exact farm entrances.
   Map requires an internet connection for Leaflet and OpenStreetMap tiles. */
(function () {
    'use strict';
    function initializeFarmMap() {
        const element = document.getElementById('liberica-farm-map');
        const fallback = document.getElementById('farm-map-fallback');
        if (!element) return;
        if (!window.L) { if (fallback) fallback.hidden = false; return; }
        const places = [
            {
                name: 'Katy’s Farm — Tipakan area',
                place: 'Tipakan, Lipa City, Batangas',
                lat: 13.9146, lng: 121.20341,
                maps: 'https://www.google.com/maps/search/?api=1&query=Katy%27s%20Farm%20Tipakan%20Lipa%20Batangas'
            },
            {
                name: 'BARAKO Smart Demo Farm — Masaya area',
                place: 'Masaya, Rosario, Batangas',
                lat: 13.8383, lng: 121.1852,
                maps: 'https://www.google.com/maps/search/?api=1&query=BARAKO%20PROJECT%20SMART%20DEMO%20FARM%20Masaya%20Rosario%20Batangas'
            }
        ];
        try {
            const map = L.map(element, { scrollWheelZoom: false });
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> contributors',
                maxZoom: 18
            }).addTo(map);
            const bounds = [];
            places.forEach(function (place) {
                const point = [place.lat, place.lng];
                const safePopup = '<strong>' + place.name + '</strong><br>' + place.place +
                    '<br><em>Approximate barangay marker — not the farm gate.</em><br>' +
                    '<a target="_blank" rel="noopener noreferrer" href="' + place.maps + '">Find the listed site in Google Maps ↗</a>';
                L.marker(point).addTo(map).bindPopup(safePopup);
                bounds.push(point);
            });
            map.fitBounds(bounds, { padding: [50, 50], maxZoom: 11 });
            window.addEventListener('resize', function () { map.invalidateSize(); });
        } catch (error) {
            console.warn('Heritage farm map could not initialize:', error);
            if (fallback) fallback.hidden = false;
        }
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeFarmMap);
    else initializeFarmMap();
}());
