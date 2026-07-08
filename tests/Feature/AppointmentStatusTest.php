<?php

use App\Enums\TreatmentStatus;
use App\Enums\VisitStatus;
use App\Models\Appointment;

it('allows valid visit status transitions', function (VisitStatus $from, VisitStatus $to) {
    $appointment = Appointment::factory()->create(['visit_status' => $from]);

    $appointment->transitionTo($to);

    expect($appointment->fresh()->visit_status)->toBe($to);
})->with([
    'waiting → arrived' => [VisitStatus::Waiting, VisitStatus::Arrived],
    'waiting → no_show' => [VisitStatus::Waiting, VisitStatus::NoShow],
    'arrived → in_progress' => [VisitStatus::Arrived, VisitStatus::InProgress],
    'in_progress → completed' => [VisitStatus::InProgress, VisitStatus::Completed],
]);

it('rejects invalid visit status transitions', function (VisitStatus $from, VisitStatus $to) {
    $appointment = Appointment::factory()->create(['visit_status' => $from]);

    expect(fn () => $appointment->transitionTo($to))
        ->toThrow(RuntimeException::class);

    expect($appointment->fresh()->visit_status)->toBe($from);
})->with([
    'waiting → in_progress' => [VisitStatus::Waiting, VisitStatus::InProgress],
    'waiting → completed' => [VisitStatus::Waiting, VisitStatus::Completed],
    'arrived → no_show' => [VisitStatus::Arrived, VisitStatus::NoShow],
    'arrived → completed' => [VisitStatus::Arrived, VisitStatus::Completed],
    'completed → arrived' => [VisitStatus::Completed, VisitStatus::Arrived],
    'no_show → arrived' => [VisitStatus::NoShow, VisitStatus::Arrived],
]);

it('marks an appointment as arrived from waiting', function () {
    $appointment = Appointment::factory()->create(['visit_status' => VisitStatus::Waiting]);

    $appointment->markArrived();

    expect($appointment->fresh()->visit_status)->toBe(VisitStatus::Arrived);
});

it('marks an appointment as no_show from waiting', function () {
    $appointment = Appointment::factory()->create(['visit_status' => VisitStatus::Waiting]);

    $appointment->markNoShow();

    expect($appointment->fresh()->visit_status)->toBe(VisitStatus::NoShow);
});

it('completes an in-progress appointment with treatment status and notes', function () {
    $appointment = Appointment::factory()->create(['visit_status' => VisitStatus::InProgress]);

    $appointment->complete(TreatmentStatus::Treated, notesKk: 'Ем алды', notesZh: '已治疗');

    $fresh = $appointment->fresh();
    expect($fresh->visit_status)->toBe(VisitStatus::Completed)
        ->and($fresh->treatment_status)->toBe(TreatmentStatus::Treated)
        ->and($fresh->notes_kk)->toBe('Ем алды')
        ->and($fresh->notes_zh)->toBe('已治疗');
});

it('refuses to complete an appointment that is not in progress', function () {
    $appointment = Appointment::factory()->create(['visit_status' => VisitStatus::Waiting]);

    expect(fn () => $appointment->complete(TreatmentStatus::Treated))
        ->toThrow(RuntimeException::class);
});

it('stores the treatment amount only when the patient was treated', function () {
    $treated = Appointment::factory()->create(['visit_status' => VisitStatus::InProgress]);
    $treated->complete(TreatmentStatus::Treated, amount: 15000);

    $notTreated = Appointment::factory()->create(['visit_status' => VisitStatus::InProgress]);
    $notTreated->complete(TreatmentStatus::NotTreated, amount: 15000);

    expect((float) $treated->fresh()->treatment_amount)->toBe(15000.0)
        ->and($notTreated->fresh()->treatment_amount)->toBeNull();
});
