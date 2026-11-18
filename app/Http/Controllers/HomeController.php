<?php
namespace App\Http\Controllers;

use App\Models\{Article, GalleryItem, Reference, Message, Setting};
use Illuminate\Http\Request;

class HomeController extends Controller {
    public function index() {
        return view('landing.index', [
            'articles' => Article::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get(),
            'gallery' => GalleryItem::where('is_published', true)->orderBy('sort_order')->orderBy('id')->get(),
            'references' => Reference::where('is_published', true)->orderBy('id')->get(),
            'settings' => Setting::pluck('value', 'key')->all(),
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
