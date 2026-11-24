<?php

namespace App\Http\Controllers;

use App\Models\EncyclopediaTopic;
use Illuminate\Support\Facades\Schema;

class EncyclopediaController extends Controller
{
    private function navigationTopics()
    {
        return EncyclopediaTopic::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get();
    }

    public function index()
    {
        $topics = Schema::hasTable('encyclopedia_topics') ? $this->navigationTopics() : collect();
        return view('encyclopedia.index', compact('topics'));
    }

    public function topic(string $topicSlug)
    {
        abort_unless(Schema::hasTable('encyclopedia_topics'), 503, 'The encyclopedia database migration has not run yet.');
        $topic = EncyclopediaTopic::where('slug', $topicSlug)->firstOrFail();
        abort_unless($topic->is_published, 404);
        $navTopics = $this->navigationTopics();
        $entries = $topic->entries()->where('is_published', true)->orderBy('sort_order')->orderBy('id')->get();

        return view('encyclopedia.topic', compact('topic', 'entries', 'navTopics'));
    }

    public function entry(string $topicSlug, string $entrySlug)
    {
        abort_unless(Schema::hasTable('encyclopedia_topics') && Schema::hasTable('encyclopedia_entries'), 503, 'The encyclopedia database migration has not run yet.');
        $topic = EncyclopediaTopic::where('slug', $topicSlug)->firstOrFail();
        $entry = $topic->entries()->where('slug', $entrySlug)->firstOrFail();
        abort_unless($topic->is_published && $entry->is_published && $entry->topic_id === $topic->id, 404);
        $navTopics = $this->navigationTopics();

        return view('encyclopedia.entry', compact('topic', 'entry', 'navTopics'));
    }
}
