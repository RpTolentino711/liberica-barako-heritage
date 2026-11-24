<?php

namespace App\Http\Controllers;

use App\Models\EncyclopediaEntry;
use App\Models\EncyclopediaTopic;
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
        preg_match_all('/@+ENCYCLOPEDIA_SOURCE:[^\r\n]*?@+/', $body, $allMarkers);
        preg_match_all('/^@+ENCYCLOPEDIA_SOURCE:([A-Za-z0-9+\/=]+)@+$/m', $body, $validMarkers);
        if (count($allMarkers[0]) !== count($validMarkers[1])) {
            throw ValidationException::withMessages(['body' => 'A section source could not be read. Please check its source name and URL.']);
        }

        $articleText = preg_replace('/^## [^\r\n]*\s*(?:\r?\n|$)/m', '', $body) ?? $body;
        $articleText = preg_replace('/^@+ENCYCLOPEDIA_SOURCE:[^\r\n]*?@+\s*(?:\r?\n|$)/m', '', $articleText) ?? $articleText;
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
        $data['is_published'] = $request->boolean('is_published');

        if ($topic) {
            $topic->update($data);
            return redirect()->route('admin.encyclopedia.topics.edit', $topic)->with('ok', 'Encyclopedia topic saved.');
        }

        EncyclopediaTopic::create($data);
        return redirect()->route('admin.encyclopedia')->with('ok', 'Encyclopedia topic saved.');
    }

    public function deleteTopic(EncyclopediaTopic $topic)
    {
        $this->guard();
        $images = $topic->entries()->whereNotNull('image_path')->pluck('image_path')->all();
        $topic->delete();
        Storage::disk('public')->delete(array_values(array_filter($images, fn ($path) => !str_starts_with($path, 'assets/'))));

        return back()->with('ok', 'Topic and its entries deleted.');
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
        $data['is_published'] = $request->boolean('is_published');
        $oldImage = $entry?->image_path;
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('encyclopedia', 'public');
        } elseif ($request->boolean('remove_image') && $entry) {
            $data['image_path'] = null;
        }
        unset($data['image'], $data['remove_image']);
        unset($data['section_removed']);

        if ($entry) $entry->update($data);
        else $topic->entries()->create($data);
        if ($oldImage && array_key_exists('image_path', $data) && !str_starts_with($oldImage, 'assets/')) {
            Storage::disk('public')->delete($oldImage);
        }

        if ($entry && $sectionRemoved) {
            return redirect()->route('admin.encyclopedia.entries.edit', [$topic, $entry])
                ->with('section_deleted', true);
        }

        return redirect()->route('admin.encyclopedia.entries', $topic)->with('ok', 'Encyclopedia entry saved.');
    }

    public function deleteEntry(EncyclopediaTopic $topic, EncyclopediaEntry $entry)
    {
        $this->guard();
        abort_unless($entry->topic_id === $topic->id, 404);
        $image = $entry->image_path;
        $entry->delete();
        if ($image && !str_starts_with($image, 'assets/')) Storage::disk('public')->delete($image);

        return back()->with('ok', 'Encyclopedia entry deleted.');
    }

    public function toggleEntryPublication(EncyclopediaTopic $topic, EncyclopediaEntry $entry)
    {
        $this->guard();
        abort_unless($entry->topic_id === $topic->id, 404);

        $entry->update(['is_published' => !$entry->is_published]);
        $status = $entry->is_published ? 'published' : 'unpublished';

        return back()->with('ok', 'Encyclopedia entry '.$status.'.');
    }
}
