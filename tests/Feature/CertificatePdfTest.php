<?php

use App\Enums\Role;
use App\Models\Appointment;
use App\Models\Patient;
use App\Support\CertificatePdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

it('generates a medical certificate pdf with kazakh and chinese text', function () {
    $patient = Patient::factory()->create([
        'name_kk' => 'Аленова Ляйлім Қапбасқызы',
        'name_zh' => '容德林',
        'iin' => '731130450143',
        'birth_date' => '1974-01-01',
    ]);
    $appointment = Appointment::factory()->create(['patient_id' => $patient->id]);

    $response = CertificatePdf::download(
        $appointment->load(['patient', 'department', 'doctor']),
        [
            'period_from' => '2026-06-27',
            'period_to' => '2026-07-04',
            'complaints' => 'Бас айналу және бас ауруы.',
            'examination' => 'Мойын омыртқасында ауырсыну.',
            'diagnosis' => 'Остеоартроз.',
            'treatment' => 'Массаж, физиотерапия, 14 күн демалыс.',
        ],
    );

    expect($response)->toBeInstanceOf(BinaryFileResponse::class)
        ->and($response->getFile()->getSize())->toBeGreaterThan(1000)
        ->and($response->headers->get('content-disposition'))->toContain('.pdf');
});

it('lets a doctor open the certificate action on their appointment', function () {
    $doctor = userWithRole(Role::Doctor);
    Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'scheduled_at' => now(),
    ]);

    $this->actingAs($doctor)
        ->get(\App\Filament\Pages\MyAppointments::getUrl())
        ->assertOk()
        ->assertSee(__('clinic.certificate.action'));
});
