@php
    $savedReferences = $entry?->references ?? collect();
    $referenceRows = old('references', old('reference_editor') ? [] : $savedReferences->map(fn ($ref) => [
        'id' => $ref->id, 'title' => $ref->title, 'url' => $ref->url, 'type' => $ref->pdf_path ? 'pdf' : 'web',
    ])->all());
    $savedPdfIds = $savedReferences->whereNotNull('pdf_path')->pluck('id')->all();
@endphp
<fieldset id="entry-references">
    <legend>References</legend>
    <input type="hidden" name="reference_editor" value="1">
    @foreach((array) old('removed_references', []) as $removedId)
        @if(is_scalar($removedId))<input type="hidden" name="removed_references[]" value="{{ $removedId }}">@endif
    @endforeach
    <div data-reference-list></div>
    <button type="button" class="button-alt" data-add-reference>Add reference</button>
    <template>
        <fieldset>
            <legend>Source</legend>
            <label>Source title<input data-field="title" maxlength="240" required></label>
            <label>Source type<select data-field="type"><option value="web">Web link</option><option value="pdf">Upload PDF</option></select></label>
            <label>Web address<input data-field="url" type="url" maxlength="2048"></label>
            <label>PDF file (up to 10 MB)<input data-field="pdf" type="file" accept="application/pdf,.pdf"><small data-current-file>Current PDF will be kept unless you choose a replacement.</small></label>
            <button type="button" class="button-alt" data-remove-reference>Remove reference</button>
        </fieldset>
    </template>
</fieldset>
<script>
(() => {
    const editor = document.getElementById('entry-references');
    const rows = editor.querySelector('[data-reference-list]');
    const savedPdfIds = @js($savedPdfIds);
    let nextIndex = 0;

    function addRow(values = {}) {
        const row = editor.querySelector('template').content.firstElementChild.cloneNode(true);
        const prefix = 'references[' + nextIndex++ + ']';
        for (const input of row.querySelectorAll('[data-field]')) {
            const field = input.dataset.field;
            input.name = prefix + '[' + field + ']';
            if (field !== 'pdf' && values[field] != null) input.value = values[field];
        }
        if (values.id) {
            const id = document.createElement('input');
            id.type = 'hidden';
            id.name = prefix + '[id]';
            id.value = values.id;
            row.append(id);
        }
        const type = row.querySelector('select');
        const url = row.querySelector('[data-field=url]');
        const pdf = row.querySelector('[data-field=pdf]');
        const hasPdf = savedPdfIds.includes(Number(values.id));
        function updateType() {
            const usePdf = type.value === 'pdf';
            pdf.disabled = !usePdf;
            pdf.required = usePdf && !hasPdf;
            pdf.parentElement.hidden = !usePdf;
            url.disabled = usePdf;
            url.required = !usePdf;
            url.parentElement.hidden = usePdf;
            row.querySelector('[data-current-file]').hidden = !hasPdf;
        }
        type.addEventListener('change', updateType);
        row.querySelector('[data-remove-reference]').addEventListener('click', () => {
            if (values.id) {
                const removed = document.createElement('input');
                removed.type = 'hidden';
                removed.name = 'removed_references[]';
                removed.value = values.id;
                editor.append(removed);
            }
            row.remove();
            editor.querySelector('[data-add-reference]').focus();
            editor.dispatchEvent(new Event('change', { bubbles: true }));
        });
        updateType();
        rows.append(row);
        return row;
    }
    Object.values(@js($referenceRows) || {}).forEach(values => { if (values) addRow(values); });
    editor.querySelector('[data-add-reference]').addEventListener('click', () => {
        addRow().querySelector('input').focus();
        editor.dispatchEvent(new Event('change', { bubbles: true }));
    });
})();
</script>
