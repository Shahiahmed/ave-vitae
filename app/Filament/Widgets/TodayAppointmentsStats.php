<?php

namespace App\Filament\Widgets;

use App\Enums\VisitStatus;
use App\Filament\Pages\TodayAppointments;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TodayAppointmentsStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return TodayAppointments::class;
    }

    protected function getStats(): array
    {
        $countBy = fn (VisitStatus $status): int => (clone $this->getPageTableQuery())
            ->where('visit_status', $status->value)
            ->count();

        $total = (clone $this->getPageTableQuery())->count();

        return [
            Stat::make(__('clinic.today.total'), $total)
                ->color('gray'),
            Stat::make(__('clinic.today.arrived'), $countBy(VisitStatus::Arrived))
                ->color('info'),
            Stat::make(__('clinic.today.waiting'), $countBy(VisitStatus::Waiting))
                ->color('warning'),
            Stat::make(__('clinic.today.no_show'), $countBy(VisitStatus::NoShow))
                ->color('danger'),
        ];
    }
}
