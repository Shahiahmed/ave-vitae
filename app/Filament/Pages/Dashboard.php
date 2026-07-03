<?php

namespace App\Filament\Pages;

use App\Enums\Role;
use App\Filament\Widgets\ClinicStats;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard
{
    public function getTitle(): string
    {
        return __('clinic.dashboard.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('clinic.dashboard.title');
    }

    public static function canAccess(): bool
    {
        return Auth::user()?->hasAnyRole([Role::Admin->value, Role::Operator->value]) ?? false;
    }

    public function getWidgets(): array
    {
        return [
            ClinicStats::class,
            AccountWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}
