@extends('layouts.admin')
@section('title', $step ? 'Edit Bean to Cup Step' : 'New Bean to Cup Step')
@section('content')
    <p class="encyclopedia-admin-back"><a href="{{ route('admin.journey.index') }}">&#8592; All Bean to Cup steps</a></p>
    <div class="panel form-panel">
        <form action="{{ $step ? route('admin.journey.update', $step) : route('admin.journey.store') }}" method="POST" enctype="multipart/form-data">
            @csrf @if($step) @method('PUT') @endif
            <label for="title">Step title *</label>
            <input id="title" name="title" required maxlength="140" value="{{ old('title', $step?->title) }}" placeholder="For example: Drying and sorting">

            <label for="description">Step description *</label>
            <textarea id="description" name="description" rows="5" maxlength="3000" required placeholder="Explain what happens at this stage and why it matters.">{{ old('description', $step?->description) }}</textarea>

            <label for="media">Photo or video {{ $step ? '(optional; keep current media if unchanged)' : '*' }}</label>
            <input id="media" name="media" type="file" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm,video/quicktime" @required(!$step)>
            <p class="journey-admin-help">Images: JPG, PNG, WebP. Videos: MP4, WebM, MOV. Maximum upload size: 50 MB.</p>
            @if($step?->media_path)
                <div class="journey-admin-current-media">
                    @php($mediaUrl = str_starts_with($step->media_path, 'assets/') ? asset($step->media_path) : asset('storage/'.$step->media_path))
                    @if($step->media_type === 'video')
                        <video controls preload="metadata"><source src="{{ $mediaUrl }}"></video>
                    @else
                        <img src="{{ $mediaUrl }}" alt="{{ $step->media_alt ?: $step->title }}">
                    @endif
                </div>
            @endif

            <label for="media_alt">Media description for accessibility (optional)</label>
            <input id="media_alt" name="media_alt" maxlength="240" value="{{ old('media_alt', $step?->media_alt) }}" placeholder="Describe what the photo shows">

            <label for="sort_order">Display order</label>
            <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $step?->sort_order ?? 0) }}">

            <label class="check"><input name="is_published" type="checkbox" value="1" @checked(old('is_published', $step?->is_published ?? true))> Show this step in the public carousel</label>
            <div class="form-actions">
                <button class="primary-button" type="submit">SAVE STEP</button>
                <a class="button-alt" href="{{ route('admin.journey.index') }}">CANCEL</a>
            </div>
        </form>
    </div>
@endsection
