<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Clinic;
use App\Models\Disease;
use App\Models\Doctor;
use App\Models\Recommendation;
use App\Models\User;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function __construct(private StatisticsService $statistics) {}

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
            'interaction_stats' => $this->statistics->getOverview(),
            'top_diseases'      => $this->statistics->getTopDiseases(5),
            'top_doctors'       => $this->statistics->getTopDoctors(5),
            'age_categories'    => $this->statistics->getAgeCategoryStats(),
            'gender_stats'      => $this->statistics->getGenderStats(),
            'daily_chart'       => $this->statistics->getDailyInteractions(14),
            'recent_actions'    => $this->statistics->getRecentAdminActions(10),
        ]);
    }

    public function exportStatistics(): StreamedResponse
    {
        $data = $this->statistics->getExportData();

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");

            // Overview
            fputcsv($handle, ['=== UMUMIY STATISTIKA ===']);
            fputcsv($handle, ['Ko\'rsatkich', 'Qiymat']);
            fputcsv($handle, ['Jami murojaat', $data['overview']['total_interactions']]);
            fputcsv($handle, ['Matn orqali', $data['overview']['text_interactions']]);
            fputcsv($handle, ['Ovoz orqali', $data['overview']['voice_interactions']]);
            fputcsv($handle, ['Bugun', $data['overview']['today_interactions']]);
            fputcsv($handle, ['Oxirgi 7 kun', $data['overview']['week_interactions']]);
            fputcsv($handle, ['Haftalik unikal foydalanuvchilar', $data['overview']['unique_users_week']]);
            fputcsv($handle, []);

            // Top diseases
            fputcsv($handle, ['=== TOP KASALLIKLAR ===']);
            fputcsv($handle, ['Kasallik', 'Kategoriya', 'Soni']);
            foreach ($data['top_diseases'] as $d) {
                fputcsv($handle, [$d['name'], $d['category'], $d['count']]);
            }
            fputcsv($handle, []);

            // Top doctors
            fputcsv($handle, ['=== TOP SHIFOKORLAR ===']);
            fputcsv($handle, ['Shifokor', 'Mutaxassislik', 'Soni']);
            foreach ($data['top_doctors'] as $d) {
                fputcsv($handle, [$d['name'], $d['specialization'], $d['count']]);
            }
            fputcsv($handle, []);

            // Age categories
            fputcsv($handle, ['=== YOSH GURUHLARI ===']);
            fputcsv($handle, ['Yosh guruhi', 'Soni']);
            foreach ($data['age_categories'] as $a) {
                fputcsv($handle, [$a['age_category'], $a['count']]);
            }
            fputcsv($handle, []);

            // Gender stats
            fputcsv($handle, ['=== JINS BO\'YICHA ===']);
            fputcsv($handle, ['Jins', 'Soni']);
            foreach ($data['gender_stats'] as $g) {
                fputcsv($handle, [$g['gender'] === 'male' ? 'Erkak' : 'Ayol', $g['count']]);
            }
            fputcsv($handle, []);

            // Daily
            fputcsv($handle, ['=== KUNLIK MUROJAAT ===']);
            fputcsv($handle, ['Sana', 'Soni']);
            foreach ($data['daily'] as $d) {
                fputcsv($handle, [$d['date'], $d['count']]);
            }

            fclose($handle);
        }, 'quickmedai-statistics-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
