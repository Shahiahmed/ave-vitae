@php
    $locales = ['ru' => 'РУ', 'kk' => 'ҚАЗ', 'zh_CN' => '中文'];
    $current = app()->getLocale();
@endphp

<div class="flex items-center gap-1 pe-2">
    @foreach ($locales as $locale => $label)
        <a
            href="{{ route('locale.switch', $locale) }}"
            @class([
                'rounded-md px-2 py-1 text-sm font-medium transition',
                'bg-primary-600 text-white' => $current === $locale,
                'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5' => $current !== $locale,
            ])
        >
            {{ $label }}
        </a>
    @endforeach
</div>
