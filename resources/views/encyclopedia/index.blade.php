@extends('layouts.landing')

@section('content')
<section class="encyclopedia-hero">
    <p class="section-number">LIBÉRICA / KNOWLEDGE LIBRARY</p>
    <p class="eyebrow gold">EXPLORE BATANGAS COFFEE HERITAGE</p>
    <h1>The <em>Encyclopedia.</em></h1>
    <p class="encyclopedia-lead">Explore the history, science, culture, and plant health behind Philippine coffee, with Batangas and Liberica at the heart of the collection.</p>
</section>
<section class="encyclopedia-list section-shell" aria-label="Encyclopedia topics">
    @forelse($topics as $topic)
        <a class="encyclopedia-topic-card" href="{{ route('encyclopedia.topic', $topic->slug) }}">
            <span class="section-number">TOPIC {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
            <h2>{{ $topic->name }}</h2>
            <p>{{ $topic->description ?: 'Explore articles in this encyclopedia topic.' }}</p>
            <span class="encyclopedia-card-link">EXPLORE TOPIC <span aria-hidden="true">↗</span></span>
        </a>
    @empty
        <p>The encyclopedia is being organized. Please check back soon.</p>
    @endforelse
</section>
@endsection
