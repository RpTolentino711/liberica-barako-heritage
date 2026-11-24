@extends('layouts.landing')

@section('content')
<article class="encyclopedia-reading">
    <a class="encyclopedia-back-link" href="{{ route('encyclopedia.topic', $topic->slug) }}">← {{ $topic->name }}</a>
    <p class="eyebrow gold">BATANGAS COFFEE ENCYCLOPEDIA</p>
    <h1>{{ $entry->title }}</h1>
    <p class="encyclopedia-lead">{{ $entry->summary }}</p>
    @if($entry->image_path)<img class="encyclopedia-reading-image" src="{{ asset('storage/'.$entry->image_path) }}" alt="{{ $entry->title }}">@endif
    <div class="encyclopedia-reading-body">
        @foreach(preg_split('/\R\s*\R/', trim($entry->body), -1, PREG_SPLIT_NO_EMPTY) as $paragraph)
            <p>{{ trim($paragraph) }}</p>
        @endforeach
    </div>
    @if($entry->source_url && $entry->source_label)
        <aside class="encyclopedia-source-card" aria-label="Entry source">
            <span class="section-number">SOURCE / FURTHER READING</span>
            <a href="{{ $entry->source_url }}" target="_blank" rel="noopener noreferrer">{{ $entry->source_label }} <span aria-hidden="true">↗</span></a>
        </aside>
    @endif
</article>
@endsection
