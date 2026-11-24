<?php

namespace App\Http\Controllers;

use App\Models\JourneyStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function reorder(Request $request)
    {
        $this->guard();
        $data = $request->validate([
            'steps' => ['required', 'array'],
            'steps.*' => ['required', 'integer', 'distinct', 'exists:journey_steps,id'],
        ]);

        $ids = array_map('intval', $data['steps']);
        $expectedIds = JourneyStep::orderBy('id')->pluck('id')->map(fn ($id) => (int) $id)->all();
        $submittedIds = $ids;
        sort($expectedIds);
        sort($submittedIds);
        if ($submittedIds !== $expectedIds) {
            return response()->json(['message' => 'The step list changed. Refresh and try again.'], 409);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $position => $id) {
                JourneyStep::whereKey($id)->update(['sort_order' => $position + 1]);
            }
        });

        return response()->json(['message' => 'Bean to Cup order saved.']);
    }

    public function save(Request $request, ?JourneyStep $step = null)
    {
        $this->guard();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:140'],
            'description' => ['required', 'string', 'max:3000'],
            'source' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url:http,https', 'max:2048'],
            'creator' => ['nullable', 'string', 'max:255'],
            'photo_url' => ['nullable', 'url:http,https', 'max:2048'],
            'license' => ['nullable', 'string', 'max:255'],
            'license_url' => ['nullable', 'url:http,https', 'max:2048'],
            'media_note' => ['nullable', 'string', 'max:255'],
            'media_alt' => ['nullable', 'string', 'max:240'],
            'media' => [$step ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,webp,mp4,webm,mov', 'max:51200'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = !$step?->archived_at && $request->boolean('is_published');
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

    public function archive(Request $request, JourneyStep $step)
    {
        $this->guard();
        $request->validate(['archived' => ['required', 'boolean']]);
        $archive = $request->boolean('archived');
        if ($archive || $step->archived_at) {
            $step->archived_at = $archive ? now() : null;
            $step->is_published = false;
            $step->save();
        }

        return back()->with('ok', $archive ? 'Step archived.' : 'Step restored as a draft.');
    }
}
