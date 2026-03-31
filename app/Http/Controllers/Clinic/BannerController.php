<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    private function clinicId(): int
    {
        return auth()->user()->clinic_id;
    }

    public function index(): Response
    {
        $banners = Banner::where('clinic_id', $this->clinicId())
            ->orderBy('position')
            ->orderBy('sort_order')
            ->paginate(20);

        return Inertia::render('clinic/Banners/Index', ['banners' => $banners]);
    }

    public function create(): Response
    {
        return Inertia::render('clinic/Banners/Form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'media'       => ['nullable', 'file', 'max:51200', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm'],
            'link'        => ['nullable', 'url', 'max:500'],
            'media_type'  => ['required', 'in:image,video,text'],
            'position'    => ['required', 'in:sidebar,top,chat'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
            'starts_at'   => ['nullable', 'date'],
            'ends_at'     => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        if ($request->hasFile('media')) {
            $folder             = $data['media_type'] === 'video' ? 'banners/videos' : 'banners/images';
            $data['media_path'] = $request->file('media')->store($folder, 'public');
        }
        unset($data['media']);

        $data['clinic_id'] = $this->clinicId();

        Banner::create($data);

        return redirect()->route('clinic.banners.index')->with('success', "Banner qo'shildi.");
    }

    public function edit(Banner $banner): Response
    {
        abort_unless($banner->clinic_id === $this->clinicId(), 403);

        return Inertia::render('clinic/Banners/Form', ['banner' => $banner]);
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        abort_unless($banner->clinic_id === $this->clinicId(), 403);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'media'       => ['nullable', 'file', 'max:51200', 'mimes:jpg,jpeg,png,gif,webp,mp4,webm'],
            'link'        => ['nullable', 'url', 'max:500'],
            'media_type'  => ['required', 'in:image,video,text'],
            'position'    => ['required', 'in:sidebar,top,chat'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
            'starts_at'   => ['nullable', 'date'],
            'ends_at'     => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        if ($request->hasFile('media')) {
            if ($banner->media_path) {
                Storage::disk('public')->delete($banner->media_path);
            }
            $folder             = $data['media_type'] === 'video' ? 'banners/videos' : 'banners/images';
            $data['media_path'] = $request->file('media')->store($folder, 'public');
        }
        unset($data['media']);

        $banner->update($data);

        return redirect()->route('clinic.banners.index')->with('success', "Banner yangilandi.");
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        abort_unless($banner->clinic_id === $this->clinicId(), 403);

        if ($banner->media_path) {
            Storage::disk('public')->delete($banner->media_path);
        }
        $banner->delete();

        return redirect()->route('clinic.banners.index')->with('success', "Banner o'chirildi.");
    }
}
