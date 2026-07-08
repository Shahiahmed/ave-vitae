<?php

namespace App\Filament\Widgets;

use App\Enums\Role;
use App\Enums\TreatmentStatus;
use App\Enums\VisitStatus;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ClinicStats extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return Auth::user()?->hasAnyRole([Role::Admin->value, Role::Operator->value]) ?? false;
    }

    protected function getStats(): array
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();
        $monthName = \Illuminate\Support\Str::ucfirst(
            now()->locale(app()->getLocale())->isoFormat('MMMM')
        );

        $todayCount = Appointment::query()
            ->whereDate('scheduled_at', today())
            ->count();

        $arrivedMonth = Appointment::query()
            ->whereBetween('scheduled_at', [$monthStart, $monthEnd])
            ->whereIn('visit_status', [
                VisitStatus::Arrived->value,
                VisitStatus::InProgress->value,
                VisitStatus::Completed->value,
            ])
            ->count();

        $treatedMonth = Appointment::query()
            ->whereBetween('scheduled_at', [$monthStart, $monthEnd])
            ->where('treatment_status', TreatmentStatus::Treated->value)
            ->count();

        $newPatientsMonth = Patient::query()
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->count();

        return [
            Stat::make(__('clinic.dashboard.today'), $todayCount)
                ->description(__('clinic.dashboard.today_desc'))
                ->color('primary'),
            Stat::make(__('clinic.dashboard.arrived_month'), $arrivedMonth)
                ->description($monthName)
                ->color('success'),
            Stat::make(__('clinic.dashboard.treated_month'), $treatedMonth)
                ->description($monthName)
                ->color('info'),
            Stat::make(__('clinic.dashboard.new_patients_month'), $newPatientsMonth)
                ->description($monthName)
                ->color('warning'),
        ];
    }
}
