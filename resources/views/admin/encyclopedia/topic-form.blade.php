@extends('layouts.admin')
@section('title', $topic ? 'Edit Encyclopedia Topic' : 'New Encyclopedia Topic')
@section('content')
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
@endsection
