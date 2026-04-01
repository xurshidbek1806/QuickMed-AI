<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::with('clinic:id,name')
            ->whereIn('role', ['super_admin', 'clinic_admin'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('admin/Users/Index', ['users' => $users]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Users/Form', [
            'clinics' => Clinic::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'role'      => ['required', 'in:super_admin,clinic_admin'],
            'clinic_id' => ['nullable', 'uuid', 'exists:clinics,id'],
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'clinic_id' => $data['role'] === 'clinic_admin' ? $data['clinic_id'] : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', "Foydalanuvchi yaratildi.");
    }

    public function edit(User $user): Response
    {
        return Inertia::render('admin/Users/Form', [
            'user'    => $user->load('clinic:id,name'),
            'clinics' => Clinic::where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', "unique:users,email,{$user->id}"],
            'password'  => ['nullable', 'string', 'min:8'],
            'role'      => ['required', 'in:super_admin,clinic_admin'],
            'clinic_id' => ['nullable', 'uuid', 'exists:clinics,id'],
        ]);

        $user->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'clinic_id' => $data['role'] === 'clinic_admin' ? $data['clinic_id'] : null,
        ]);

        if (!empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        return redirect()->route('admin.users.index')->with('success', "Foydalanuvchi yangilandi.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', "O'zingizni o'chira olmaysiz.");
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', "Foydalanuvchi o'chirildi.");
    }
}
