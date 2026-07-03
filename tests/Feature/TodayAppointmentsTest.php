<?php

use App\Enums\Role;
use App\Enums\VisitStatus;
use App\Filament\Pages\TodayAppointments;
use App\Models\Appointment;

it('lets reception, operator and admin open the today page', function (Role $role) {
    Appointment::factory()->create(['scheduled_at' => now()]);

    $this->actingAs(userWithRole($role))
        ->get(TodayAppointments::getUrl())
        ->assertOk();
})->with([
    'admin' => [Role::Admin],
    'operator' => [Role::Operator],
    'reception' => [Role::Reception],
]);

it('forbids a doctor from the today page', function () {
    $this->actingAs(userWithRole(Role::Doctor))
        ->get(TodayAppointments::getUrl())
        ->assertForbidden();
});

it('renders the today page with its stats widget for a full day of visits', function () {
    Appointment::factory()->create(['scheduled_at' => now()->setTime(9, 0), 'visit_status' => VisitStatus::Waiting]);
    Appointment::factory()->create(['scheduled_at' => now()->setTime(10, 0), 'visit_status' => VisitStatus::Arrived]);
    Appointment::factory()->create(['scheduled_at' => now()->setTime(11, 0), 'visit_status' => VisitStatus::NoShow]);

    $this->actingAs(userWithRole(Role::Reception))
        ->get(TodayAppointments::getUrl())
        ->assertOk();
});
