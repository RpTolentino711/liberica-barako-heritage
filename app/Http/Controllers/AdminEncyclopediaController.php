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

class AdminEncyclopediaController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
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
        return view('admin.encyclopedia.topic-form', compact('topic'));
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

        if ($topic) $topic->update($data);
        else EncyclopediaTopic::create($data);

        return redirect()->route('admin.encyclopedia')->with('ok', 'Encyclopedia topic saved.');
    }

    public function deleteTopic(EncyclopediaTopic $topic)
    {
        $this->guard();
        $images = $topic->entries()->whereNotNull('image_path')->pluck('image_path')->all();
        $topic->delete();
        Storage::disk('public')->delete($images);

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

        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'alpha_dash', 'max:190', Rule::unique('encyclopedia_entries', 'slug')
                ->where(fn ($query) => $query->where('topic_id', $topic->id))->ignore($entry?->id)],
            'summary' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:30000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'source_label' => ['nullable', 'string', 'max:240'],
            'source_url' => ['nullable', 'url:http,https', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if (!$data['slug']) $data['slug'] = 'entry-'.Str::random(8);
        $data['topic_id'] = $topic->id;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');
        if ($request->hasFile('image')) $data['image_path'] = $request->file('image')->store('encyclopedia', 'public');
        unset($data['image']);

        $oldImage = $entry?->image_path;
        if ($entry) $entry->update($data);
        else $topic->entries()->create($data);
        if ($oldImage && isset($data['image_path'])) Storage::disk('public')->delete($oldImage);

        return redirect()->route('admin.encyclopedia.entries', $topic)->with('ok', 'Encyclopedia entry saved.');
    }

    public function deleteEntry(EncyclopediaTopic $topic, EncyclopediaEntry $entry)
    {
        $this->guard();
        abort_unless($entry->topic_id === $topic->id, 404);
        $image = $entry->image_path;
        $entry->delete();
        if ($image) Storage::disk('public')->delete($image);

        return back()->with('ok', 'Encyclopedia entry deleted.');
    }
}
