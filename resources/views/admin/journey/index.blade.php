@extends('layouts.admin')
@section('title', 'Bean to Cup')
@section('content')
    <div class="panel-head standalone">
        <p class="lead">Manage the steps visitors see in the Bean to Cup carousel. Add as many steps as you need, with an image or video for each.</p>
        <a href="{{ route('admin.journey.create') }}" class="primary-button small">+ NEW STEP</a>
    </div>
    <p class="journey-order-status" id="journeyOrderStatus" aria-live="polite">Drag the handle to reorder steps. Use the arrow controls on touch devices.</p>
    <div class="panel table-wrap">
        <table>
            <thead><tr><th>Step</th><th>Media</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
            <tbody id="journeyStepRows" data-reorder-url="{{ route('admin.journey.reorder') }}">
            @forelse($steps as $step)
                <tr data-step-row data-step-id="{{ $step->id }}">
                    <td><div class="journey-step-row-title"><button type="button" class="journey-drag-handle" data-drag-handle draggable="true" aria-label="Drag {{ $step->title }} to reorder" title="Drag to reorder">&#10247;</button><div><strong>{{ $step->title }}</strong><small>{{ \Illuminate\Support\Str::limit($step->description, 110) }}</small></div></div></td>
                    <td>{{ ucfirst($step->media_type) }}</td>
                    <td><span class="status">{{ $step->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td><div class="journey-order-controls"><span data-step-order>{{ $step->sort_order }}</span><button type="button" data-move-step="up" aria-label="Move {{ $step->title }} up">&#8593;</button><button type="button" data-move-step="down" aria-label="Move {{ $step->title }} down">&#8595;</button></div></td>
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
    <script src="{{ asset('js/admin-journey-sort.js') }}?v=1" defer></script>
@endsection
