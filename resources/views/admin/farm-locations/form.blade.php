@extends('layouts.admin')
@section('title', $location ? 'Edit Map Location' : 'New Map Location')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <p class="encyclopedia-admin-back"><a href="{{ route('admin.farm-locations.index') }}">&#8592; All map locations</a></p>
    <div class="panel form-panel">
        <form action="{{ $location ? route('admin.farm-locations.update', $location) : route('admin.farm-locations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if($location) @method('PUT') @endif
            <label for="name">Place name *</label>
            <input id="name" name="name" maxlength="160" required value="{{ old('name', $location?->name) }}" placeholder="For example: Katy’s Farm">

            <label for="place_name">Barangay / city / province *</label>
            <input id="place_name" name="place_name" maxlength="220" required value="{{ old('place_name', $location?->place_name) }}" placeholder="Tipakan, Lipa City, Batangas">

            <label for="location_type">Location type (optional)</label>
            <input id="location_type" name="location_type" maxlength="120" value="{{ old('location_type', $location?->location_type) }}" placeholder="Cultivation, nursery, research site">

            <label for="description">Short description *</label>
            <textarea id="description" name="description" rows="4" maxlength="1200" required placeholder="Give visitors a brief, informative description of this place.">{{ old('description', $location?->description) }}</textarea>

            <label>Pin the place on the map *</label>
            <div class="farm-location-search-row">
                <p class="farm-location-map-help">Search the address above to move the pin, then click the map or drag the marker to fine-tune it. You can also enter coordinates below.</p>
                <button class="button-alt" type="button" id="searchFarmLocation">FIND ON MAP</button>
            </div>
            <p class="farm-location-search-status" id="farmLocationSearchStatus" role="status" aria-live="polite">Search results are provided by OpenStreetMap contributors.</p>
            <div id="farmLocationSearchResults" class="farm-location-search-results" hidden></div>
            <div id="farmLocationPickerMap" class="farm-location-picker-map" role="application" aria-label="Map location picker"></div>
            <div class="two-col farm-location-coordinates">
                <div><label for="latitude">Latitude *</label><input id="latitude" name="latitude" type="number" step="any" min="-90" max="90" required value="{{ old('latitude', $location?->latitude ?? 13.9) }}"></div>
                <div><label for="longitude">Longitude *</label><input id="longitude" name="longitude" type="number" step="any" min="-180" max="180" required value="{{ old('longitude', $location?->longitude ?? 121.1) }}"></div>
            </div>

            <label for="photo">Location photo {{ $location ? '(optional if keeping the current photo)' : '*' }}</label>
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" @required(!$location)>
            <p class="journey-admin-help">JPG, PNG, or WebP; maximum 4 MB.</p>
            @if($location?->photo_path)
                @php($photoUrl = str_starts_with($location->photo_path, 'assets/') ? asset($location->photo_path) : asset('storage/'.$location->photo_path))
                <div class="farm-location-current-photo"><img src="{{ $photoUrl }}" alt="{{ $location->photo_alt ?: $location->name }}"></div>
            @endif

            <label for="photo_alt">Photo description for accessibility (optional)</label>
            <input id="photo_alt" name="photo_alt" maxlength="240" value="{{ old('photo_alt', $location?->photo_alt) }}" placeholder="Describe the location photo">

            <label for="source_url">Research / project source URL (optional)</label>
            <input id="source_url" name="source_url" type="url" maxlength="2048" value="{{ old('source_url', $location?->source_url) }}" placeholder="https://">

            <label for="sort_order">Display order</label>
            <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $location?->sort_order ?? 0) }}">

            <label class="check"><input name="is_published" type="checkbox" value="1" @checked(old('is_published', $location?->is_published ?? true))> Show this location on the public map</label>
            <div class="form-actions">
                <button class="primary-button" type="submit">SAVE LOCATION</button>
                <a class="button-alt" href="{{ route('admin.farm-locations.index') }}">CANCEL</a>
            </div>
        </form>
    </div>
    <script defer src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script defer src="{{ asset('js/admin-farm-location-map.js') }}?v=2"></script>
@endsection
