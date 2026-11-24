<?php

namespace App\Http\Controllers;

use App\Models\FarmLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminFarmLocationController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->is_admin, 403);
    }

    public function index()
    {
        $this->guard();
        return view('admin.farm-locations.index', [
            'locations' => FarmLocation::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        $this->guard();
        return view('admin.farm-locations.form', ['location' => null]);
    }

    public function edit(FarmLocation $location)
    {
        $this->guard();
        return view('admin.farm-locations.form', compact('location'));
    }

    public function save(Request $request, ?FarmLocation $location = null)
    {
        $this->guard();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'place_name' => ['required', 'string', 'max:220'],
            'location_type' => ['nullable', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1200'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'photo' => [$location ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'photo_alt' => ['nullable', 'string', 'max:240'],
            'source_url' => ['nullable', 'url:http,https', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');
        unset($data['photo']);
        $oldPhoto = $location?->photo_path;

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('farm-locations', 'public');
        }

        if ($location) $location->update($data);
        else FarmLocation::create($data);

        if ($oldPhoto && isset($data['photo_path']) && !str_starts_with($oldPhoto, 'assets/')) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('admin.farm-locations.index')->with('ok', 'Map location saved.');
    }

    public function delete(FarmLocation $location)
    {
        $this->guard();
        $photoPath = $location->photo_path;
        $location->delete();
        if ($photoPath && !str_starts_with($photoPath, 'assets/')) {
            Storage::disk('public')->delete($photoPath);
        }

        return back()->with('ok', 'Map location deleted.');
    }
}
