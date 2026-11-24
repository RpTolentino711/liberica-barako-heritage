@extends('layouts.admin')
@section('title', 'Bean to Cup')
@section('content')
    <div class="panel-head standalone">
        <p class="lead">Manage the steps visitors see in the Bean to Cup carousel. Add as many steps as you need, with an image or video for each.</p>
        <a href="{{ route('admin.journey.create') }}" class="primary-button small">+ NEW STEP</a>
    </div>
    <div class="panel table-wrap">
        <table>
            <thead><tr><th>Step</th><th>Media</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($steps as $step)
                <tr>
                    <td><strong>{{ $step->title }}</strong><small>{{ \Illuminate\Support\Str::limit($step->description, 110) }}</small></td>
                    <td>{{ ucfirst($step->media_type) }}</td>
                    <td><span class="status">{{ $step->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>{{ $step->sort_order }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.journey.edit', $step) }}">Edit</a>
                        <button type="button" class="danger-button" data-confirm-delete data-delete-action="{{ route('admin.journey.delete', $step) }}" data-delete-title="Delete {{ $step->title }}?" data-delete-description="This Bean to Cup step and its uploaded media will be permanently deleted.">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No steps yet. Add the first Bean to Cup step above.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
