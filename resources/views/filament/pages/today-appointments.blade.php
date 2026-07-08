<x-filament-panels::page>
    @php
        $stats = $this->getStats();
        $cards = [
            ['label' => __('clinic.today.total'), 'value' => $stats['total'], 'text' => 'text-gray-600 dark:text-gray-300'],
            ['label' => __('clinic.today.arrived'), 'value' => $stats['arrived'], 'text' => 'text-info-600 dark:text-info-400'],
            ['label' => __('clinic.today.waiting'), 'value' => $stats['waiting'], 'text' => 'text-warning-600 dark:text-warning-400'],
            ['label' => __('clinic.today.no_show'), 'value' => $stats['no_show'], 'text' => 'text-danger-600 dark:text-danger-400'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
        @foreach ($cards as $card)
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</span>
                <div class="mt-1 text-3xl font-semibold {{ $card['text'] }}">{{ $card['value'] }}</div>
            </div>
        @endforeach
    </div>

    {{ $this->table }}
</x-filament-panels::page>
