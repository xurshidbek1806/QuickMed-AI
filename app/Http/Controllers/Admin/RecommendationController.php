<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Recommendation;
use App\Traits\LogsAdminActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RecommendationController extends Controller
{
    use LogsAdminActions;
    public function index(): Response
    {
        $recommendations = Recommendation::with(['disease:id,name,category', 'doctor:id,name,specialization'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('admin/Recommendations/Index', ['recommendations' => $recommendations]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Recommendations/Form', [
            'diseases' => Disease::active()->orderBy('name')->get(['id', 'name', 'category']),
            'doctors'  => Doctor::active()->orderBy('name')->get(['id', 'name', 'specialization']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'disease_id'          => ['required', 'uuid', 'exists:diseases,id'],
            'doctor_id'           => ['required', 'uuid', 'exists:doctors,id'],
            'recommendation_text' => ['required', 'string', 'max:5000'],
            'priority'            => ['required', 'integer', 'min:0', 'max:100'],
            'is_active'           => ['boolean'],
        ]);

        $recommendation = Recommendation::create($data);

        $this->logCreate($recommendation, ['disease_id' => $data['disease_id'], 'doctor_id' => $data['doctor_id']]);

        return redirect()->route('admin.recommendations.index')->with('success', "Tavsiya yaratildi.");
    }

    public function edit(Recommendation $recommendation): Response
    {
        $recommendation->load(['disease:id,name', 'doctor:id,name']);

        return Inertia::render('admin/Recommendations/Form', [
            'recommendation' => $recommendation,
            'diseases'       => Disease::active()->orderBy('name')->get(['id', 'name', 'category']),
            'doctors'        => Doctor::active()->orderBy('name')->get(['id', 'name', 'specialization']),
        ]);
    }

    public function update(Request $request, Recommendation $recommendation): RedirectResponse
    {
        $data = $request->validate([
            'disease_id'          => ['required', 'uuid', 'exists:diseases,id'],
            'doctor_id'           => ['required', 'uuid', 'exists:doctors,id'],
            'recommendation_text' => ['required', 'string', 'max:5000'],
            'priority'            => ['required', 'integer', 'min:0', 'max:100'],
            'is_active'           => ['boolean'],
        ]);

        $recommendation->update($data);

        $this->logUpdate($recommendation, ['disease_id' => $data['disease_id'], 'doctor_id' => $data['doctor_id']]);

        return redirect()->route('admin.recommendations.index')->with('success', "Tavsiya yangilandi.");
    }

    public function destroy(Recommendation $recommendation): RedirectResponse
    {
        $this->logDelete($recommendation);
        $recommendation->delete();
        return redirect()->route('admin.recommendations.index')->with('success', "Tavsiya o'chirildi.");
    }
}
