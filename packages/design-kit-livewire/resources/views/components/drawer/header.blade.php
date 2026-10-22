@php
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

@props([
    'eyebrow' => null,
    'closeLabel' => 'Tutup drawer',
    'showCloseButton' => true,
    'closeButton' => null,
    'darkMode' => false,
])

@php
    $showCloseButton = Prop::bool($showCloseButton, true, 'drawer.header', 'show-close-button');
    $darkMode = Prop::bool($darkMode, false, 'drawer.header', 'dark-mode');
@endphp

<div
    {{ $attributes->class([
        'flex shrink-0 items-center justify-between gap-4 px-5 pt-4 pb-2',
    ]) }}
>
    <div class="min-w-0 flex-1 space-y-1">
        @if (filled($eyebrow))
            <span class="block text-base font-semibold tracking-wider uppercase text-gray-500">
                {{ $eyebrow }}
            </span>
        @endif
        @if (Content::filled($slot))
            {{ $slot }}
        @endif
    </div>

    @if (Content::filled($closeButton))
        {{ $closeButton }}
    @elseif ($showCloseButton)
        <button
            type="button"
            x-on:click="typeof close === 'function' ? close() : $dispatch('close')"
            aria-label="{{ $closeLabel }}"
            @class([
                '-mr-2 ml-auto inline-flex size-8 shrink-0 items-center justify-center rounded-lg transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600',
                $darkMode ? 'text-gray-500 hover:bg-gray-700 hover:text-white' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700',
            ])
        >
            <x-ceplok-o-close class="w-[18px] h-[18px]" aria-hidden="true" />
        </button>
    @endif
</div>
