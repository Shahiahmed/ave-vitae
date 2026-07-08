<?php

namespace App\Support;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AppointmentsExport
{
    /**
     * Заголовки столбцов Excel.
     *
     * @return array<int, string>
     */
    public static function headings(): array
    {
        return [
            __('clinic.appointment.scheduled_at'),
            __('clinic.appointment.patient'),
            __('clinic.patient.phone'),
            __('clinic.appointment.department'),
            __('clinic.appointment.doctor'),
            __('clinic.appointment.visit_status'),
            __('clinic.appointment.treatment_status'),
            __('clinic.appointment.treatment_amount'),
        ];
    }

    /**
     * Одна строка Excel из записи.
     *
     * @return array<int, string>
     */
    public static function rowFor(Appointment $appointment): array
    {
        return [
            $appointment->scheduled_at?->format('d.m.Y H:i') ?? '',
            (string) $appointment->patient?->name_kk,
            (string) $appointment->patient?->phone,
            (string) $appointment->department?->name_ru,
            $appointment->doctor?->name ?? '—',
            $appointment->visit_status?->getLabel() ?? '',
            $appointment->treatment_status?->getLabel() ?? '—',
            $appointment->treatment_amount !== null ? (string) $appointment->treatment_amount : '—',
        ];
    }

    /**
     * Сформировать xlsx по запросу (с учётом фильтров) и отдать на скачивание.
     */
    public static function download(Builder $query, string $filename): BinaryFileResponse
    {
        $path = tempnam(sys_get_temp_dir(), 'appt_').'.xlsx';

        $writer = new Writer;
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues(static::headings()));

        $query->with(['patient', 'department', 'doctor'])
            ->lazy()
            ->each(fn (Appointment $appointment) => $writer->addRow(
                Row::fromValues(static::rowFor($appointment)),
            ));

        $writer->close();

        return response()->download($path, $filename)->deleteFileAfterSend();
    }
}
