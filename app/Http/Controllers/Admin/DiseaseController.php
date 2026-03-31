<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Disease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DiseaseController extends Controller
{
    public function index(): Response
    {
        $diseases = Disease::with('clinic:id,name')
            ->orderByDesc('created_at')
            ->paginate(25);

        return Inertia::render('admin/Diseases/Index', ['diseases' => $diseases]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Diseases/Form', [
            'clinics' => Clinic::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'clinic_id'   => ['nullable', 'exists:clinics,id'],
            'name'        => ['required', 'string', 'max:500'],
            'category'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'min_age'     => ['required', 'integer', 'min:0', 'max:150'],
            'max_age'     => ['required', 'integer', 'min:0', 'max:150', 'gte:min_age'],
            'is_active'   => ['boolean'],
        ]);

        Disease::create($data);

        return redirect()->route('admin.diseases.index')->with('success', "Kasallik qo'shildi.");
    }

    public function edit(Disease $disease): Response
    {
        return Inertia::render('admin/Diseases/Form', [
            'disease' => $disease,
            'clinics' => Clinic::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Disease $disease): RedirectResponse
    {
        $data = $request->validate([
            'clinic_id'   => ['nullable', 'exists:clinics,id'],
            'name'        => ['required', 'string', 'max:500'],
            'category'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'min_age'     => ['required', 'integer', 'min:0', 'max:150'],
            'max_age'     => ['required', 'integer', 'min:0', 'max:150', 'gte:min_age'],
            'is_active'   => ['boolean'],
        ]);

        $disease->update($data);

        return redirect()->route('admin.diseases.index')->with('success', "Kasallik yangilandi.");
    }

    public function destroy(Disease $disease): RedirectResponse
    {
        $disease->delete();
        return redirect()->route('admin.diseases.index')->with('success', "Kasallik o'chirildi.");
    }
}
