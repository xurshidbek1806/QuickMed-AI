<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Clinic;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Recommendation;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'clinics'         => Clinic::count(),
                'diseases'        => Disease::count(),
                'doctors'         => Doctor::count(),
                'banners'         => Banner::count(),
                'users'           => User::whereIn('role', ['super_admin', 'clinic_admin'])->count(),
                'recommendations' => Recommendation::count(),
            ],
        ]);
    }
}
