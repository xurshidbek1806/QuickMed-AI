<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use App\Traits\LogsAdminActions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ClinicController extends Controller
{
    use LogsAdminActions;
    public function index(): Response
    {
        $clinics = Clinic::withCount(['diseases', 'doctors', 'banners', 'users'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('admin/Clinics/Index', ['clinics' => $clinics]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Clinics/Form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:500'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'email'       => ['nullable', 'email', 'unique:clinics,email'],
            'website'     => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active'   => ['boolean'],
            // Admin account for this clinic
            'admin_name'     => ['required', 'string', 'max:255'],
            'admin_email'    => ['required', 'email', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8'],
        ]);

        $clinic = Clinic::create([
            'name'        => $data['name'],
            'address'     => $data['address'] ?? null,
            'phone'       => $data['phone'] ?? null,
            'email'       => $data['email'] ?? null,
            'website'     => $data['website'] ?? null,
            'description' => $data['description'] ?? null,
            'is_active'   => $data['is_active'] ?? true,
        ]);

        $adminUser = User::create([
            'name'      => $data['admin_name'],
            'email'     => $data['admin_email'],
            'password'  => Hash::make($data['admin_password']),
            'role'      => 'clinic_admin',
            'clinic_id' => $clinic->id,
        ]);

        $this->logCreate($clinic, ['name' => $data['name'], 'admin_user_id' => $adminUser->id]);

        return redirect()->route('admin.clinics.index')->with('success', "Shifoxona yaratildi.");
    }

    public function edit(Clinic $clinic): Response
    {
        $clinic->load('users');
        return Inertia::render('admin/Clinics/Form', ['clinic' => $clinic]);
    }

    public function update(Request $request, Clinic $clinic): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['nullable', 'string', 'max:500'],
            'phone'       => ['nullable', 'string', 'max:50'],
            'email'       => ['nullable', 'email', "unique:clinics,email,{$clinic->id}"],
            'website'     => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_active'   => ['boolean'],
        ]);

        $clinic->update($data);

        $this->logUpdate($clinic, ['name' => $data['name']]);

        return redirect()->route('admin.clinics.index')->with('success', "Shifoxona yangilandi.");
    }

    public function destroy(Clinic $clinic): RedirectResponse
    {
        $this->logDelete($clinic, ['name' => $clinic->name]);
        $clinic->delete();
        return redirect()->route('admin.clinics.index')->with('success', "Shifoxona o'chirildi.");
    }
}
