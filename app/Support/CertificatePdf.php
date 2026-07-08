<?php

namespace App\Support;

use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CertificatePdf
{
    /**
     * Сформировать медицинскую справку (PDF) по записи + данным формы и отдать на скачивание.
     *
     * @param  array<string, mixed>  $data
     */
    public static function download(Appointment $appointment, array $data): BinaryFileResponse
    {
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        $fullName = trim(
            ($patient?->name_kk ?? '')
            .($patient?->name_zh ? ' ('.$patient->name_zh.')' : '')
        );

        $from = filled($data['period_from'] ?? null) ? Carbon::parse($data['period_from'])->format('d.m.Y') : null;
        $to = filled($data['period_to'] ?? null) ? Carbon::parse($data['period_to'])->format('d.m.Y') : null;
        $period = ($from && $to) ? "{$from} — {$to}" : ((string) ($from ?? $to ?? ''));

        $doctorName = $doctor
            ? trim(($doctor->name_kk ?: $doctor->name).($doctor->name_zh ? ' ('.$doctor->name_zh.')' : ''))
            : '';

        $html = view('pdf.certificate', [
            'fullName' => $fullName,
            'iin' => $patient?->iin,
            'age' => $patient?->birth_date?->age,
            'period' => $period,
            'department' => $appointment->department?->name_ru,
            'complaints' => $data['complaints'] ?? null,
            'examination' => $data['examination'] ?? null,
            'diagnosis' => $data['diagnosis'] ?? null,
            'treatment' => $data['treatment'] ?? '',
            'doctorName' => $doctorName,
            'date' => now()->format('d.m.Y'),
            'logo' => is_file(public_path('logo.svg')) ? public_path('logo.svg') : null,
        ])->render();

        $tempDir = storage_path('app/mpdf');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useAdobeCJK' => true,
            'tempDir' => $tempDir,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 18,
            'margin_right' => 18,
        ]);
        $mpdf->WriteHTML($html);

        $path = tempnam(sys_get_temp_dir(), 'cert_').'.pdf';
        $mpdf->Output($path, Destination::FILE);

        $filename = 'anyqtama-'.($patient?->id ?? 'x').'-'.now()->format('Ymd').'.pdf';

        return response()->download($path, $filename)->deleteFileAfterSend();
    }
}
