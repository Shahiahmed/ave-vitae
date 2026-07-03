<?php

use App\Enums\Role;
use App\Filament\Pages\Dashboard;
use App\Models\Appointment;

it('shows the dashboard only to admin and operator', function (Role $role, bool $allowed) {
    Appointment::factory()->create(['scheduled_at' => now()]);

    $response = $this->actingAs(userWithRole($role))->get(Dashboard::getUrl());

    $allowed ? $response->assertOk() : $response->assertForbidden();
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, true],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

it('points each role at an accessible home url', function (Role $role, string $expectedUrl) {
    $this->actingAs(userWithRole($role));

    expect(Filament\Facades\Filament::getHomeUrl())->toContain($expectedUrl);
})->with([
    'doctor to my appointments' => [Role::Doctor, '/my-appointments'],
    'reception to today' => [Role::Reception, '/today-appointments'],
    'admin to dashboard' => [Role::Admin, '/admin'],
]);
