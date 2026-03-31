<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DoctorController extends Controller
{
    private function clinicId(): int
    {
        return auth()->user()->clinic_id;
    }

    public function index(): Response
    {
        $doctors = Doctor::where('clinic_id', $this->clinicId())
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('clinic/Doctors/Index', ['doctors' => $doctors]);
    }

    public function create(): Response
    {
        return Inertia::render('clinic/Doctors/Form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:500'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'phone_number'   => ['nullable', 'string', 'max:50'],
            'location_url'   => ['nullable', 'url', 'max:2000'],
            'photo'          => ['nullable', 'image', 'max:2048'],
            'is_active'      => ['boolean'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $data['clinic_id'] = $this->clinicId();

        Doctor::create($data);

        return redirect()->route('clinic.doctors.index')->with('success', "Shifokor qo'shildi.");
    }

    public function edit(Doctor $doctor): Response
    {
        abort_unless($doctor->clinic_id === $this->clinicId(), 403);

        return Inertia::render('clinic/Doctors/Form', ['doctor' => $doctor]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        abort_unless($doctor->clinic_id === $this->clinicId(), 403);

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:500'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'phone_number'   => ['nullable', 'string', 'max:50'],
            'location_url'   => ['nullable', 'url', 'max:2000'],
            'photo'          => ['nullable', 'image', 'max:2048'],
            'is_active'      => ['boolean'],
        ]);

        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $doctor->update($data);

        return redirect()->route('clinic.doctors.index')->with('success', "Shifokor yangilandi.");
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        abort_unless($doctor->clinic_id === $this->clinicId(), 403);

        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }
        $doctor->delete();

        return redirect()->route('clinic.doctors.index')->with('success', "Shifokor o'chirildi.");
    }
}
