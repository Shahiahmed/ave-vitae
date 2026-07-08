<x-filament-panels::page>
    @php
        $stats = $this->getStats();
        $cards = [
            ['label' => __('clinic.today.total'), 'value' => $stats['total'], 'color' => null],
            ['label' => __('clinic.today.arrived'), 'value' => $stats['arrived'], 'color' => 'var(--info-600)'],
            ['label' => __('clinic.today.waiting'), 'value' => $stats['waiting'], 'color' => 'var(--warning-600)'],
            ['label' => __('clinic.today.no_show'), 'value' => $stats['no_show'], 'color' => 'var(--danger-600)'],
        ];
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
        @foreach ($cards as $card)
            <div class="fi-wi-stats-overview-stat">
                <div class="fi-wi-stats-overview-stat-content">
                    <span class="fi-wi-stats-overview-stat-label">{{ $card['label'] }}</span>
                    <div class="fi-wi-stats-overview-stat-value" @if ($card['color']) style="color: {{ $card['color'] }};" @endif>
                        {{ $card['value'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{ $this->table }}
</x-filament-panels::page>
