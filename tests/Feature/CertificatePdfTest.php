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

it('saves the certificate so the doctor does not refill it from scratch', function () {
    $doctor = userWithRole(Role::Doctor);
    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'scheduled_at' => now(),
    ]);
    $this->actingAs($doctor);

    Livewire\Livewire::test(\App\Filament\Pages\MyAppointments::class)
        ->callTableAction('certificate', $appointment, [
            'period_from' => '2026-06-27',
            'period_to' => '2026-07-04',
            'complaints' => 'Бас айналу',
            'treatment' => 'Массаж, 14 күн демалыс',
        ])
        ->assertFileDownloaded();

    $certificate = \App\Models\MedicalCertificate::where('appointment_id', $appointment->id)->first();

    expect($certificate)->not->toBeNull()
        ->and($certificate->complaints)->toBe('Бас айналу')
        ->and($certificate->treatment)->toBe('Массаж, 14 күн демалыс')
        ->and($certificate->patient_id)->toBe($appointment->patient_id);

    // Повторное формирование обновляет ту же справку, а не плодит дубликаты.
    Livewire\Livewire::test(\App\Filament\Pages\MyAppointments::class)
        ->callTableAction('certificate', $appointment, [
            'period_from' => '2026-06-27',
            'period_to' => '2026-07-04',
            'treatment' => 'Жаңартылған ем',
        ])
        ->assertFileDownloaded();

    expect(\App\Models\MedicalCertificate::where('appointment_id', $appointment->id)->count())->toBe(1)
        ->and($certificate->fresh()->treatment)->toBe('Жаңартылған ем');
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
