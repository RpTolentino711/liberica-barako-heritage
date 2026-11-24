@extends('layouts.admin')
@section('title', $topic ? 'Edit Encyclopedia Topic' : 'New Encyclopedia Topic')
@section('content')
    @if($topic)<p class="encyclopedia-admin-back"><a href="{{ route('admin.encyclopedia') }}">← All encyclopedia topics</a></p>@endif
    <div class="panel form-panel">
        <form action="{{ $topic ? route('admin.encyclopedia.topics.update', $topic) : route('admin.encyclopedia.topics.store') }}" method="POST">
            @csrf @if($topic) @method('PUT') @endif
            <label for="name">Topic name *</label>
            <input id="name" name="name" required maxlength="120" value="{{ old('name', $topic?->name) }}" placeholder="History of Philippine Coffee">
            <label for="slug">URL slug (optional)</label>
            <input id="slug" name="slug" maxlength="140" value="{{ old('slug', $topic?->slug) }}" placeholder="generated-from-topic-name">
            <label for="description">Short description</label>
            <textarea id="description" name="description" maxlength="500" rows="3">{{ old('description', $topic?->description) }}</textarea>
            <label for="sort_order">Display order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $topic?->sort_order ?? 0) }}">
            <label class="check"><input name="is_published" type="checkbox" value="1" @checked(old('is_published', $topic?->is_published ?? true))> Show this topic on the public website</label>
            <div class="form-actions"><button class="primary-button" type="submit">SAVE TOPIC</button><a class="button-alt" href="{{ route('admin.encyclopedia') }}">CANCEL</a></div>
        </form>
    </div>
    @if($topic)
        <div class="panel-head standalone encyclopedia-topic-entries-head">
            <p class="lead"><strong>{{ $entries->count() }} {{ \Illuminate\Support\Str::plural('entry', $entries->count()) }}</strong> in {{ $topic->name }}. These are the articles readers see under this topic.</p>
            <a href="{{ route('admin.encyclopedia.entries.new', $topic) }}" class="primary-button small">+ NEW ENTRY</a>
        </div>
        <div class="panel table-wrap">
            <table>
                <thead><tr><th>Entry and introduction</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
                <tbody>
                @forelse($entries as $entry)
                    <tr>
                        <td><strong>{{ $entry->title }}</strong><small>{{ $entry->summary }}</small></td>
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
    @endif
@endsection
