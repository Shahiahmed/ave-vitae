<?php

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Patient;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Пациенты
|--------------------------------------------------------------------------
*/

dataset('patient_view', [
    'admin viewAny patients' => [Role::Admin, true],
    'operator viewAny patients' => [Role::Operator, true],
    'reception viewAny patients' => [Role::Reception, false],
    'doctor viewAny patients' => [Role::Doctor, false],
]);

it('gates patient list by role', function (Role $role, bool $allowed) {
    expect(userWithRole($role)->can('viewAny', Patient::class))->toBe($allowed);
})->with('patient_view');

it('gates patient creation by role', function (Role $role, bool $allowed) {
    expect(userWithRole($role)->can('create', Patient::class))->toBe($allowed);
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, true],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

it('allows only admin to delete patients', function (Role $role, bool $allowed) {
    $patient = Patient::factory()->create();
    expect(userWithRole($role)->can('delete', $patient))->toBe($allowed);
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, false],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

/*
|--------------------------------------------------------------------------
| Записи (appointments)
|--------------------------------------------------------------------------
*/

it('gates appointment list by role', function (Role $role, bool $allowed) {
    expect(userWithRole($role)->can('viewAny', Appointment::class))->toBe($allowed);
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, true],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

it('allows only admin to delete appointments', function (Role $role, bool $allowed) {
    $appointment = Appointment::factory()->create();
    expect(userWithRole($role)->can('delete', $appointment))->toBe($allowed);
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, false],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

it('lets admin, operator and reception change visit status', function (Role $role, bool $allowed) {
    $appointment = Appointment::factory()->create();
    expect(userWithRole($role)->can('updateVisitStatus', $appointment))->toBe($allowed);
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, true],
    'reception' => [Role::Reception, true],
    'doctor' => [Role::Doctor, false],
]);

it('lets a doctor complete only their own appointment', function () {
    $doctor = userWithRole(Role::Doctor);
    $otherDoctor = userWithRole(Role::Doctor);

    $own = Appointment::factory()->create(['doctor_id' => $doctor->id]);
    $foreign = Appointment::factory()->create(['doctor_id' => $otherDoctor->id]);

    expect($doctor->can('complete', $own))->toBeTrue()
        ->and($doctor->can('complete', $foreign))->toBeFalse();
});

it('forbids operator and reception from completing appointments', function (Role $role) {
    $appointment = Appointment::factory()->create();
    expect(userWithRole($role)->can('complete', $appointment))->toBeFalse();
})->with([
    'operator' => [Role::Operator],
    'reception' => [Role::Reception],
]);

/*
|--------------------------------------------------------------------------
| Отделения и пользователи — только admin
|--------------------------------------------------------------------------
*/

it('restricts department management to admin', function (Role $role, bool $allowed) {
    expect(userWithRole($role)->can('viewAny', Department::class))->toBe($allowed);
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, false],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

it('restricts user management to admin', function (Role $role, bool $allowed) {
    expect(userWithRole($role)->can('viewAny', User::class))->toBe($allowed);
})->with([
    'admin' => [Role::Admin, true],
    'operator' => [Role::Operator, false],
    'reception' => [Role::Reception, false],
    'doctor' => [Role::Doctor, false],
]);

it('forbids an admin from deleting themselves', function () {
    $admin = userWithRole(Role::Admin);
    expect($admin->can('delete', $admin))->toBeFalse();
});
