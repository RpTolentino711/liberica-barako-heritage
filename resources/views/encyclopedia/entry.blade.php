@extends('layouts.landing')

@section('content')
<article class="encyclopedia-reading">
    <a class="encyclopedia-back-link" href="{{ route('encyclopedia.topic', $topic->slug) }}">← {{ $topic->name }}</a>
    <p class="eyebrow gold">BATANGAS COFFEE ENCYCLOPEDIA</p>
    <h1>{{ $entry->title }}</h1>
    <p class="encyclopedia-lead">{{ $entry->summary }}</p>
    @if($entry->image_path)<img class="encyclopedia-reading-image" src="{{ asset('storage/'.$entry->image_path) }}" alt="{{ $entry->title }}">@endif
    <div class="encyclopedia-reading-body">{!! nl2br(e($entry->body)) !!}</div>
</article>
@endsection
