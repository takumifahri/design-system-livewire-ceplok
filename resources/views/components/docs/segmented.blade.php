{{--
    Kelompok tombol pilihan (segmented control) untuk Playground — port
    Segmented di docs React, dipakai di dalam komponen Livewire.

    `model` adalah nama properti Livewire yang diubah lewat $set; `value` nilai
    sekarang (boleh enum). Dengan `alpine`, `model` justru nama variabel Alpine
    di sekitarnya dan pilihannya berganti di browser tanpa request — dipakai
    untuk kontrol yang murni tampilan, mis. pemilih font di Typography.

    Tiap `options` berisi `value`, `label`, dan opsional `icon` (path SVG) serta
    `disabled`, yang mematikan dan meredupkan pilihan itu saja. `disabled` pada
    komponen meredupkan sekaligus mematikan seluruh pilihan; nilai terpilih
    tetap terlihat.
--}}
@props([
    'label',
    'model',
    'value' => null,
    'options' => [],
    'itemClass' => 'px-3',
    'wrap' => false,
    'disabled' => false,
    'alpine' => false,
])

@php
    $current = $value instanceof BackedEnum ? $value->value : $value;
@endphp

<div
    {{ $attributes->class([
        'inline-flex rounded-lg border border-border bg-surface p-1 dark:border-gray-700 dark:bg-gray-800',
        'flex-wrap' => $wrap,
        'opacity-50' => $disabled,
    ]) }}
    role="group"
    aria-label="{{ $label }}"
>
    @foreach ($options as $option)
        @php
            $selected = $current === $option['value'];
            $optionDisabled = $option['disabled'] ?? false;
        @endphp
        <button
            type="button"
            @if ($alpine)
                x-on:click="{{ $model }} = @js($option['value'])"
                x-bind:aria-pressed="{{ $model }} === @js($option['value'])"
                x-bind:class="{
                    'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-300': {{ $model }} === @js($option['value']),
                    'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-100': {{ $model }} !== @js($option['value']),
                }"
            @else
                wire:click="$set(@js($model), @js($option['value']))"
            @endif
            @disabled($disabled || $optionDisabled)
            @class([
                'inline-flex items-center gap-2 rounded-md py-1.5 text-sm font-bold transition-colors disabled:cursor-not-allowed',
                $itemClass,
                $selected ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-300' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-100',
                'opacity-40' => ! $disabled && $optionDisabled,
            ])
            aria-pressed="{{ $selected ? 'true' : 'false' }}"
        >
            @isset($option['icon'])
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $option['icon'] }}" />
                </svg>
            @endisset
            {{ $option['label'] }}
        </button>
    @endforeach
</div>
