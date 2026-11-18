@extends('layouts.admin')
@section('title','Dashboard')
@section('content')
    <p class="lead">Your overview of educational content and visitors' messages.</p>
    <div class="stats"><div><small>ALL ARTICLES</small><strong>{{ $articleCount }}</strong></div><div><small>PUBLISHED ARTICLES</small><strong>{{ $publishedCount }}</strong></div><div><small>GALLERY PHOTOS</small><strong>{{ $galleryCount }}</strong></div><div><small>NEW MESSAGES</small><strong>{{ $messageCount }}</strong></div></div>
    <div class="panel"><div class="panel-head"><h2>Recent visitor messages</h2><a class="button-alt" href="{{ route('admin.messages') }}">ALL MESSAGES ↗</a></div>@forelse($recentMessages as $message)<div class="list-row"><div><strong>{{ $message->name }}</strong><p>{{ Str::limit($message->message,110) }}</p></div><small>{{ $message->created_at->format('M j, Y') }}</small></div>@empty<p class="muted">No messages yet.</p>@endforelse</div>
@endsection
