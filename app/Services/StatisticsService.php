<?php

namespace App\Services;

use App\Models\AdminAction;
use App\Models\UserInteraction;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function getOverview(): array
    {
        return [
            'total_interactions' => UserInteraction::count(),
            'text_interactions'  => UserInteraction::where('input_type', 'text')->count(),
            'voice_interactions' => UserInteraction::where('input_type', 'voice')->count(),
            'today_interactions' => UserInteraction::whereDate('created_at', today())->count(),
            'week_interactions'  => UserInteraction::where('created_at', '>=', now()->subDays(7))->count(),
            'unique_users_week'  => UserInteraction::where('created_at', '>=', now()->subDays(7))
                ->distinct('ip_address')->count('ip_address'),
        ];
    }

    public function getTopDiseases(int $limit = 10): array
    {
        return UserInteraction::query()
            ->select('disease_id', DB::raw('COUNT(*) as count'))
            ->whereNotNull('disease_id')
            ->groupBy('disease_id')
            ->orderByDesc('count')
            ->limit($limit)
            ->with('disease:id,name,category')
            ->get()
            ->map(fn($row) => [
                'disease_id' => $row->disease_id,
                'name'       => $row->disease?->name ?? 'Noma\'lum',
                'category'   => $row->disease?->category ?? '',
                'count'      => $row->count,
            ])
            ->toArray();
    }

    public function getTopDoctors(int $limit = 10): array
    {
        return UserInteraction::query()
            ->select('doctor_id', DB::raw('COUNT(*) as count'))
            ->whereNotNull('doctor_id')
            ->groupBy('doctor_id')
            ->orderByDesc('count')
            ->limit($limit)
            ->with('doctor:id,name,specialization')
            ->get()
            ->map(fn($row) => [
                'doctor_id'      => $row->doctor_id,
                'name'           => $row->doctor?->name ?? 'Noma\'lum',
                'specialization' => $row->doctor?->specialization ?? '',
                'count'          => $row->count,
            ])
            ->toArray();
    }

    public function getAgeCategoryStats(): array
    {
        return UserInteraction::query()
            ->select('age_category', DB::raw('COUNT(*) as count'))
            ->whereNotNull('age_category')
            ->where('age_category', '!=', '')
            ->groupBy('age_category')
            ->orderByDesc('count')
            ->get()
            ->map(fn($row) => [
                'age_category' => $row->age_category,
                'count'        => $row->count,
            ])
            ->toArray();
    }

    public function getGenderStats(): array
    {
        return UserInteraction::query()
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->whereNotNull('gender')
            ->where('gender', '!=', '')
            ->groupBy('gender')
            ->orderByDesc('count')
            ->get()
            ->map(fn($row) => [
                'gender' => $row->gender,
                'count'  => $row->count,
            ])
            ->toArray();
    }

    public function getDailyInteractions(int $days = 30): array
    {
        return UserInteraction::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->map(fn($row) => [
                'date'  => $row->date,
                'count' => $row->count,
            ])
            ->toArray();
    }

    public function getRecentAdminActions(int $limit = 20): array
    {
        return AdminAction::query()
            ->with('admin:id,name')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn($a) => [
                'id'          => $a->id,
                'admin_name'  => $a->admin?->name ?? 'Noma\'lum',
                'action_type' => $a->action_type,
                'entity_type' => $a->entity_type,
                'details'     => $a->action_details,
                'ip_address'  => $a->ip_address,
                'created_at'  => $a->created_at->format('Y-m-d H:i'),
            ])
            ->toArray();
    }

    public function getExportData(): array
    {
        return [
            'overview'       => $this->getOverview(),
            'top_diseases'   => $this->getTopDiseases(20),
            'top_doctors'    => $this->getTopDoctors(20),
            'age_categories' => $this->getAgeCategoryStats(),
            'gender_stats'   => $this->getGenderStats(),
            'daily'          => $this->getDailyInteractions(30),
        ];
    }
}
