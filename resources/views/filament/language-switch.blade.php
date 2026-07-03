@php
    $locales = [
        'ru' => ['label' => 'Русский', 'short' => 'РУ'],
        'kk' => ['label' => 'Қазақша', 'short' => 'ҚАЗ'],
        'zh_CN' => ['label' => '中文', 'short' => '中文'],
    ];
    $current = app()->getLocale();
    $currentShort = $locales[$current]['short'] ?? 'РУ';
@endphp

<x-filament::dropdown placement="bottom-end" teleport width="xs">
    <x-slot name="trigger">
        <button
            type="button"
            class="fi-topbar-item-btn flex items-center gap-x-1.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-gray-700 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:text-gray-200 dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
        >
            <x-filament::icon icon="heroicon-m-language" class="h-5 w-5 text-gray-400 dark:text-gray-500" />
            <span>{{ $currentShort }}</span>
            <x-filament::icon icon="heroicon-m-chevron-down" class="h-4 w-4 text-gray-400 dark:text-gray-500" />
        </button>
    </x-slot>

    <x-filament::dropdown.list>
        @foreach ($locales as $locale => $meta)
            <x-filament::dropdown.list.item
                tag="a"
                :href="route('locale.switch', $locale)"
                :icon="$current === $locale ? 'heroicon-m-check' : 'heroicon-o-globe-alt'"
                :color="$current === $locale ? 'primary' : 'gray'"
            >
                {{ $meta['label'] }}
            </x-filament::dropdown.list.item>
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
