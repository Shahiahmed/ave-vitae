@php
    $locales = [
        'kk' => ['label' => 'Қазақша', 'short' => 'ҚАЗ'],
        'zh_CN' => ['label' => '中文', 'short' => '中文'],
        'ru' => ['label' => 'Русский', 'short' => 'РУ'],
    ];
    $current = app()->getLocale();
    $currentShort = $locales[$current]['short'] ?? 'ҚАЗ';

    $flag = function (string $locale): string {
        return match ($locale) {
            'ru' => '<svg viewBox="0 0 20 14" width="20" height="14" class="shrink-0 rounded-[2px] ring-1 ring-black/10">'
                .'<rect width="20" height="14" fill="#fff"/>'
                .'<rect y="4.667" width="20" height="4.667" fill="#0039A6"/>'
                .'<rect y="9.333" width="20" height="4.667" fill="#D52B1E"/></svg>',
            'kk' => '<svg viewBox="0 0 20 14" width="20" height="14" class="shrink-0 rounded-[2px] ring-1 ring-black/10">'
                .'<rect width="20" height="14" fill="#00AFCA"/>'
                .'<circle cx="10" cy="6.4" r="2.5" fill="#FEC50C"/>'
                .'<rect x="4" y="11" width="12" height="0.9" fill="#FEC50C"/></svg>',
            'zh_CN' => '<svg viewBox="0 0 20 14" width="20" height="14" class="shrink-0 rounded-[2px] ring-1 ring-black/10">'
                .'<rect width="20" height="14" fill="#DE2910"/>'
                .'<polygon fill="#FFDE00" points="6,2.5 6.764,4.448 8.853,4.573 7.236,5.902 7.763,7.927 6,6.8 4.237,7.927 4.763,5.902 3.147,4.573 5.236,4.448"/></svg>',
            default => '',
        };
    };
@endphp

<x-filament::dropdown placement="bottom-end" teleport width="xs">
    <x-slot name="trigger">
        <button
            type="button"
            class="fi-topbar-item-btn flex items-center gap-x-1.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-gray-700 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:text-gray-200 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
        >
            {!! $flag($current) !!}
            <span>{{ $currentShort }}</span>
            <x-filament::icon icon="heroicon-m-chevron-down" class="h-4 w-4 text-gray-400 dark:text-gray-500" />
        </button>
    </x-slot>

    <x-filament::dropdown.list>
        @foreach ($locales as $locale => $meta)
            <x-filament::dropdown.list.item
                tag="a"
                :href="route('locale.switch', $locale)"
                :color="$current === $locale ? 'primary' : 'gray'"
            >
                <span class="flex w-full items-center gap-x-2.5">
                    {!! $flag($locale) !!}
                    <span>{{ $meta['label'] }}</span>
                    @if ($current === $locale)
                        <x-filament::icon icon="heroicon-m-check" class="ms-auto h-4 w-4 text-primary-600 dark:text-primary-400" />
                    @endif
                </span>
            </x-filament::dropdown.list.item>
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
