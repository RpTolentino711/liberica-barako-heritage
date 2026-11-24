<form method="POST" action="{{ $action }}">
    @csrf
    @method('PATCH')
    <input type="hidden" name="archived" value="{{ $archived ? 0 : 1 }}">
    <button type="submit">{{ $archived ? 'Restore to draft' : 'Archive' }}</button>
</form>
