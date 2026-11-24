@extends('layouts.landing')

@section('content')
<article class="encyclopedia-article">
    <header class="encyclopedia-article-header">
        <a class="encyclopedia-article-back" href="{{ route('encyclopedia.topic', $topic->slug) }}"><span aria-hidden="true">&#8592;</span> {{ $topic->name }}</a>
        <p class="eyebrow">LIBERICAPEDIA <span aria-hidden="true">/</span> {{ $topic->name }}</p>
        <h1>{{ $entry->title }}</h1>
        <p class="encyclopedia-article-deck">{{ $entry->summary }}</p>
        <div class="encyclopedia-article-byline"><span class="byline-mark" aria-hidden="true">L</span><span>LIBÉRICA <small>LIBERICAPEDIA</small></span><span class="byline-divider" aria-hidden="true"></span><span>{{ $topic->name }}</span></div>
    </header>

    @if($entry->image_path)
        <figure class="encyclopedia-article-figure">
            <img class="encyclopedia-reading-image" src="{{ str_starts_with($entry->image_path, 'assets/') ? asset($entry->image_path) : asset('storage/'.$entry->image_path) }}" alt="" fetchpriority="high">
        </figure>
    @endif

    <div class="encyclopedia-article-layout">
        <aside class="encyclopedia-article-aside">
            <span class="section-number">EXPLORE LIBERICAPEDIA</span>
            <a href="{{ route('encyclopedia.topic', $topic->slug) }}" class="aside-topic">{{ $topic->name }} <span aria-hidden="true">&#8599;</span></a>
            <a href="{{ route('encyclopedia.index') }}" class="aside-all">Browse all topics</a>
        </aside>
        <div class="encyclopedia-reading-body">
            @php($articleSections = \App\Support\EncyclopediaSectionParser::parse($entry->body))
            @php($entrySourceAlreadyShown = false)
            @foreach($articleSections as $section)
                @if($entry->source_url && $section['source_url'] === $entry->source_url)
                    @php($entrySourceAlreadyShown = true)
                @endif
                @if($section['heading'])<h2>{{ $section['heading'] }}</h2>@endif
                @foreach($section['paragraphs'] as $paragraph)
                    <p>{{ trim($paragraph) }}</p>
                @endforeach
                @if($section['source_label'] || $section['source_url'])
                    <aside class="encyclopedia-section-source" aria-label="Section source">
                        <span class="section-number">SECTION SOURCE</span>
                        @if($section['source_url'])
                            <a href="{{ $section['source_url'] }}" target="_blank" rel="noopener noreferrer">{{ $section['source_label'] ?: $section['source_url'] }} <span aria-hidden="true">&#8599;</span></a>
                        @else
                            <span>{{ $section['source_label'] }}</span>
                        @endif
                    </aside>
                @endif
            @endforeach
            @if($entry->source_url && $entry->source_label && !$entrySourceAlreadyShown)
                <aside class="encyclopedia-section-source" aria-label="Article source">
                    <span class="section-number">ARTICLE SOURCE / FURTHER READING</span>
                    <a href="{{ $entry->source_url }}" target="_blank" rel="noopener noreferrer">{{ $entry->source_label }} <span aria-hidden="true">&#8599;</span></a>
                </aside>
            @endif
            @if($entry->references->isNotEmpty())
                <section aria-labelledby="article-references">
                    <h2 id="article-references">References</h2>
                    <ul>
                        @foreach($entry->references as $reference)
                            <li><a href="{{ $reference->pdf_path ? route('encyclopedia.reference.pdf', $reference) : $reference->url }}">{{ $reference->title }}{{ $reference->pdf_path ? ' (PDF)' : '' }}</a></li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </div>
</article>
@endsection
