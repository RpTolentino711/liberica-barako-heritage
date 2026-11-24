<?php

namespace App\Http\Controllers;

use App\Models\EncyclopediaEntry;
use App\Models\EncyclopediaTopic;
use App\Support\EncyclopediaReferenceEditor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminEncyclopediaController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
    }

    private function validateSectionSources(string $body): void
    {
        preg_match_all('/^@+ENCYCLOPEDIA_SOURCE:[^\r\n]*?@+[ \t]*\r?$/m', $body, $allMarkers);
        preg_match_all('/^@+ENCYCLOPEDIA_SOURCE:([A-Za-z0-9+\/=]+)@+[ \t]*\r?$/m', $body, $validMarkers);
        if (count($allMarkers[0]) !== count($validMarkers[1])) {
            throw ValidationException::withMessages(['body' => 'A section source could not be read. Please check its source name and URL.']);
        }

        $articleText = preg_replace('/^## [^\r\n]*\s*(?:\r?\n|$)/m', '', $body) ?? $body;
        $articleText = preg_replace('/^@+ENCYCLOPEDIA_SOURCE:[^\r\n]*?@+[ \t]*\r?\n?/m', '', $articleText) ?? $articleText;
        if (trim($articleText) === '') {
            throw ValidationException::withMessages(['body' => 'Add some article text before saving.']);
        }

        foreach ($validMarkers[1] as $encodedSource) {
            $json = base64_decode($encodedSource, true);
            $source = is_string($json) ? json_decode($json, true) : null;
            if (!is_array($source)) {
                throw ValidationException::withMessages(['body' => 'A section source could not be read. Please add it again.']);
            }

            foreach (['label', 'url'] as $field) {
                if (isset($source[$field]) && !is_string($source[$field])) {
                    throw ValidationException::withMessages(['body' => 'Section source names and URLs must be plain text.']);
                }
                $source[$field] = trim($source[$field] ?? '') ?: null;
            }
            $validator = Validator::make($source, [
                'label' => ['nullable', 'string', 'max:240'],
                'url' => ['nullable', 'url:http,https', 'max:2048'],
            ]);
            if ($validator->fails()) {
                throw ValidationException::withMessages(['body' => 'Each section source needs a valid HTTP or HTTPS URL and a source name up to 240 characters.']);
            }
        }
    }

    public function index()
    {
        $this->guard();
        if (!Schema::hasTable('encyclopedia_topics') || !Schema::hasTable('encyclopedia_entries')) {
            return view('admin.encyclopedia.index', ['topics' => collect(), 'migrationPending' => true]);
        }
        return view('admin.encyclopedia.index', [
            'topics' => EncyclopediaTopic::withCount('entries')->orderBy('sort_order')->orderBy('id')->get(),
            'migrationPending' => false,
        ]);
    }

    public function createTopic()
    {
        $this->guard();
        return view('admin.encyclopedia.topic-form', ['topic' => null]);
    }

    public function editTopic(EncyclopediaTopic $topic)
    {
        $this->guard();
        $entries = $topic->entries()->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.encyclopedia.topic-form', compact('topic', 'entries'));
    }

    public function saveTopic(Request $request, ?EncyclopediaTopic $topic = null)
    {
        $this->guard();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'alpha_dash', 'max:140', Rule::unique('encyclopedia_topics', 'slug')->ignore($topic?->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        if (!$data['slug']) $data['slug'] = 'topic-'.Str::random(8);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = !$topic?->archived_at && $request->boolean('is_published');

        if ($topic) {
            $topic->update($data);
            return redirect()->route('admin.encyclopedia.topics.edit', $topic)->with('ok', 'Encyclopedia topic saved.');
        }

        EncyclopediaTopic::create($data);
        return redirect()->route('admin.encyclopedia')->with('ok', 'Encyclopedia topic saved.');
    }

    public function archiveTopic(Request $request, EncyclopediaTopic $topic)
    {
        $this->guard();
        $request->validate(['archived' => ['required', 'boolean']]);
        $archive = $request->boolean('archived');
        if ($archive || $topic->archived_at) {
            $topic->archived_at = $archive ? now() : null;
            $topic->is_published = false;
            $topic->save();
        }

        return back()->with('ok', $archive ? 'Topic archived.' : 'Topic restored as a draft.');
    }

    public function entries(EncyclopediaTopic $topic)
    {
        $this->guard();
        return view('admin.encyclopedia.entries', [
            'topic' => $topic,
            'entries' => $topic->entries()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function createEntry(EncyclopediaTopic $topic)
    {
        $this->guard();
        return view('admin.encyclopedia.entry-form', ['topic' => $topic, 'entry' => null]);
    }

    public function editEntry(EncyclopediaTopic $topic, EncyclopediaEntry $entry)
    {
        $this->guard();
        abort_unless($entry->topic_id === $topic->id, 404);
        return view('admin.encyclopedia.entry-form', compact('topic', 'entry'));
    }

    public function saveEntry(Request $request, EncyclopediaTopic $topic, ?EncyclopediaEntry $entry = null)
    {
        $this->guard();
        if ($entry) abort_unless($entry->topic_id === $topic->id, 404);
        $sectionRemoved = $request->boolean('section_removed');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'alpha_dash', 'max:190', Rule::unique('encyclopedia_entries', 'slug')
                ->where(fn ($query) => $query->where('topic_id', $topic->id))->ignore($entry?->id)],
            'summary' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:30000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['nullable', 'boolean'],
            'section_removed' => ['nullable', 'boolean'],
            'source_label' => ['nullable', 'string', 'max:240'],
            'source_url' => ['nullable', 'url:http,https', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
        $this->validateSectionSources($data['body']);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if (!$data['slug']) $data['slug'] = 'entry-'.Str::random(8);
        $data['topic_id'] = $topic->id;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = !$entry?->archived_at && $request->boolean('is_published');
        $oldImage = $entry?->image_path;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('encyclopedia', 'public');
        } elseif ($request->boolean('remove_image') && $entry) {
            $data['image_path'] = null;
        }
        unset($data['image'], $data['remove_image']);
        unset($data['section_removed']);

        $uploaded = [];
        $retired = [];
        try {
            $entry = DB::transaction(function () use ($topic, $entry, $data, $request, &$uploaded, &$retired) {
                $saved = $entry ? $topic->entries()->lockForUpdate()->findOrFail($entry->id) : $topic->entries()->make();
                $saved->fill($data)->save();
                EncyclopediaReferenceEditor::save($request, $saved, $uploaded, $retired);
                return $saved;
            });
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($uploaded);
            if (!empty($data['image_path'])) Storage::disk('public')->delete($data['image_path']);
            throw $exception;
        }
        Storage::disk('local')->delete($retired);
        if ($oldImage && array_key_exists('image_path', $data) && !str_starts_with($oldImage, 'assets/')) {
            Storage::disk('public')->delete($oldImage);
        }

        if ($entry && $sectionRemoved) {
            return redirect()->route('admin.encyclopedia.entries.edit', [$topic, $entry])
                ->with('section_deleted', true);
        }

        return redirect()->route('admin.encyclopedia.entries', $topic)->with('ok', 'Encyclopedia entry saved.');
    }

    public function archiveEntry(Request $request, EncyclopediaTopic $topic, EncyclopediaEntry $entry)
    {
        $this->guard();
        abort_unless($entry->topic_id === $topic->id, 404);
        $request->validate(['archived' => ['required', 'boolean']]);
        $archive = $request->boolean('archived');
        if ($archive || $entry->archived_at) {
            $entry->archived_at = $archive ? now() : null;
            $entry->is_published = false;
            $entry->save();
        }

        return back()->with('ok', $archive ? 'Entry archived.' : 'Entry restored as a draft.');
    }

    public function toggleEntryPublication(EncyclopediaTopic $topic, EncyclopediaEntry $entry)
    {
        $this->guard();
        abort_unless($entry->topic_id === $topic->id, 404);

        abort_if($entry->archived_at, 409, 'Restore the entry before publishing.');
        $entry->update(['is_published' => !$entry->is_published]);
        $status = $entry->is_published ? 'published' : 'unpublished';

        return back()->with('ok', 'Encyclopedia entry '.$status.'.');
    }
}
