{{--
    Popover contoh untuk halaman Popover: trigger tombol ikon informasi, port
    infoTrigger di PopoverPage docs React. Ikon hanya contoh; trigger boleh
    tombol lain yang sesuai konteks.
--}}
@props([
    'label',
    'title' => 'Popover',
    'side' => 'right',
    'dark' => false,
    'triggerClass' => '',
])

@php
    $baseTriggerClass = $dark
        ? 'inline-flex size-9 items-center justify-center rounded-md transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-400 text-gray-400 hover:bg-gray-700 hover:text-gray-50'
        : 'inline-flex size-9 items-center justify-center rounded-md transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-400 text-gray-500 hover:bg-gray-100 hover:text-gray-700';
    $triggerClasses = trim($baseTriggerClass.' '.$triggerClass);
@endphp

<x-ceplok::popover :title="$title" :side="$side" {{ $attributes }}>
    <x-slot:trigger raw :aria-label="$label" :class="$triggerClasses"><x-ceplok::icon>{{ svg('ceplok-o-info-circle') }}</x-ceplok::icon></x-slot:trigger>
    @if ($slot->isEmpty())
        Popover Body Text, Popover Body Text, Popover Body Text
    @else
        {{ $slot }}
    @endif
</x-ceplok::popover>
