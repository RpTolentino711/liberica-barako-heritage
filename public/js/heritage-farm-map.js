/* Interactive map pins and popups are populated from administrator-managed locations. */
(function () {
    'use strict';

    function decodeLocations(element) {
        try {
            const binary = atob(element.dataset.locations || '');
            const bytes = Uint8Array.from(binary, (character) => character.charCodeAt(0));
            return JSON.parse(new TextDecoder().decode(bytes));
        } catch (error) {
            console.warn('Map location data could not be read:', error);
            return [];
        }
    }

    function popupContent(place) {
        const wrapper = document.createElement('div');
        wrapper.className = 'farm-map-popup';
        if (place.photo) {
            const image = document.createElement('img');
            image.className = 'farm-map-popup-image';
            image.src = place.photo;
            image.alt = place.photo_alt || place.name;
            image.loading = 'lazy';
            wrapper.append(image);
        }

        const title = document.createElement('strong');
        title.className = 'farm-map-popup-title';
        title.textContent = place.name;
        wrapper.append(title);

        const address = document.createElement('p');
        address.className = 'farm-map-popup-place';
        address.textContent = place.place;
        wrapper.append(address);

        const description = document.createElement('p');
        description.className = 'farm-map-popup-description';
        description.textContent = place.description;
        wrapper.append(description);

        const mapsLink = document.createElement('a');
        mapsLink.href = place.maps;
        mapsLink.target = '_blank';
        mapsLink.rel = 'noopener noreferrer';
        mapsLink.textContent = 'View location in Google Maps ↗';
        wrapper.append(mapsLink);
        return wrapper;
    }

    function initializeFarmMap() {
        const element = document.getElementById('liberica-farm-map');
        const fallback = document.getElementById('farm-map-fallback');
        if (!element) return;
        if (!window.L) { if (fallback) fallback.hidden = false; return; }

        const places = decodeLocations(element);
        try {
            const map = L.map(element, { scrollWheelZoom: false }).setView([13.9, 121.1], 9);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);

            const bounds = [];
            const markers = new Map();
            places.forEach((place) => {
                const point = [Number(place.latitude), Number(place.longitude)];
                if (!Number.isFinite(point[0]) || !Number.isFinite(point[1])) return;
                const marker = L.marker(point).addTo(map).bindPopup(popupContent(place), { maxWidth: 280 });
                marker.on('click', () => {
                    document.querySelectorAll('.farm-location-card.is-selected').forEach((card) => card.classList.remove('is-selected'));
                    document.querySelector(`[data-location-card="${Number(place.id)}"]`)?.classList.add('is-selected');
                });
                markers.set(Number(place.id), marker);
                bounds.push(point);
            });

            if (bounds.length === 1) map.setView(bounds[0], 13);
            else if (bounds.length > 1) map.fitBounds(bounds, { padding: [50, 50], maxZoom: 11 });

            document.querySelectorAll('[data-location-card]').forEach((card) => {
                card.addEventListener('click', (event) => {
                    if (event.target.closest('a')) return;
                    const marker = markers.get(Number(card.dataset.locationCard));
                    if (!marker) return;
                    map.setView(marker.getLatLng(), Math.max(map.getZoom(), 12));
                    marker.openPopup();
                });
            });

            window.addEventListener('resize', () => map.invalidateSize());
        } catch (error) {
            console.warn('Heritage farm map could not initialize:', error);
            if (fallback) fallback.hidden = false;
        }
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeFarmMap);
    else initializeFarmMap();
}());
