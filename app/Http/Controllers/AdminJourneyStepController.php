<?php

namespace App\Http\Controllers;

use App\Models\JourneyStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminJourneyStepController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
    }

    public function index()
    {
        $this->guard();
        return view('admin.journey.index', [
            'steps' => JourneyStep::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        $this->guard();
        return view('admin.journey.form', ['step' => null]);
    }

    public function edit(JourneyStep $step)
    {
        $this->guard();
        return view('admin.journey.form', compact('step'));
    }

    public function save(Request $request, ?JourneyStep $step = null)
    {
        $this->guard();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'description' => ['required', 'string', 'max:3000'],
            'media_alt' => ['nullable', 'string', 'max:240'],
            'media' => [$step ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,webp,mp4,webm,mov', 'max:51200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');
        unset($data['media']);
        $oldMedia = $step?->media_path;

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $data['media_path'] = $file->store('journey', 'public');
            $data['media_type'] = str_starts_with((string) $file->getMimeType(), 'video/') ? 'video' : 'image';
        }

        if ($step) $step->update($data);
        else JourneyStep::create($data);

        if ($oldMedia && isset($data['media_path']) && !str_starts_with($oldMedia, 'assets/')) {
            Storage::disk('public')->delete($oldMedia);
        }

        return redirect()->route('admin.journey.index')->with('ok', 'Bean to Cup step saved.');
    }

    public function delete(JourneyStep $step)
    {
        $this->guard();
        $mediaPath = $step->media_path;
        $step->delete();
        if ($mediaPath && !str_starts_with($mediaPath, 'assets/')) Storage::disk('public')->delete($mediaPath);

        return back()->with('ok', 'Bean to Cup step deleted.');
    }
}
