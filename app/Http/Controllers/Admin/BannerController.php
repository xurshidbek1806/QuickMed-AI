<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Clinic;
use App\Traits\LogsAdminActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BannerController extends Controller
{
    use LogsAdminActions;
    public function index(): Response
    {
        $banners = Banner::with('clinic:id,name')
            ->orderBy('position')
            ->orderBy('sort_order')
            ->paginate(25);

        return Inertia::render('admin/Banners/Index', ['banners' => $banners]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Banners/Form', [
            'clinics' => Clinic::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'clinic_id'   => ['nullable', 'exists:clinics,id'],
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
            $folder           = $data['media_type'] === 'video' ? 'banners/videos' : 'banners/images';
            $data['media_path'] = $request->file('media')->store($folder, 'public');
        }
        unset($data['media']);

        $banner = Banner::create($data);

        $this->logCreate($banner, ['title' => $data['title']]);

        return redirect()->route('admin.banners.index')->with('success', "Banner qo'shildi.");
    }

    public function edit(Banner $banner): Response
    {
        return Inertia::render('admin/Banners/Form', [
            'banner'  => $banner,
            'clinics' => Clinic::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $request->validate([
            'clinic_id'   => ['nullable', 'exists:clinics,id'],
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
            $folder           = $data['media_type'] === 'video' ? 'banners/videos' : 'banners/images';
            $data['media_path'] = $request->file('media')->store($folder, 'public');
        }
        unset($data['media']);

        $banner->update($data);

        $this->logUpdate($banner, ['title' => $data['title']]);

        return redirect()->route('admin.banners.index')->with('success', "Banner yangilandi.");
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->logDelete($banner, ['title' => $banner->title]);
        if ($banner->media_path) {
            Storage::disk('public')->delete($banner->media_path);
        }
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', "Banner o'chirildi.");
    }
}
