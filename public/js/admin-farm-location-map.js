(() => {
    const element = document.getElementById('farmLocationPickerMap');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    if (!element || !latitudeInput || !longitudeInput || !window.L) return;

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

    map.on('click', (event) => {
        marker.setLatLng(event.latlng);
        updateInputs(event.latlng);
    });
    marker.on('dragend', () => updateInputs(marker.getLatLng()));
    latitudeInput.addEventListener('change', updateMarker);
    longitudeInput.addEventListener('change', updateMarker);
    window.setTimeout(() => map.invalidateSize(), 150);
})();
