<?php
namespace App\Http\Controllers;

use App\Models\{Article, GalleryItem, Reference, Message, Setting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller {
    private function guard(): void {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
    }

    public function login() {
        if (Auth::check() && Auth::user()->is_admin) return redirect()->route('admin.dashboard');
        return view('admin.login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate(['email'=>'required|email', 'password'=>'required|string']);
        if (Auth::attempt($credentials)) {
            if (!Auth::user()->is_admin) {
                Auth::logout();
                return back()->withErrors(['email'=>'Administrator account required.']);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        return back()->withErrors(['email'=>'Invalid login details.'])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function dashboard() {
        $this->guard();
        return view('admin.dashboard', [
            'articleCount'=>Article::count(),
            'publishedCount'=>Article::where('is_published',true)->count(),
            'galleryCount'=>GalleryItem::count(),
            'messageCount'=>Message::where('status','new')->count(),
            'recentMessages'=>Message::latest()->take(5)->get(),
        ]);
    }

    public function articles() {
        $this->guard();
        return view('admin.articles', ['items'=>Article::orderBy('sort_order')->orderBy('id')->get()]);
    }

    public function articleForm(?Article $article = null) {
        $this->guard();
        return view('admin.article-form', compact('article'));
    }

    private function articleData(Request $request, ?Article $article = null): array {
        $data = $request->validate([
            'title'=>'required|string|max:160',
            'slug'=>['nullable','alpha_dash','max:190',Rule::unique('articles','slug')->ignore($article?->id)],
            'category'=>['required', Rule::in(['heritage','botany','cultivation','culture','preservation'])],
            'summary'=>'required|string|max:400',
            'body'=>'required|string|max:20000',
            'image'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sort_order'=>'nullable|integer|min:0|max:9999',
        ]);
        $data['slug']=$data['slug'] ?: Str::slug($data['title']);
        if (!$data['slug']) $data['slug'] = 'story-'.Str::random(8);
        $data['sort_order']=$data['sort_order'] ?? 0;
        $data['is_published']=$request->boolean('is_published');
        if ($request->hasFile('image')) $data['image_path']=$request->file('image')->store('articles','public');
        unset($data['image']);
        return $data;
    }

    public function saveArticle(Request $request, ?Article $article = null) {
        $this->guard();
        $data = $this->articleData($request,$article);
        if (Article::where('slug',$data['slug'])->when($article,fn($q)=>$q->where('id', '!=', $article->id))->exists()) {
            return back()->withErrors(['slug'=>'This URL slug is already used.'])->withInput();
        }
        $old = $article?->image_path;
        if ($article) $article->update($data); else Article::create($data);
        if ($old && isset($data['image_path'])) Storage::disk('public')->delete($old);
        return redirect()->route('admin.articles')->with('ok','Article saved successfully.');
    }

    public function deleteArticle(Article $article) {
        $this->guard();
        $old=$article->image_path;
        $article->delete();
        if ($old) Storage::disk('public')->delete($old);
        return back()->with('ok','Article deleted.');
    }

    public function gallery() {
        $this->guard();
        return view('admin.gallery', ['items'=>GalleryItem::orderBy('sort_order')->get()]);
    }

    public function saveGallery(Request $request) {
        $this->guard();
        $data=$request->validate([
            'title'=>'required|string|max:160',
            'caption'=>'nullable|string|max:400',
            'image_credit'=>'nullable|string|max:190',
            'image'=>'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sort_order'=>'nullable|integer|min:0|max:9999',
        ]);
        $data['image_path']=$request->file('image')->store('gallery','public');
        $data['sort_order']=$data['sort_order']??0;
        $data['is_published']=$request->boolean('is_published');
        unset($data['image']);
        GalleryItem::create($data);
        return back()->with('ok','Photo added.');
    }

    public function deleteGallery(GalleryItem $item) {
        $this->guard();
        $item->delete();
        Storage::disk('public')->delete($item->image_path);
        return back()->with('ok','Photo removed.');
    }

    public function references() {
        $this->guard();
        return view('admin.references', ['items'=>Reference::latest()->get()]);
    }

    public function saveReference(Request $request) {
        $this->guard();
        $data=$request->validate([
            'title'=>'required|string|max:190',
            'publisher'=>'required|string|max:120',
            'url'=>'required|url|max:2048',
            'notes'=>'nullable|string|max:2000',
        ]);
        $data['is_published']=$request->boolean('is_published');
        Reference::create($data);
        return back()->with('ok','Reference added.');
    }

    public function deleteReference(Reference $reference) {
        $this->guard();
        $reference->delete();
        return back()->with('ok','Reference removed.');
    }

    public function messages() {
        $this->guard();
        return view('admin.messages',['items'=>Message::latest()->paginate(15)]);
    }

    public function updateMessage(Request $request, Message $message) {
        $this->guard();
        $request->validate(['status'=>['required',Rule::in(['new','read','replied','archived'])]]);
        $message->update(['status'=>$request->status]);
        return back()->with('ok','Message updated.');
    }

    public function settings() {
        $this->guard();
        return view('admin.settings',['settings'=>Setting::pluck('value','key')->all()]);
    }

    public function saveSettings(Request $request) {
        $this->guard();
        $data=$request->validate([
            'site_name'=>'required|string|max:100',
            'hero_kicker'=>'required|string|max:100',
            'hero_title'=>'required|string|max:120',
            'hero_accent'=>'required|string|max:120',
            'hero_description'=>'required|string|max:700',
            'contact_email'=>'nullable|email|max:190',
            'hero_image'=>'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
        if ($request->hasFile('hero_image')) {
            $old=Setting::where('key','hero_image')->value('value');
            $data['hero_image']=$request->file('hero_image')->store('hero','public');
            if ($old && str_starts_with($old,'hero/')) Storage::disk('public')->delete($old);
        } else unset($data['hero_image']);
        foreach ($data as $key=>$value) Setting::updateOrCreate(['key'=>$key],['value'=>$value]);
        return back()->with('ok','Website settings updated.');
    }
}
