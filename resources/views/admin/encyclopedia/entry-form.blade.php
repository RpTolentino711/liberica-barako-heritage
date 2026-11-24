@extends('layouts.admin')
@section('title', $entry ? 'Edit Encyclopedia Entry' : 'New Encyclopedia Entry')
@section('content')
    <p class="encyclopedia-admin-back"><a href="{{ route('admin.encyclopedia.entries', $topic) }}">← {{ $topic->name }}</a></p>
    <div class="panel form-panel">
        <form action="{{ $entry ? route('admin.encyclopedia.entries.update', [$topic, $entry]) : route('admin.encyclopedia.entries.store', $topic) }}" method="POST" enctype="multipart/form-data">
            @csrf @if($entry) @method('PUT') @endif
            <label for="title">Entry title *</label>
            <input id="title" name="title" required maxlength="180" value="{{ old('title', $entry?->title) }}">
            <label for="slug">URL slug (optional)</label>
            <input id="slug" name="slug" maxlength="190" value="{{ old('slug', $entry?->slug) }}" placeholder="generated-from-entry-title">
            <label for="summary">Short introduction *</label>
            <textarea id="summary" name="summary" maxlength="500" rows="3" required>{{ old('summary', $entry?->summary) }}</textarea>
            <label for="body">Entry content *</label>
            <textarea id="body" name="body" maxlength="30000" rows="14" required>{{ old('body', $entry?->body) }}</textarea>
            <div class="two-col">
                <div><label for="source_label">Source name</label><input id="source_label" name="source_label" maxlength="240" value="{{ old('source_label', $entry?->source_label) }}" placeholder="Department of Agriculture"></div>
                <div><label for="source_url">Source link (HTTPS)</label><input id="source_url" type="url" name="source_url" maxlength="2048" value="{{ old('source_url', $entry?->source_url) }}" placeholder="https://"></div>
            </div>
            <label for="image">Optional image (JPG, PNG, WebP; max 4 MB)</label>
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            @if($entry?->image_path)<img class="image-preview" src="{{ asset('storage/'.$entry->image_path) }}" alt="Current entry image">@endif
            <label for="sort_order">Display order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $entry?->sort_order ?? 0) }}">
            <label class="check"><input name="is_published" type="checkbox" value="1" @checked(old('is_published', $entry?->is_published ?? false))> Publish this entry</label>
            <div class="form-actions"><button class="primary-button" type="submit">SAVE ENTRY</button><a class="button-alt" href="{{ route('admin.encyclopedia.entries', $topic) }}">CANCEL</a></div>
        </form>
    </div>
@endsection
