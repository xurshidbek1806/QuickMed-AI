<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Traits\LogsAdminActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DoctorController extends Controller
{
    use LogsAdminActions;
    public function index(): Response
    {
        $doctors = Doctor::with('clinic:id,name')
            ->orderByDesc('created_at')
            ->paginate(25);

        return Inertia::render('admin/Doctors/Index', ['doctors' => $doctors]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Doctors/Form', [
            'clinics' => Clinic::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'clinic_id'      => ['nullable', 'exists:clinics,id'],
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

        $doctor = Doctor::create($data);

        $this->logCreate($doctor, ['name' => $data['name']]);

        return redirect()->route('admin.doctors.index')->with('success', "Shifokor qo'shildi.");
    }

    public function edit(Doctor $doctor): Response
    {
        return Inertia::render('admin/Doctors/Form', [
            'doctor'  => $doctor,
            'clinics' => Clinic::active()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $data = $request->validate([
            'clinic_id'      => ['nullable', 'exists:clinics,id'],
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

        $this->logUpdate($doctor, ['name' => $data['name']]);

        return redirect()->route('admin.doctors.index')->with('success', "Shifokor yangilandi.");
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $this->logDelete($doctor, ['name' => $doctor->name]);
        if ($doctor->photo) {
            Storage::disk('public')->delete($doctor->photo);
        }
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', "Shifokor o'chirildi.");
    }
}
