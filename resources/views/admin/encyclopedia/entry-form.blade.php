@extends('layouts.admin')
@section('title', $entry ? 'Edit Encyclopedia Entry' : 'New Encyclopedia Entry')
@section('content')
    <p class="encyclopedia-admin-back"><a href="{{ route('admin.encyclopedia.entries', $topic) }}">← {{ $topic->name }}</a></p>
    <div class="panel form-panel">
        <form action="{{ $entry ? route('admin.encyclopedia.entries.update', [$topic, $entry]) : route('admin.encyclopedia.entries.store', $topic) }}" method="POST" enctype="multipart/form-data">
            @csrf @if($entry) @method('PUT') @endif
            <label for="title">Entry title *</label>
            <input id="title" name="title" required maxlength="180" value="{{ old('title', $entry?->title) }}">
            <label for="slug">URL slug (optional)</label>
            <input id="slug" name="slug" maxlength="190" value="{{ old('slug', $entry?->slug) }}" placeholder="generated-from-entry-title">
            <label for="summary">Short introduction *</label>
            <textarea id="summary" name="summary" maxlength="500" rows="3" required>{{ old('summary', $entry?->summary) }}</textarea>
            <label for="body">Entry content * (up to 30,000 characters)</label>
            <div id="encyclopediaSections" class="encyclopedia-sections" aria-live="polite"></div>
            <textarea id="body" name="body" maxlength="30000" rows="14" required>{{ old('body', $entry?->body) }}</textarea>
            <div class="encyclopedia-editor-actions">
                <button class="button-alt" id="addEncyclopediaSection" type="button">+ ADD MORE</button>
                <p class="encyclopedia-editor-note">The article source below applies to the main content. Each additional section can have its own optional source name and URL, shown beneath that section. Save Entry after removing a section; refreshing before saving restores the saved section.</p>
            </div>
            <div class="two-col">
                <div><label for="source_label">Source name</label><input id="source_label" name="source_label" maxlength="240" value="{{ old('source_label', $entry?->source_label) }}" placeholder="Department of Agriculture"></div>
                <div><label for="source_url">Source link (HTTPS)</label><input id="source_url" type="url" name="source_url" maxlength="2048" value="{{ old('source_url', $entry?->source_url) }}" placeholder="https://"></div>
            </div>
            <label for="image">Optional image (JPG, PNG, WebP; max 4 MB)</label>
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            @if($entry?->image_path)
                <img class="image-preview" src="{{ str_starts_with($entry->image_path, 'assets/') ? asset($entry->image_path) : asset('storage/'.$entry->image_path) }}" alt="Current entry image">
                <label class="check"><input name="remove_image" type="checkbox" value="1"> Remove current image</label>
            @endif
            <label for="sort_order">Display order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $entry?->sort_order ?? 0) }}">
            <label class="check"><input name="is_published" type="checkbox" value="1" @checked(old('is_published', $entry?->is_published ?? false))> Publish this entry</label>
            <div class="form-actions"><button class="primary-button" type="submit">SAVE ENTRY</button><a class="button-alt" href="{{ route('admin.encyclopedia.entries', $topic) }}">CANCEL</a></div>
        </form>
    </div>
    @if($entry?->image_path)
        <dialog id="removeEntryImageDialog" class="delete-confirm-dialog" aria-labelledby="removeEntryImageTitle" aria-describedby="removeEntryImageDescription">
            <div class="delete-confirm-content">
                <div class="logout-warning-icon" aria-hidden="true">!</div>
                <p class="logout-dialog-eyebrow">PLEASE CONFIRM</p>
                <h2 id="removeEntryImageTitle">Remove this image?</h2>
                <p id="removeEntryImageDescription">The image will be removed when you save this entry. You can choose a replacement image before saving.</p>
                <div class="logout-dialog-actions">
                    <button type="button" class="logout-cancel" id="cancelRemoveEntryImage">CANCEL</button>
                    <button type="button" class="delete-confirm-button" id="confirmRemoveEntryImage">YES, REMOVE IMAGE</button>
                </div>
            </div>
        </dialog>
    @endif
    <script>
        (() => {
            const editor = document.getElementById('encyclopediaSections');
            const fallback = document.getElementById('body');
            const addButton = document.getElementById('addEncyclopediaSection');
            if (!editor || !fallback || !addButton) return;
            let hasUnsavedChanges = false;
            const markUnsaved = () => { hasUnsavedChanges = true; };

            const parseSections = (value) => {
                const sections = [];
                let heading = '';
                let content = [];
                let sourceLabel = '';
                let sourceUrl = '';
                let hasHeading = false;
                const saveCurrent = () => {
                    if (content.join('\n').trim() || hasHeading || sections.length === 0) {
                        sections.push({ heading, content: content.join('\n').trim(), sourceLabel, sourceUrl });
                    }
                };
                for (const line of value.split(/\r?\n/)) {
                    const trimmedLine = line.trim();
                    if (line.startsWith('## ')) {
                        if (content.join('\n').trim() || hasHeading) saveCurrent();
                        heading = line.slice(3).trim();
                        content = [];
                        sourceLabel = '';
                        sourceUrl = '';
                        hasHeading = true;
                    } else if (/^@+ENCYCLOPEDIA_SOURCE:/.test(trimmedLine)) {
                        const marker = trimmedLine.match(/^@+ENCYCLOPEDIA_SOURCE:([A-Za-z0-9+/=]+)@+$/);
                        if (marker) {
                            try {
                                const binary = atob(marker[1]);
                                const bytes = Uint8Array.from(binary, (character) => character.charCodeAt(0));
                                const source = JSON.parse(new TextDecoder().decode(bytes));
                                sourceLabel = source.label || '';
                                sourceUrl = source.url || '';
                                if (/^https?:\/\/(?:www\.)?google\.com\/search\?/i.test(sourceUrl)) sourceUrl = '';
                            } catch (_) {}
                        }
                    } else {
                        content.push(line);
                    }
                }
                saveCurrent();
                return sections;
            };

            const sourceMarker = (label, url) => {
                if (!label && !url) return '';
                const bytes = new TextEncoder().encode(JSON.stringify({ label, url }));
                let binary = '';
                bytes.forEach((byte) => { binary += String.fromCharCode(byte); });
                return `@@ENCYCLOPEDIA_SOURCE:${btoa(binary)}@@`;
            };

            const syncBody = () => {
                const values = [...editor.querySelectorAll('.encyclopedia-content-section')].map((section) => {
                    const heading = section.querySelector('[data-section-heading]').value.trim();
                    const content = section.querySelector('[data-section-content]').value
                        .split(/\r?\n/)
                        .filter((line) => !/^@+ENCYCLOPEDIA_SOURCE:/.test(line.trim()))
                        .join('\n')
                        .trim();
                    const sourceLabel = section.querySelector('[data-section-source-label]')?.value.trim() || '';
                    const sourceUrl = section.querySelector('[data-section-source-url]')?.value.trim() || '';
                    if (!heading && !content && !sourceLabel && !sourceUrl) return '';
                    return [heading ? `## ${heading}` : '', sourceMarker(sourceLabel, sourceUrl), content].filter(Boolean).join('\n\n');
                }).filter(Boolean);
                fallback.value = values.join('\n\n');
            };

            const addSection = ({ heading = '', content = '', sourceLabel = '', sourceUrl = '' } = {}, removable = true) => {
                const section = document.createElement('section');
                section.className = 'encyclopedia-content-section';

                const sectionHead = document.createElement('div');
                sectionHead.className = 'encyclopedia-content-section-head';
                const title = document.createElement('strong');
                title.textContent = editor.children.length ? `ADDITIONAL SECTION ${String(editor.children.length + 1).padStart(2, '0')}` : 'MAIN CONTENT';
                sectionHead.append(title);

                if (removable) {
                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'encyclopedia-section-remove';
                    remove.textContent = 'Remove section';
                    remove.addEventListener('click', () => { section.remove(); syncBody(); markUnsaved(); });
                    sectionHead.append(remove);
                }

                const headingLabel = document.createElement('label');
                headingLabel.textContent = 'Section heading (optional)';
                const headingInput = document.createElement('input');
                headingInput.type = 'text';
                headingInput.maxLength = 180;
                headingInput.placeholder = 'For example: How the coffee trade grew';
                headingInput.value = heading;
                headingInput.dataset.sectionHeading = '';
                headingLabel.append(headingInput);

                const contentLabel = document.createElement('label');
                contentLabel.textContent = 'Section text';
                const contentInput = document.createElement('textarea');
                contentInput.rows = 7;
                contentInput.maxLength = 30000;
                contentInput.required = editor.children.length === 0;
                contentInput.value = content;
                contentInput.dataset.sectionContent = '';
                contentLabel.append(contentInput);

                section.append(sectionHead, headingLabel, contentLabel);
                if (removable) {
                    const sourceLabelField = document.createElement('label');
                    sourceLabelField.textContent = 'Source name (optional)';
                    const sourceLabelInput = document.createElement('input');
                    sourceLabelInput.type = 'text';
                    sourceLabelInput.maxLength = 240;
                    sourceLabelInput.placeholder = 'For example: City Government of Lipa';
                    sourceLabelInput.autocomplete = 'off';
                    sourceLabelInput.value = sourceLabel;
                    sourceLabelInput.dataset.sectionSourceLabel = '';
                    sourceLabelField.append(sourceLabelInput);

                    const sourceUrlField = document.createElement('label');
                    sourceUrlField.textContent = 'Source URL (optional)';
                    const sourceUrlInput = document.createElement('input');
                    sourceUrlInput.type = 'url';
                    sourceUrlInput.maxLength = 2048;
                    sourceUrlInput.placeholder = 'https://';
                    sourceUrlInput.autocomplete = 'off';
                    sourceUrlInput.value = sourceUrl;
                    sourceUrlInput.dataset.sectionSourceUrl = '';
                    sourceUrlField.append(sourceUrlInput);

                    section.append(sourceLabelField, sourceUrlField);
                    sourceLabelInput.addEventListener('input', () => { syncBody(); markUnsaved(); });
                    sourceUrlInput.addEventListener('input', () => { syncBody(); markUnsaved(); });
                }
                editor.append(section);
                headingInput.addEventListener('input', () => { syncBody(); markUnsaved(); });
                contentInput.addEventListener('input', () => { syncBody(); markUnsaved(); });
            };

            parseSections(fallback.value).forEach((section, index) => addSection(section, index > 0));
            fallback.hidden = true;
            fallback.required = false;
            addButton.addEventListener('click', () => {
                addSection();
                markUnsaved();
                editor.lastElementChild?.querySelector('[data-section-heading]')?.focus();
            });
            fallback.form?.addEventListener('submit', () => { syncBody(); hasUnsavedChanges = false; });
            fallback.form?.querySelectorAll('input, textarea').forEach((field) => {
                field.addEventListener('input', markUnsaved);
                field.addEventListener('change', markUnsaved);
            });
            window.addEventListener('beforeunload', (event) => {
                if (!hasUnsavedChanges) return;
                event.preventDefault();
                event.returnValue = '';
            });

            const removeImageCheckbox = fallback.form?.querySelector('[name="remove_image"]');
            const removeImageDialog = document.getElementById('removeEntryImageDialog');
            const cancelRemoveImage = document.getElementById('cancelRemoveEntryImage');
            const confirmRemoveImage = document.getElementById('confirmRemoveEntryImage');
            if (removeImageCheckbox && removeImageDialog) {
                removeImageCheckbox.addEventListener('change', () => {
                    if (!removeImageCheckbox.checked) return;
                    removeImageCheckbox.checked = false;
                    if (typeof removeImageDialog.showModal === 'function') removeImageDialog.showModal();
                    else if (window.confirm('Remove the current image when you save this entry?')) {
                        removeImageCheckbox.checked = true;
                        markUnsaved();
                    }
                });
                cancelRemoveImage?.addEventListener('click', () => removeImageDialog.close());
                confirmRemoveImage?.addEventListener('click', () => {
                    removeImageCheckbox.checked = true;
                    markUnsaved();
                    removeImageDialog.close();
                });
                removeImageDialog.addEventListener('click', (event) => {
                    if (event.target === removeImageDialog) removeImageDialog.close();
                });
            }
        })();
    </script>
@endsection
