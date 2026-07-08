<?php

use App\Models\Appointment;
use App\Support\AppointmentsExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

it('builds an 8-column row from an appointment', function () {
    $appointment = Appointment::factory()->create();

    $row = AppointmentsExport::rowFor($appointment->load(['patient', 'department', 'doctor']));

    expect($row)->toHaveCount(8)
        ->and($row[1])->toBe($appointment->patient->name_kk);
});

it('exports the filtered query to an xlsx download', function () {
    Appointment::factory()->count(3)->create();

    $response = AppointmentsExport::download(Appointment::query(), 'today.xlsx');

    expect($response)->toBeInstanceOf(BinaryFileResponse::class)
        ->and($response->getFile()->getSize())->toBeGreaterThan(0)
        ->and($response->headers->get('content-disposition'))->toContain('today.xlsx');
});
