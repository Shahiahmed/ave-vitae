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

it('shows an appointment scheduled for today under the default date filter', function () {
    $patient = \App\Models\Patient::factory()->create(['name_kk' => 'Бүгінгі Пациент']);
    \App\Models\Appointment::factory()->create([
        'patient_id' => $patient->id,
        'scheduled_at' => today()->setTime(0, 0),
    ]);

    $this->actingAs(userWithRole(Role::Reception))
        ->get(TodayAppointments::getUrl())
        ->assertOk()
        ->assertSee('Бүгінгі Пациент');
});

it('renders inline stat counters on the today page', function () {
    Appointment::factory()->create(['scheduled_at' => today()->setTime(9, 0), 'visit_status' => VisitStatus::Waiting]);
    Appointment::factory()->create(['scheduled_at' => today()->setTime(10, 0), 'visit_status' => VisitStatus::Arrived]);
    Appointment::factory()->create(['scheduled_at' => today()->setTime(11, 0), 'visit_status' => VisitStatus::NoShow]);

    $this->actingAs(userWithRole(Role::Reception))
        ->get(TodayAppointments::getUrl())
        ->assertOk()
        ->assertSee(__('clinic.today.total'))
        ->assertSee(__('clinic.today.arrived'));
});

it('exports the today table via a livewire action without crashing', function () {
    $this->actingAs(userWithRole(Role::Reception));
    Appointment::factory()->create(['scheduled_at' => today()->setTime(9, 0)]);

    Livewire\Livewire::test(TodayAppointments::class)
        ->searchTable('7')
        ->callTableAction('export')
        ->assertFileDownloaded();
});
