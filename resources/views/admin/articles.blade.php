@extends('layouts.admin')
@section('title','Educational Articles')
@section('content')
    <div class="panel-head standalone"><p class="lead">Manage educational stories, coffee characteristics, and preservation information.</p><a href="{{ route('admin.articles.new') }}" class="primary-button small">+ NEW ARTICLE</a></div>
    <div class="panel table-wrap"><table><thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead><tbody>@forelse($items as $item)<tr><td><strong>{{ $item->title }}</strong><small>{{ $item->slug }}</small></td><td>{{ ucfirst($item->category) }}</td><td><span class="status">{{ $item->is_published ? 'Published' : 'Draft' }}</span></td><td>{{ $item->sort_order }}</td><td class="actions"><a href="{{ route('admin.articles.edit',$item) }}">Edit</a><form method="POST" action="{{ route('admin.articles.delete',$item) }}" onsubmit="return confirm('Delete this article?');">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>@empty<tr><td colspan="5">No articles yet.</td></tr>@endforelse</tbody></table></div>
@endsection
