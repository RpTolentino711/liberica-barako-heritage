@extends('layouts.admin')
@section('title',$article ? 'Edit Article' : 'New Article')
@section('content')
    <div class="panel form-panel"><form action="{{ $article ? route('admin.articles.update',$article) : route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">@csrf @if($article)@method('PUT')@endif
        <label for="title">Article title *</label><input id="title" name="title" required maxlength="160" value="{{ old('title',$article?->title) }}">
        <label for="slug">URL slug (optional)</label><input id="slug" name="slug" maxlength="190" value="{{ old('slug',$article?->slug) }}" placeholder="generated-from-title">
        <div class="two-col"><div><label for="category">Topic *</label><select name="category" id="category" required>@foreach(['heritage'=>'Heritage','botany'=>'About Liberica','cultivation'=>'Cultivation','culture'=>'Coffee Culture','preservation'=>'Preservation'] as $key=>$name)<option value="{{ $key }}" @selected(old('category',$article?->category ?? 'heritage')===$key)>{{ $name }}</option>@endforeach</select></div><div><label for="sort_order">Display order</label><input id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order',$article?->sort_order ?? 0) }}"></div></div>
        <label for="summary">Short introduction *</label><textarea id="summary" name="summary" maxlength="400" rows="3" required>{{ old('summary',$article?->summary) }}</textarea>
        <label for="body">Full article text *</label><textarea id="body" name="body" rows="12" required>{{ old('body',$article?->body) }}</textarea>
        <label for="image">Optional editorial image (JPG, PNG, WebP; max 4 MB)</label><input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
        @if($article?->image_path)<img class="image-preview" src="{{ asset('storage/'.$article->image_path) }}" alt="Current article image">@endif
        <label class="check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$article?->is_published ?? false))> Publish this article</label>
        <div class="form-actions"><button class="primary-button" type="submit">SAVE ARTICLE</button><a class="button-alt" href="{{ route('admin.articles') }}">CANCEL</a></div>
    </form></div>
@endsection
