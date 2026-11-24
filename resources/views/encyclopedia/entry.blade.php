@extends('layouts.landing')

@section('content')
<article class="encyclopedia-article">
    <header class="encyclopedia-article-header">
        <a class="encyclopedia-article-back" href="{{ route('encyclopedia.topic', $topic->slug) }}"><span aria-hidden="true">←</span> {{ $topic->name }}</a>
        <p class="eyebrow">BATANGAS COFFEE ENCYCLOPEDIA <span aria-hidden="true">/</span> {{ $topic->name }}</p>
        <h1>{{ $entry->title }}</h1>
        <p class="encyclopedia-article-deck">{{ $entry->summary }}</p>
        <div class="encyclopedia-article-byline"><span class="byline-mark" aria-hidden="true">L</span><span>LIBÉRICA <small>HERITAGE ENCYCLOPEDIA</small></span><span class="byline-divider" aria-hidden="true"></span><span>{{ $topic->name }}</span></div>
    </header>

    @if($entry->image_path)
        <figure class="encyclopedia-article-figure">
            <img class="encyclopedia-reading-image" src="{{ str_starts_with($entry->image_path, 'assets/') ? asset($entry->image_path) : asset('storage/'.$entry->image_path) }}" alt="" fetchpriority="high">
        </figure>
    @endif

    <div class="encyclopedia-article-layout">
        <aside class="encyclopedia-article-aside">
            <span class="section-number">EXPLORE THE ENCYCLOPEDIA</span>
            <a href="{{ route('encyclopedia.topic', $topic->slug) }}" class="aside-topic">{{ $topic->name }} <span aria-hidden="true">↗</span></a>
            <a href="{{ route('encyclopedia.index') }}" class="aside-all">Browse all topics</a>
            @if($entry->source_url && $entry->source_label)
                <div class="encyclopedia-source-card" aria-label="Entry source">
                    <span class="section-number">SOURCE / FURTHER READING</span>
                    <a href="{{ $entry->source_url }}" target="_blank" rel="noopener noreferrer">{{ $entry->source_label }} <span aria-hidden="true">↗</span></a>
                </div>
            @endif
        </aside>
        <div class="encyclopedia-reading-body">
            @foreach(preg_split('/\R\s*\R/', trim($entry->body), -1, PREG_SPLIT_NO_EMPTY) as $paragraph)
                <p>{{ trim($paragraph) }}</p>
            @endforeach
        </div>
    </div>
</article>
@endsection
