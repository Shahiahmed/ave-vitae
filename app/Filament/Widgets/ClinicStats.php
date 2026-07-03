<?php

namespace App\Filament\Widgets;

use App\Enums\Role;
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
        $todayCount = Appointment::query()
            ->whereDate('scheduled_at', today())
            ->count();

        $arrivedWeek = Appointment::query()
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereIn('visit_status', [
                VisitStatus::Arrived->value,
                VisitStatus::InProgress->value,
                VisitStatus::Completed->value,
            ])
            ->count();

        $noShowWeek = Appointment::query()
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('visit_status', VisitStatus::NoShow->value)
            ->count();

        $newPatientsMonth = Patient::query()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        return [
            Stat::make(__('clinic.dashboard.today'), $todayCount)
                ->description(__('clinic.dashboard.today_desc'))
                ->color('primary'),
            Stat::make(__('clinic.dashboard.arrived_week'), $arrivedWeek)
                ->color('success'),
            Stat::make(__('clinic.dashboard.no_show_week'), $noShowWeek)
                ->color('danger'),
            Stat::make(__('clinic.dashboard.new_patients_month'), $newPatientsMonth)
                ->color('info'),
        ];
    }
}
