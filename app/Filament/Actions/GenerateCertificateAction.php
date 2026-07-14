<?php

namespace App\Filament\Actions;

use App\Models\Appointment;
use App\Models\MedicalCertificate;
use App\Support\CertificatePdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

class GenerateCertificateAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'certificate';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('clinic.certificate.action'))
            ->icon('heroicon-o-document-text')
            ->color('gray')
            ->modalHeading(__('clinic.certificate.heading'))
            ->modalWidth('2xl')
            ->modalSubmitActionLabel(__('clinic.certificate.submit'))
            ->fillForm(fn (Appointment $record): array => static::previousData($record))
            ->schema([
                DatePicker::make('period_from')
                    ->label(__('clinic.certificate.period_from'))
                    ->native(false)
                    ->displayFormat('d.m.Y'),
                DatePicker::make('period_to')
                    ->label(__('clinic.certificate.period_to'))
                    ->native(false)
                    ->displayFormat('d.m.Y'),
                Textarea::make('complaints')
                    ->label(__('clinic.certificate.complaints'))
                    ->rows(3),
                Textarea::make('examination')
                    ->label(__('clinic.certificate.examination'))
                    ->rows(3),
                Textarea::make('diagnosis')
                    ->label(__('clinic.certificate.diagnosis'))
                    ->rows(2),
                Textarea::make('treatment')
                    ->label(__('clinic.certificate.treatment'))
                    ->rows(3)
                    ->required(),
            ])
            ->action(function (Appointment $record, array $data): mixed {
                // Сохраняем справку, чтобы в следующий раз не заполнять заново.
                MedicalCertificate::updateOrCreate(
                    ['appointment_id' => $record->id],
                    [
                        'patient_id' => $record->patient_id,
                        'doctor_id' => $record->doctor_id,
                        'period_from' => $data['period_from'] ?? null,
                        'period_to' => $data['period_to'] ?? null,
                        'complaints' => $data['complaints'] ?? null,
                        'examination' => $data['examination'] ?? null,
                        'diagnosis' => $data['diagnosis'] ?? null,
                        'treatment' => $data['treatment'],
                    ],
                );

                return CertificatePdf::download($record, $data);
            });
    }

    /**
     * Данные для предзаполнения: справка этой записи, иначе последняя справка пациента.
     *
     * @return array<string, mixed>
     */
    protected static function previousData(Appointment $appointment): array
    {
        $certificate = $appointment->certificate
            ?? MedicalCertificate::query()
                ->where('patient_id', $appointment->patient_id)
                ->latest('id')
                ->first();

        if ($certificate === null) {
            return [
                'period_from' => now(),
                'period_to' => now(),
            ];
        }

        return [
            'period_from' => $certificate->period_from,
            'period_to' => $certificate->period_to,
            'complaints' => $certificate->complaints,
            'examination' => $certificate->examination,
            'diagnosis' => $certificate->diagnosis,
            'treatment' => $certificate->treatment,
        ];
    }
}
