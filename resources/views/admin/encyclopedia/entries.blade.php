@extends('layouts.admin')
@section('title', $topic->name)
@section('content')
    <div class="panel-head standalone">
        <p class="lead"><strong>{{ $entries->count() }} {{ \Illuminate\Support\Str::plural('entry', $entries->count()) }}</strong> in {{ $topic->name }}. Edit or delete an individual entry below, or add another one.</p>
        <a href="{{ route('admin.encyclopedia.entries.new', $topic) }}" class="primary-button small">+ NEW ENTRY</a>
    </div>
    <p class="encyclopedia-admin-back"><a href="{{ route('admin.encyclopedia') }}">← All encyclopedia topics</a></p>
    <div class="panel table-wrap">
        <table>
            <thead><tr><th>Entry</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($entries as $entry)
                <tr>
                    <td><strong>{{ $entry->title }}</strong><small>{{ $entry->slug }}</small></td>
                    <td><span class="status">{{ $entry->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>{{ $entry->sort_order }}</td>
                    <td class="actions">
                        <a class="encyclopedia-entry-edit" href="{{ route('admin.encyclopedia.entries.edit', [$topic, $entry]) }}">EDIT ENTRY</a>
                        <button type="button" class="danger-button" data-confirm-delete data-delete-action="{{ route('admin.encyclopedia.entries.delete', [$topic, $entry]) }}" data-delete-title="Delete {{ $entry->title }}?" data-delete-description="This encyclopedia entry will be permanently deleted.">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No entries in this topic yet. Add the first one above.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
