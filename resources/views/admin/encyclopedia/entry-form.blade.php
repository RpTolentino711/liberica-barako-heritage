@extends('layouts.admin')
@section('title', $entry ? 'Edit Encyclopedia Entry' : 'New Encyclopedia Entry')
@section('content')
    <p class="encyclopedia-admin-back"><a href="{{ route('admin.encyclopedia.entries', $topic) }}">← {{ $topic->name }}</a></p>
    <div class="panel form-panel">
        <form action="{{ $entry ? route('admin.encyclopedia.entries.update', [$topic, $entry]) : route('admin.encyclopedia.entries.store', $topic) }}" method="POST" enctype="multipart/form-data">
            @csrf @if($entry) @method('PUT') @endif
            <input type="hidden" id="encyclopediaSectionRemoved" name="section_removed" value="{{ old('section_removed', '0') }}">
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
                <p class="encyclopedia-editor-note">The article source below applies to the main content. Each additional section can have its own optional source name and URL, shown beneath that section. Confirming section deletion saves the article immediately.</p>
            </div>
            <div class="two-col">
                <div><label for="source_label">Source name</label><input id="source_label" name="source_label" maxlength="240" value="{{ old('source_label', $entry?->source_label) }}" placeholder="Department of Agriculture"></div>
                <div><label for="source_url">Source link (HTTPS)</label><input id="source_url" type="url" name="source_url" maxlength="2048" value="{{ old('source_url', $entry?->source_url) }}" placeholder="https://"></div>
            </div>
            @include('admin.encyclopedia.references')
            <label for="image">Optional image (JPG, PNG, WebP; max 4 MB)</label>
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp">
            @if($entry?->image_path)
                <input type="hidden" id="removeCurrentImage" name="remove_image" value="0">
                <div class="encyclopedia-image-preview-wrap" id="encyclopediaImagePreviewWrap">
                    <img class="image-preview" id="currentEntryImagePreview" src="{{ str_starts_with($entry->image_path, 'assets/') ? asset($entry->image_path) : asset('storage/'.$entry->image_path) }}" alt="Current entry image">
                    <div class="encyclopedia-image-actions" aria-label="Photo actions">
                        <button type="button" id="updateEntryImage" aria-label="Update photo" title="Update photo">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                            <span>Update</span>
                        </button>
                        <button type="button" id="deleteEntryImage" class="is-delete" aria-label="Delete photo" title="Delete photo">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2m3 0-1 14H6L5 6"/><path d="M10 11v5m4-5v5"/></svg>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            @endif
            <label for="sort_order">Display order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $entry?->sort_order ?? 0) }}">
            <label class="check"><input name="is_published" type="checkbox" value="1" @checked(old('is_published', $entry?->is_published ?? false))> Publish this entry</label>
            <div class="form-actions"><button class="primary-button" type="submit">SAVE ENTRY</button><a class="button-alt" href="{{ route('admin.encyclopedia.entries', $topic) }}">CANCEL</a></div>
        </form>
    </div>
    <dialog id="removeEncyclopediaSectionDialog" class="delete-confirm-dialog" aria-labelledby="removeEncyclopediaSectionTitle" aria-describedby="removeEncyclopediaSectionDescription">
        <div class="delete-confirm-content">
            <div class="logout-warning-icon" aria-hidden="true">!</div>
            <p class="logout-dialog-eyebrow">PLEASE CONFIRM</p>
            <h2 id="removeEncyclopediaSectionTitle">Delete this section?</h2>
            <p id="removeEncyclopediaSectionDescription">Are you sure you want to delete this content? Confirming will save the article immediately, including any other unsaved edits.</p>
            <div class="logout-dialog-actions">
                <button type="button" class="logout-cancel" id="cancelRemoveEncyclopediaSection">CANCEL</button>
                <button type="button" class="delete-confirm-button" id="confirmRemoveEncyclopediaSection">YES, DELETE SECTION</button>
            </div>
        </div>
    </dialog>
    @if(session('section_deleted'))
        <dialog id="encyclopediaSectionDeletedDialog" class="delete-confirm-dialog" aria-labelledby="encyclopediaSectionDeletedTitle" aria-describedby="encyclopediaSectionDeletedDescription">
            <div class="delete-confirm-content">
                <div class="logout-warning-icon" aria-hidden="true">✓</div>
                <p class="logout-dialog-eyebrow">SAVED SUCCESSFULLY</p>
                <h2 id="encyclopediaSectionDeletedTitle">Section deleted</h2>
                <p id="encyclopediaSectionDeletedDescription">The section was removed from this article and saved successfully.</p>
                <div class="logout-dialog-actions">
                    <button type="button" class="logout-cancel" id="closeEncyclopediaSectionDeleted">CONTINUE</button>
                </div>
            </div>
        </dialog>
    @endif
    <dialog id="unsavedEncyclopediaChangesDialog" class="delete-confirm-dialog" aria-labelledby="unsavedEncyclopediaChangesTitle" aria-describedby="unsavedEncyclopediaChangesDescription">
        <div class="delete-confirm-content">
            <div class="logout-warning-icon" aria-hidden="true">!</div>
            <p class="logout-dialog-eyebrow">UNSAVED CHANGES</p>
            <h2 id="unsavedEncyclopediaChangesTitle">Save before leaving?</h2>
            <p id="unsavedEncyclopediaChangesDescription">It looks like you changed something. Choose Save Changes to jump to the Save Entry button, or leave without saving to discard your edits.</p>
            <div class="logout-dialog-actions unsaved-change-actions">
                <button type="button" class="logout-cancel" id="leaveEncyclopediaWithoutSaving">LEAVE WITHOUT SAVING</button>
                <button type="button" class="button-alt" id="stayInEncyclopediaEditor">KEEP EDITING</button>
                <button type="button" class="delete-confirm-button" id="jumpToSaveEncyclopediaEntry">SAVE CHANGES</button>
            </div>
        </div>
    </dialog>
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
            let sectionPendingRemoval = null;
            let pendingNavigationUrl = null;
            const markUnsaved = () => { hasUnsavedChanges = true; };
            document.getElementById('entry-references').addEventListener('change', markUnsaved);
            document.getElementById('entry-references').addEventListener('input', markUnsaved);
            const removeSectionDialog = document.getElementById('removeEncyclopediaSectionDialog');
            const cancelRemoveSection = document.getElementById('cancelRemoveEncyclopediaSection');
            const confirmRemoveSection = document.getElementById('confirmRemoveEncyclopediaSection');
            const sectionRemovedInput = document.getElementById('encyclopediaSectionRemoved');
            const unsavedDialog = document.getElementById('unsavedEncyclopediaChangesDialog');
            const leaveWithoutSaving = document.getElementById('leaveEncyclopediaWithoutSaving');
            const stayInEditor = document.getElementById('stayInEncyclopediaEditor');
            const jumpToSave = document.getElementById('jumpToSaveEncyclopediaEntry');

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
                    remove.addEventListener('click', () => {
                        sectionPendingRemoval = section;
                        if (removeSectionDialog && typeof removeSectionDialog.showModal === 'function') {
                            removeSectionDialog.showModal();
                        } else if (window.confirm('Are you sure you want to delete this content? Confirming will save the article immediately, including any other unsaved edits.')) {
                            section.remove();
                            syncBody();
                            if (sectionRemovedInput) sectionRemovedInput.value = '1';
                            markUnsaved();
                            fallback.form?.requestSubmit();
                            sectionPendingRemoval = null;
                        }
                    });
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
            cancelRemoveSection?.addEventListener('click', () => {
                sectionPendingRemoval = null;
                removeSectionDialog.close();
            });
            confirmRemoveSection?.addEventListener('click', () => {
                sectionPendingRemoval?.remove();
                sectionPendingRemoval = null;
                syncBody();
                if (sectionRemovedInput) sectionRemovedInput.value = '1';
                markUnsaved();
                fallback.form?.requestSubmit();
            });
            removeSectionDialog?.addEventListener('click', (event) => {
                if (event.target === removeSectionDialog) {
                    sectionPendingRemoval = null;
                    removeSectionDialog.close();
                }
            });
            document.addEventListener('click', (event) => {
                const link = event.target.closest('a[href]');
                if (!hasUnsavedChanges || !link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                if (link.target === '_blank' || link.hasAttribute('download')) return;
                const destination = new URL(link.href, window.location.href);
                if (destination.origin !== window.location.origin || destination.href === window.location.href) return;
                if (typeof unsavedDialog?.showModal !== 'function') {
                    if (window.confirm('You have unsaved changes. Leave without saving?')) {
                        hasUnsavedChanges = false;
                        window.location.assign(destination.href);
                    }
                    return;
                }
                event.preventDefault();
                pendingNavigationUrl = destination.href;
                unsavedDialog.showModal();
            });
            leaveWithoutSaving?.addEventListener('click', () => {
                hasUnsavedChanges = false;
                unsavedDialog.close();
                if (pendingNavigationUrl) window.location.assign(pendingNavigationUrl);
            });
            stayInEditor?.addEventListener('click', () => {
                pendingNavigationUrl = null;
                unsavedDialog.close();
            });
            jumpToSave?.addEventListener('click', () => {
                pendingNavigationUrl = null;
                unsavedDialog.close();
                const saveButton = fallback.form?.querySelector('button[type="submit"]');
                saveButton?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                saveButton?.focus({ preventScroll: true });
            });
            unsavedDialog?.addEventListener('click', (event) => {
                if (event.target === unsavedDialog) {
                    pendingNavigationUrl = null;
                    unsavedDialog.close();
                }
            });
            const sectionDeletedDialog = document.getElementById('encyclopediaSectionDeletedDialog');
            const closeSectionDeleted = document.getElementById('closeEncyclopediaSectionDeleted');
            if (sectionDeletedDialog) {
                if (typeof sectionDeletedDialog.showModal === 'function') sectionDeletedDialog.showModal();
                closeSectionDeleted?.addEventListener('click', () => sectionDeletedDialog.close());
                sectionDeletedDialog.addEventListener('click', (event) => {
                    if (event.target === sectionDeletedDialog) sectionDeletedDialog.close();
                });
            }
            window.addEventListener('beforeunload', (event) => {
                if (!hasUnsavedChanges) return;
                event.preventDefault();
                event.returnValue = '';
            });

            const removeImageInput = document.getElementById('removeCurrentImage');
            const removeImageDialog = document.getElementById('removeEntryImageDialog');
            const cancelRemoveImage = document.getElementById('cancelRemoveEntryImage');
            const confirmRemoveImage = document.getElementById('confirmRemoveEntryImage');
            const deleteImageButton = document.getElementById('deleteEntryImage');
            const updateImageButton = document.getElementById('updateEntryImage');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('currentEntryImagePreview');
            if (deleteImageButton && removeImageInput) {
                deleteImageButton.addEventListener('click', () => {
                    if (typeof removeImageDialog?.showModal === 'function') removeImageDialog.showModal();
                    else if (window.confirm('Remove the current image when you save this entry?')) {
                        removeImageInput.value = '1';
                        markUnsaved();
                    }
                });
            }
            if (updateImageButton && imageInput) updateImageButton.addEventListener('click', () => imageInput.click());
            imageInput?.addEventListener('change', () => {
                const file = imageInput.files?.[0];
                if (!file) return;
                if (removeImageInput) removeImageInput.value = '0';
                if (imagePreview) imagePreview.src = URL.createObjectURL(file);
                markUnsaved();
            });
            if (removeImageDialog) {
                cancelRemoveImage?.addEventListener('click', () => removeImageDialog.close());
                confirmRemoveImage?.addEventListener('click', () => {
                    if (removeImageInput) removeImageInput.value = '1';
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
