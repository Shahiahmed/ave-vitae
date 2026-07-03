<?php

use App\Enums\Role;
use App\Filament\Pages\MyAppointments;
use App\Models\Appointment;
use App\Models\Patient;

it('allows only doctors to open the my-appointments page', function (Role $role, bool $allowed) {
    $response = $this->actingAs(userWithRole($role))->get(MyAppointments::getUrl());

    $allowed ? $response->assertOk() : $response->assertForbidden();
})->with([
    'doctor' => [Role::Doctor, true],
    'admin' => [Role::Admin, false],
    'operator' => [Role::Operator, false],
    'reception' => [Role::Reception, false],
]);

it('shows a doctor only their own appointments', function () {
    $doctor = userWithRole(Role::Doctor);
    $otherDoctor = userWithRole(Role::Doctor);

    $mine = Patient::factory()->create(['name_kk' => 'Мой Пациент']);
    $foreign = Patient::factory()->create(['name_kk' => 'Чужой Пациент']);

    Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $mine->id,
        'scheduled_at' => now()->addHour(),
    ]);
    Appointment::factory()->create([
        'doctor_id' => $otherDoctor->id,
        'patient_id' => $foreign->id,
        'scheduled_at' => now()->addHour(),
    ]);

    $this->actingAs($doctor)
        ->get(MyAppointments::getUrl())
        ->assertOk()
        ->assertSee('Мой Пациент')
        ->assertDontSee('Чужой Пациент');
});
