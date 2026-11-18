@extends('layouts.admin')
@section('title','Visitor Messages')
@section('content')
    <p class="lead">Read and organize contact messages. Replies are handled outside this demo application.</p>
    <div class="panel">@forelse($items as $item)<div class="message-item"><div class="message-head"><strong>{{ $item->name }}</strong><small>{{ $item->email }} · {{ $item->created_at->format('M j, Y h:i A') }}</small></div><p>{{ $item->message }}</p><form method="POST" action="{{ route('admin.messages.update',$item) }}">@csrf @method('PATCH')<label for="status-{{ $item->id }}">Status</label><select id="status-{{ $item->id }}" name="status">@foreach(['new','read','replied','archived'] as $s)<option value="{{ $s }}" @selected($item->status===$s)>{{ ucfirst($s) }}</option>@endforeach</select><button class="button-alt" type="submit">UPDATE</button></form></div>@empty<p class="muted">No messages received yet.</p>@endforelse</div><div class="pagination">{{ $items->links() }}</div>
@endsection
