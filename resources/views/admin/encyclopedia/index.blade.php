@extends('layouts.admin')
@section('title', 'Encyclopedia')
@section('content')
    @if($migrationPending ?? false)
        <div class="alert-error" role="alert">The encyclopedia database update is waiting to run. Ask your hosting administrator to run <code>php artisan migrate --force</code> in the project directory, then refresh this page.</div>
    @else
    <div class="panel-head standalone">
        <p class="lead">Manage the public encyclopedia topics and the entries readers find inside them.</p>
        <a href="{{ route('admin.encyclopedia.topics.new') }}" class="primary-button small">+ NEW TOPIC</a>
    </div>
    <div class="panel table-wrap">
        <table>
            <thead><tr><th>Topic</th><th>Entries</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($topics as $topic)
                <tr>
                    <td><strong><a class="encyclopedia-topic-title-link" href="{{ route('admin.encyclopedia.topics.edit', $topic) }}">{{ $topic->name }}</a></strong><small>{{ $topic->slug }}</small></td>
                    <td><a class="encyclopedia-entry-count" href="{{ route('admin.encyclopedia.entries', $topic) }}">{{ $topic->entries_count }} <span>VIEW</span></a></td>
                    <td><span class="status">{{ $topic->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td>{{ $topic->sort_order }}</td>
                    <td class="actions encyclopedia-admin-actions">
                        <a class="encyclopedia-action-manage" href="{{ route('admin.encyclopedia.entries', $topic) }}">Manage {{ $topic->entries_count }} {{ \Illuminate\Support\Str::plural('entry', $topic->entries_count) }}</a>
                        <a href="{{ route('admin.encyclopedia.topics.edit', $topic) }}">Edit topic</a>
                        <button type="button" class="danger-button" data-confirm-delete data-delete-action="{{ route('admin.encyclopedia.topics.delete', $topic) }}" data-delete-title="Delete {{ $topic->name }}?" data-delete-description="This will permanently delete this topic and all {{ $topic->entries_count }} entries inside it.">Delete</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No encyclopedia topics yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @endif
@endsection
