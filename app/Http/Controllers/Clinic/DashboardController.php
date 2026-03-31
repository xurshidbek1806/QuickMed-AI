<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Recommendation;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $clinicId = auth()->user()->clinic_id;

        return Inertia::render('clinic/Dashboard', [
            'stats' => [
                'diseases'        => Disease::where('clinic_id', $clinicId)->count(),
                'doctors'         => Doctor::where('clinic_id', $clinicId)->count(),
                'recommendations' => Recommendation::whereHas('disease', fn($q) => $q->where('clinic_id', $clinicId))->count(),
                'banners'         => Banner::where('clinic_id', $clinicId)->count(),
            ],
            'clinic' => auth()->user()->clinic,
        ]);
    }
}
