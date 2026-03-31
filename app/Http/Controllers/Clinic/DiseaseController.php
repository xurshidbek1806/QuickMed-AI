<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DiseaseController extends Controller
{
    private function clinicId(): int
    {
        return auth()->user()->clinic_id ?? abort(403, 'Shifoxona tayinlanmagan.');
    }

    public function index(): Response
    {
        $diseases = Disease::where('clinic_id', $this->clinicId())
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('clinic/Diseases/Index', ['diseases' => $diseases]);
    }

    public function create(): Response
    {
        return Inertia::render('clinic/Diseases/Form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:500'],
            'category'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'min_age'     => ['required', 'integer', 'min:0', 'max:150'],
            'max_age'     => ['required', 'integer', 'min:0', 'max:150', 'gte:min_age'],
            'is_active'   => ['boolean'],
        ]);

        $data['clinic_id'] = $this->clinicId();

        Disease::create($data);

        return redirect()->route('clinic.diseases.index')->with('success', "Kasallik qo'shildi.");
    }

    public function edit(Disease $disease): Response
    {
        abort_unless($disease->clinic_id === $this->clinicId(), 403);

        return Inertia::render('clinic/Diseases/Form', ['disease' => $disease]);
    }

    public function update(Request $request, Disease $disease): RedirectResponse
    {
        abort_unless($disease->clinic_id === $this->clinicId(), 403);

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:500'],
            'category'    => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'min_age'     => ['required', 'integer', 'min:0', 'max:150'],
            'max_age'     => ['required', 'integer', 'min:0', 'max:150', 'gte:min_age'],
            'is_active'   => ['boolean'],
        ]);

        $disease->update($data);

        return redirect()->route('clinic.diseases.index')->with('success', "Kasallik yangilandi.");
    }

    public function destroy(Disease $disease): RedirectResponse
    {
        abort_unless($disease->clinic_id === $this->clinicId(), 403);

        $disease->delete();

        return redirect()->route('clinic.diseases.index')->with('success', "Kasallik o'chirildi.");
    }
}
