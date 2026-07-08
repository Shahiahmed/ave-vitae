<?php

namespace App\Filament\Actions;

use App\Models\Appointment;
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
            ->schema([
                DatePicker::make('period_from')
                    ->label(__('clinic.certificate.period_from'))
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->default(now()),
                DatePicker::make('period_to')
                    ->label(__('clinic.certificate.period_to'))
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->default(now()),
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
            ->action(fn (Appointment $record, array $data): mixed => CertificatePdf::download($record, $data));
    }
}
