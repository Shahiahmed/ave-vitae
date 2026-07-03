<?php

use App\Enums\Role;
use App\Enums\VisitStatus;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;

it('lets admin and operator open the appointment resource', function (Role $role) {
    $user = userWithRole($role);

    $this->actingAs($user)->get(AppointmentResource::getUrl('index'))->assertOk();
    $this->actingAs($user)->get(AppointmentResource::getUrl('create'))->assertOk();
})->with([
    'admin' => [Role::Admin],
    'operator' => [Role::Operator],
]);

it('forbids reception and doctor from the appointment resource', function (Role $role) {
    $this->actingAs(userWithRole($role))
        ->get(AppointmentResource::getUrl('index'))
        ->assertForbidden();
})->with([
    'reception' => [Role::Reception],
    'doctor' => [Role::Doctor],
]);

it('creates an appointment with default waiting status', function () {
    $patient = Patient::factory()->create();
    $department = Department::factory()->create();

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'department_id' => $department->id,
        'scheduled_at' => now()->addDay(),
    ]);

    expect($appointment->refresh()->visit_status)->toBe(VisitStatus::Waiting)
        ->and($appointment->patient->is($patient))->toBeTrue()
        ->and($appointment->department->is($department))->toBeTrue()
        ->and($appointment->doctor)->toBeNull();
});
