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
                    <td>{{ $topic->entries_count }}</td>
                    <td><span class="status">{{ $topic->archived_at ? 'Archived' : ($topic->is_published ? 'Published' : 'Draft') }}</span></td>
                    <td>{{ $topic->sort_order }}</td>
                    <td class="actions encyclopedia-admin-actions">
                        <a href="{{ route('admin.encyclopedia.topics.edit', $topic) }}">Edit topic</a>
                        @include('admin.archive-action', ['action' => route('admin.encyclopedia.topics.archive', $topic), 'archived' => $topic->archived_at])
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
