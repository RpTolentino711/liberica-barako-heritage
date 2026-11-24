<?php
namespace App\Http\Controllers;

use App\Models\{Article, EncyclopediaTopic, GalleryItem, JourneyStep, Reference, Message, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller {
    public function index() {
        $featuredEntry = Schema::hasColumn('encyclopedia_entries', 'featured_in_discover')
            ? \App\Models\EncyclopediaEntry::with('topic')->where('featured_in_discover', true)
                ->where('is_published', true)->whereHas('topic', fn ($query) => $query->where('is_published', true))->first()
            : null;
        return view('landing.index', [
            'featuredEntry' => $featuredEntry,
            'articles' => Article::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get(),
            'gallery' => GalleryItem::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get(),
            'references' => Reference::where('is_published', true)->orderBy('id')->get(),
            'settings' => Setting::pluck('value', 'key')->all(),
            'journeySteps' => Schema::hasTable('journey_steps')
                ? JourneyStep::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get()
                : collect(),
            'topics' => Schema::hasTable('encyclopedia_topics')
                ? EncyclopediaTopic::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get()
                : collect(),
        ]);
    }

    public function message(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190',
            'message' => 'required|string|min:10|max:4000',
            'website' => 'nullable|size:0',
        ]);
        unset($data['website']);
        Message::create($data);
        return redirect('/#contact')->with('success', 'Thank you! Your message was received.');
    }
}
