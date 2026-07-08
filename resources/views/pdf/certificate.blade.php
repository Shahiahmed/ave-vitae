@php
    /** @var string $fullName */
    /** @var ?string $iin */
    /** @var ?int $age */
    /** @var string $period */
    /** @var ?string $department */
    /** @var ?string $complaints */
    /** @var ?string $examination */
    /** @var ?string $diagnosis */
    /** @var string $treatment */
    /** @var string $doctorName */
    /** @var string $date */
    /** @var ?string $logo */
@endphp
<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; font-size: 11pt; color: #000; line-height: 1.4; }
        .header-table { width: 100%; border-bottom: 2px solid #1f4b7a; padding-bottom: 6px; margin-bottom: 14px; }
        .header-table td { vertical-align: middle; }
        .logo { width: 60px; }
        .clinic-kk { font-size: 13pt; font-weight: bold; }
        .clinic-ru { font-size: 11pt; color: #333; }
        .title { text-align: center; font-size: 14pt; font-weight: bold; margin: 6px 0 16px; }
        .field { margin: 2px 0; }
        .field b { }
        .section-title { font-weight: bold; margin-top: 12px; }
        .section-body { text-align: justify; margin-top: 2px; white-space: pre-line; }
        .footer { margin-top: 40px; }
        .footer .row { margin: 3px 0; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo">
                @if ($logo)
                    <img src="{{ $logo }}" class="logo">
                @endif
            </td>
            <td>
                <div class="clinic-kk">«ЧАҢ ЖИЯҢ» медицина орталығы</div>
                <div class="clinic-ru">Медицинский центр «Чан Жиян»</div>
            </td>
        </tr>
    </table>

    <div class="title">МЕДИЦИНАЛЫҚ АНЫҚТАМА</div>

    <div class="field"><b>А.Ж.Т.:</b> {{ $fullName }}</div>
    @if ($iin)
        <div class="field"><b>ЖСН:</b> {{ $iin }}</div>
    @endif
    @if ($age !== null)
        <div class="field"><b>Жасы:</b> {{ $age }}</div>
    @endif
    <div class="field"><b>Қаралу уақыты:</b> {{ $period }}</div>
    @if ($department)
        <div class="field"><b>Бөлімі:</b> {{ $department }}</div>
    @endif

    @if ($complaints)
        <div class="section-title">Шағымдары:</div>
        <div class="section-body">{{ $complaints }}</div>
    @endif

    @if ($examination)
        <div class="section-title">Қарап-тексеру нәтижелері:</div>
        <div class="section-body">{{ $examination }}</div>
    @endif

    @if ($diagnosis)
        <div class="section-title">Диагноз:</div>
        <div class="section-body">{{ $diagnosis }}</div>
    @endif

    <div class="section-title">Емдеу және ұсыныстар:</div>
    <div class="section-body">{{ $treatment }}</div>

    <div class="footer">
        <div class="row"><b>Дәрігер:</b> {{ $doctorName }}</div>
        <div class="row"><b>Күні:</b> {{ $date }}</div>
    </div>
</body>
</html>
