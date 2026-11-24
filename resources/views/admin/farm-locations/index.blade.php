@extends('layouts.admin')
@section('title', 'Farm Map Locations')
@section('content')
    <div class="panel-head standalone">
        <p class="lead">Manage the named places shown on the public Batangas map. Select a point on the map, add a photo, and write a short description for visitors.</p>
        <a href="{{ route('admin.farm-locations.create') }}" class="primary-button small">+ NEW LOCATION</a>
    </div>
    <div class="panel table-wrap">
        <table>
            <thead><tr><th>Place</th><th>Photo</th><th>Coordinates</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($locations as $location)
                <tr>
                    <td><strong>{{ $location->name }}</strong><small>{{ $location->place_name }}</small></td>
                    <td>{{ $location->photo_path ? 'Photo added' : 'No photo' }}</td>
                    <td>{{ number_format($location->latitude, 5) }}, {{ number_format($location->longitude, 5) }}</td>
                    <td><span class="status">{{ $location->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td class="actions">
                        <a href="{{ route('admin.farm-locations.edit', $location) }}">Edit</a>
                        <button type="button" class="danger-button" data-confirm-delete data-delete-action="{{ route('admin.farm-locations.delete', $location) }}" data-delete-title="Delete {{ $location->name }}?" data-delete-description="This map pin and its uploaded photo will be permanently deleted.">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No map locations yet. Add your first location above.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
