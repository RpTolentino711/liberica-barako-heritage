(() => {
    const element = document.getElementById('farmLocationPickerMap');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const placeNameInput = document.getElementById('place_name');
    const searchButton = document.getElementById('searchFarmLocation');
    const searchStatus = document.getElementById('farmLocationSearchStatus');
    const searchResults = document.getElementById('farmLocationSearchResults');
    if (!element || !latitudeInput || !longitudeInput || !window.L) return;

    if (L.Icon && L.Icon.Default) {
        delete L.Icon.Default.prototype._getIconUrl;
        L.Icon.Default.mergeOptions({
            iconRetinaUrl: '/images/marker-icon-2x.png',
            iconUrl: '/images/marker-icon.png',
            shadowUrl: '/images/marker-shadow.png',
        });
    }

    const initialLatitude = Number(latitudeInput.value) || 13.9;
    const initialLongitude = Number(longitudeInput.value) || 121.1;
    const map = L.map(element, { scrollWheelZoom: false }).setView([initialLatitude, initialLongitude], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    const marker = L.marker([initialLatitude, initialLongitude], { draggable: true }).addTo(map);
    const updateInputs = (point) => {
        latitudeInput.value = point.lat.toFixed(7);
        longitudeInput.value = point.lng.toFixed(7);
    };
    const updateMarker = () => {
        const latitude = Number(latitudeInput.value);
        const longitude = Number(longitudeInput.value);
        if (!Number.isFinite(latitude) || !Number.isFinite(longitude) || Math.abs(latitude) > 90 || Math.abs(longitude) > 180) return;
        const point = [latitude, longitude];
        marker.setLatLng(point);
        map.setView(point, Math.max(map.getZoom(), 12));
    };
    const setPin = (latitude, longitude) => {
        const point = L.latLng(latitude, longitude);
        marker.setLatLng(point);
        updateInputs(point);
        map.setView(point, 15);
    };

    let lastSearchAt = 0;
    let activeSearch = null;
    const searchPlace = async () => {
        if (searchButton.disabled) return;
        const query = placeNameInput?.value.trim();
        if (!query) {
            searchStatus.textContent = 'Enter a barangay, city, or address above before searching.';
            placeNameInput?.focus();
            return;
        }

        searchButton.disabled = true;
        const waitMs = 1000 - (Date.now() - lastSearchAt);
        if (waitMs > 0) await new Promise((resolve) => window.setTimeout(resolve, waitMs));
        lastSearchAt = Date.now();
        activeSearch?.abort();
        const requestController = new AbortController();
        activeSearch = requestController;
        searchResults.hidden = true;
        searchResults.replaceChildren();
        searchStatus.textContent = 'Searching OpenStreetMap…';

        try {
            const params = new URLSearchParams({
                q: query,
                format: 'jsonv2',
                limit: '5',
                countrycodes: 'ph',
                'accept-language': 'en',
            });
            const response = await fetch(`https://nominatim.openstreetmap.org/search?${params}`, {
                headers: { Accept: 'application/json' },
                signal: requestController.signal,
            });
            if (!response.ok) throw new Error('The map search service is unavailable right now.');
            const matches = await response.json();
            if (!matches.length) {
                searchStatus.textContent = 'No matching place found in the Philippines. Try adding the barangay, city, or province.';
                return;
            }

            const chooseMatch = (match) => {
                setPin(Number(match.lat), Number(match.lon));
                searchStatus.textContent = `Pin moved to: ${match.display_name}`;
                searchResults.hidden = true;
            };
            chooseMatch(matches[0]);
            if (matches.length > 1) {
                searchStatus.textContent = `Pin moved to the best match. Choose another result if needed (${matches.length} matches):`;
                matches.forEach((match, index) => {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'farm-location-search-result';
                    option.textContent = `${index + 1}. ${match.display_name}`;
                    option.addEventListener('click', () => chooseMatch(match));
                    searchResults.append(option);
                });
                searchResults.hidden = false;
            }
        } catch (error) {
            if (error.name !== 'AbortError') searchStatus.textContent = error.message || 'Map search failed. Please try again.';
        } finally {
            if (activeSearch === requestController) searchButton.disabled = false;
        }
    };

    searchButton?.addEventListener('click', searchPlace);
    placeNameInput?.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            searchPlace();
        }
    });

    map.on('click', (event) => {
        marker.setLatLng(event.latlng);
        updateInputs(event.latlng);
    });
    marker.on('dragend', () => updateInputs(marker.getLatLng()));
    latitudeInput.addEventListener('change', updateMarker);
    longitudeInput.addEventListener('change', updateMarker);
    window.setTimeout(() => map.invalidateSize(), 150);
})();
