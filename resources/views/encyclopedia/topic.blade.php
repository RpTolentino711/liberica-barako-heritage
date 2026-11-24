@extends('layouts.landing')

@section('content')
<section class="encyclopedia-hero">
    <a class="encyclopedia-back-link" href="{{ route('encyclopedia.index') }}">← ALL LIBERICAPEDIA TOPICS</a>
    <p class="eyebrow gold">LIBERICAPEDIA</p>
    <h1>{{ $topic->name }}</h1>
    @if($topic->description)<p class="encyclopedia-lead">{{ $topic->description }}</p>@endif
</section>
<section class="encyclopedia-topic-content section-shell">
    <div class="encyclopedia-topic-tabs" aria-label="Libericapedia topics">
        @foreach($navTopics as $navTopic)
            <a href="{{ route('encyclopedia.topic', $navTopic->slug) }}" @if($navTopic->id === $topic->id) aria-current="page" @endif>{{ $navTopic->name }}</a>
        @endforeach
    </div>
    <div class="encyclopedia-entry-grid">
        @forelse($entries as $entry)
            <a class="encyclopedia-entry-card" href="{{ route('encyclopedia.entry', [$topic->slug, $entry->slug]) }}">
                @if($entry->image_path)<img src="{{ str_starts_with($entry->image_path, 'assets/') ? asset($entry->image_path) : asset('storage/'.$entry->image_path) }}" alt="" loading="lazy">@endif
                <span class="section-number">ENTRY {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <h2>{{ $entry->title }}</h2>
                <p>{{ $entry->summary }}</p>
                <span class="encyclopedia-card-link">READ ENTRY <span aria-hidden="true">↗</span></span>
            </a>
        @empty
            <p>There are no published entries in this topic yet.</p>
        @endforelse
    </div>
</section>
@endsection
