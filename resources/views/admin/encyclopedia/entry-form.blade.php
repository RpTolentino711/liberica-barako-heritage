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
                <p class="encyclopedia-editor-note">Add another section to this article. Leave a section heading blank if you only need more paragraphs.</p>
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
    <script>
        (() => {
            const editor = document.getElementById('encyclopediaSections');
            const fallback = document.getElementById('body');
            const addButton = document.getElementById('addEncyclopediaSection');
            if (!editor || !fallback || !addButton) return;

            const parseSections = (value) => {
                const sections = [];
                let heading = '';
                let content = [];
                let hasHeading = false;
                for (const line of value.split(/\r?\n/)) {
                    if (line.startsWith('## ')) {
                        if (content.join('\n').trim() || hasHeading) sections.push({ heading, content: content.join('\n').trim() });
                        heading = line.slice(3).trim();
                        content = [];
                        hasHeading = true;
                    } else {
                        content.push(line);
                    }
                }
                if (content.join('\n').trim() || hasHeading || sections.length === 0) {
                    sections.push({ heading, content: content.join('\n').trim() });
                }
                return sections;
            };

            const syncBody = () => {
                const values = [...editor.querySelectorAll('.encyclopedia-content-section')].map((section) => {
                    const heading = section.querySelector('[data-section-heading]').value.trim();
                    const content = section.querySelector('[data-section-content]').value.trim();
                    if (!heading && !content) return '';
                    return heading ? `## ${heading}\n\n${content}` : content;
                }).filter(Boolean);
                fallback.value = values.join('\n\n');
            };

            const addSection = ({ heading = '', content = '' } = {}, removable = true) => {
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
                    remove.addEventListener('click', () => { section.remove(); syncBody(); });
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
                contentInput.value = content;
                contentInput.dataset.sectionContent = '';
                contentLabel.append(contentInput);

                section.append(sectionHead, headingLabel, contentLabel);
                editor.append(section);
                headingInput.addEventListener('input', syncBody);
                contentInput.addEventListener('input', syncBody);
            };

            parseSections(fallback.value).forEach((section, index) => addSection(section, index > 0));
            fallback.hidden = true;
            fallback.required = false;
            addButton.addEventListener('click', () => {
                addSection();
                editor.lastElementChild?.querySelector('[data-section-heading]')?.focus();
            });
            fallback.form?.addEventListener('submit', syncBody);
        })();
    </script>
@endsection
