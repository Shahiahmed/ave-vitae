<?php

use App\Enums\Role;
use App\Filament\Pages\Dashboard;
use App\Models\Appointment;

it('shows the dashboard to admin and operator, and redirects the others to their page', function (Role $role, bool $allowed) {
    Appointment::factory()->create(['scheduled_at' => now()]);

    $response = $this->actingAs(userWithRole($role))->get(Dashboard::getUrl());

    // Роли без дашборда не должны получать 403 — их уводит на свою страницу.
    $allowed ? $response->assertOk() : $response->assertRedirect();
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, true],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

it('redirects a doctor from the dashboard to my appointments', function () {
    $this->actingAs(userWithRole(Role::Doctor))
        ->get(Dashboard::getUrl())
        ->assertRedirect(App\Filament\Pages\MyAppointments::getUrl());
});

it('points each role at an accessible home url', function (Role $role, string $expectedUrl) {
    $this->actingAs(userWithRole($role));

    expect(Filament\Facades\Filament::getHomeUrl())->toContain($expectedUrl);
})->with([
    'doctor to my appointments' => [Role::Doctor, '/my-appointments'],
    'reception to today' => [Role::Reception, '/today-appointments'],
    'admin to dashboard' => [Role::Admin, '/admin'],
]);
