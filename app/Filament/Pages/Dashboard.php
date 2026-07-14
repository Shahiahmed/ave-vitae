<?php

namespace App\Filament\Pages;

use App\Enums\Role;
use App\Filament\Widgets\ClinicStats;
use Filament\Facades\Filament;
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

    /**
     * Страница доступна всем, кто вошёл в панель: роли без дашборда
     * не получают 403, а перенаправляются на свою страницу (см. mount()).
     */
    public static function canAccess(): bool
    {
        return Auth::check();
    }

    /**
     * В меню дашборд показываем только тем, у кого он есть.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return static::hasDashboard();
    }

    /**
     * Ресепшн и врач вместо 403 уезжают на свою домашнюю страницу.
     */
    public function mount(): void
    {
        if (! static::hasDashboard()) {
            $this->redirect(Filament::getHomeUrl());
        }
    }

    private static function hasDashboard(): bool
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
