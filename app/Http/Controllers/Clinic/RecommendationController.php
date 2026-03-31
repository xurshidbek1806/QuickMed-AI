<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Recommendation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RecommendationController extends Controller
{
    private function clinicId(): int
    {
        return auth()->user()->clinic_id;
    }

    public function index(): Response
    {
        $recommendations = Recommendation::with(['disease:id,name', 'doctor:id,name,specialization'])
            ->whereHas('disease', fn($q) => $q->where('clinic_id', $this->clinicId()))
            ->orderByDesc('priority')
            ->paginate(20);

        return Inertia::render('clinic/Recommendations/Index', ['recommendations' => $recommendations]);
    }

    public function create(): Response
    {
        return Inertia::render('clinic/Recommendations/Form', [
            'diseases' => Disease::where('clinic_id', $this->clinicId())->active()->get(['id', 'name']),
            'doctors'  => Doctor::where('clinic_id', $this->clinicId())->active()->get(['id', 'name', 'specialization']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'disease_id'          => ['required', 'uuid', 'exists:diseases,id'],
            'doctor_id'           => ['nullable', 'uuid', 'exists:doctors,id'],
            'recommendation_text' => ['required', 'string', 'min:10'],
            'priority'            => ['integer', 'min:0', 'max:100'],
            'is_active'           => ['boolean'],
        ]);

        // Ensure disease belongs to this clinic
        abort_unless(
            Disease::where('id', $data['disease_id'])->where('clinic_id', $this->clinicId())->exists(),
            403
        );

        Recommendation::create($data);

        return redirect()->route('clinic.recommendations.index')->with('success', "Tavsiya qo'shildi.");
    }

    public function edit(Recommendation $recommendation): Response
    {
        abort_unless(
            Disease::where('id', $recommendation->disease_id)->where('clinic_id', $this->clinicId())->exists(),
            403
        );

        return Inertia::render('clinic/Recommendations/Form', [
            'recommendation' => $recommendation,
            'diseases'       => Disease::where('clinic_id', $this->clinicId())->active()->get(['id', 'name']),
            'doctors'        => Doctor::where('clinic_id', $this->clinicId())->active()->get(['id', 'name', 'specialization']),
        ]);
    }

    public function update(Request $request, Recommendation $recommendation): RedirectResponse
    {
        abort_unless(
            Disease::where('id', $recommendation->disease_id)->where('clinic_id', $this->clinicId())->exists(),
            403
        );

        $data = $request->validate([
            'disease_id'          => ['required', 'uuid', 'exists:diseases,id'],
            'doctor_id'           => ['nullable', 'uuid', 'exists:doctors,id'],
            'recommendation_text' => ['required', 'string', 'min:10'],
            'priority'            => ['integer', 'min:0', 'max:100'],
            'is_active'           => ['boolean'],
        ]);

        $recommendation->update($data);

        return redirect()->route('clinic.recommendations.index')->with('success', "Tavsiya yangilandi.");
    }

    public function destroy(Recommendation $recommendation): RedirectResponse
    {
        abort_unless(
            Disease::where('id', $recommendation->disease_id)->where('clinic_id', $this->clinicId())->exists(),
            403
        );

        $recommendation->delete();

        return redirect()->route('clinic.recommendations.index')->with('success', "Tavsiya o'chirildi.");
    }
}
