@php
    use CeplokUi\DesignKit\Enums\ClipboardPlatform;
    use CeplokUi\DesignKit\Enums\ClipboardVariant;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\FieldId;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

{{-- Nilai hanya-baca dengan aksi salin; prefix hanya untuk tampilan dan tidak ikut disalin. --}}
@props([
    'value' => null,
    'variant' => 'default',
    'platform' => 'default',
    'prefix' => null,
    'label' => null,
    'helperText' => null,
    'disabled' => false,
    'darkMode' => false,
])

@php
    $variant = Prop::oneOf(ClipboardVariant::class, $variant, ClipboardVariant::Default, 'clipboard', 'variant');
    $platform = Prop::oneOf(ClipboardPlatform::class, $platform, ClipboardPlatform::Default, 'clipboard', 'platform');
    $disabled = Prop::bool($disabled, false, 'clipboard', 'disabled');
    $darkMode = Prop::bool($darkMode, false, 'clipboard', 'dark-mode');
    $id = FieldId::for($attributes, 'clipboard');
    $helperId = $id.'-helper';
    $hasLabel = Content::filled($label);
    $hasHelper = Content::filled($helperText);
    $hasPrefix = $variant === 'segmented' && Content::filled($prefix);
    $segmented = $variant === 'segmented';
    $ariaLabel = $attributes->get('aria-label');
    $labelledBy = $attributes->get('aria-labelledby');
    if ($value === null) {
        Prop::violation('Prop `value` pada <x-ceplok::clipboard> wajib diisi.');
        $value = '';
    }
    if (! $hasLabel && ! Content::filled($ariaLabel) && ! Content::filled($labelledBy)) {
        Prop::violation('<x-ceplok::clipboard> memerlukan `label`, `aria-label`, atau `aria-labelledby` yang tidak kosong.');
    }
    if (! $segmented && Content::filled($prefix)) {
        Prop::violation('Prop `prefix` pada <x-ceplok::clipboard> hanya dapat dipakai dengan variant="segmented".');
    }
    $value = (string) $value;
    $sizes = [
        'default' => ['value' => 'text-sm leading-[1.25]', 'caption' => 'text-sm leading-[1.25]', 'label' => 'text-sm leading-[1.5] font-medium', 'prefix' => 'text-sm leading-[1.5]', 'action' => 'text-sm leading-[1.5]', 'tooltip' => 'text-sm leading-[1.5] font-medium', 'field' => 'h-10.5', 'actionSize' => 'h-10.5 px-4', 'padding' => 'px-3', 'feedback' => 'min-h-5', 'text' => 'text-sm'],
        'mobile' => ['value' => 'text-xs', 'caption' => 'text-xs', 'label' => 'text-xs font-medium', 'prefix' => 'text-xs', 'action' => 'text-xs', 'tooltip' => 'text-xs', 'field' => 'h-9.75', 'actionSize' => 'h-8.5 px-3', 'padding' => 'px-2.5', 'feedback' => 'min-h-4', 'text' => 'text-xs'],
    ];
    $size = $sizes[$platform];
    $action = 'inline-flex shrink-0 items-center justify-center bg-primary-700 font-medium text-white transition-colors enabled:hover:bg-primary-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:cursor-not-allowed disabled:opacity-50 aria-disabled:opacity-50';
    $root = $attributes->only(['class', 'wire:key', 'wire:copy-success', 'wire:copy-error', 'x-on:copy-success', 'x-on:copy-error']);
    $describedBy = collect([$hasHelper ? $helperId : null, $attributes->get('aria-describedby')])->filter()->implode(' ');
@endphp

<div
    x-data="ceplokClipboard"
    data-value="{{ $value }}"
    data-disabled="{{ $disabled ? 'true' : 'false' }}"
    x-on:keydown.escape.window="if (ceplokTooltip) ceplokTooltipDismissed = true"
    @if ($darkMode) data-theme="dark" @endif
    {{ $root->class('w-full') }}
>
    @if ($hasLabel)
        <label for="{{ $id }}" @class(['mb-2 block text-clipboard-label', $size['label']])>{{ $label }}</label>
    @endif

    <div
        @class([$segmented ? 'flex items-stretch rounded-lg border border-clipboard-border bg-clipboard-field' : 'flex items-center gap-2', 'cursor-not-allowed' => $disabled])
        x-on:pointerdown.capture="ceplokBlock($event)"
        x-on:keydown.capture="ceplokBlock($event)"
        x-on:copy.capture="ceplokBlock($event)"
        x-on:cut.capture="ceplokBlock($event)"
        x-on:contextmenu.capture="ceplokBlock($event)"
    >
        @if ($hasPrefix)
            <span @class(['flex shrink-0 items-center rounded-s-lg border-e border-clipboard-border bg-clipboard-prefix-surface px-3 text-clipboard-prefix', $size['prefix']])>{{ $prefix }}</span>
        @endif

        <input
            x-ref="input"
            id="{{ $id }}"
            type="text"
            readonly
            @disabled($disabled)
            value="{{ $value }}"
            @if (Content::filled($ariaLabel)) aria-label="{{ $ariaLabel }}" @endif
            @if (Content::filled($labelledBy)) aria-labelledby="{{ $labelledBy }}" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            @class([
                'min-w-0 flex-1 bg-clipboard-field focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:pointer-events-none disabled:cursor-not-allowed disabled:select-none',
                $segmented ? 'text-clipboard-segmented-value' : 'text-clipboard-value',
                $size['value'],
                'h-10 px-3' => $segmented,
                'rounded-s-lg' => $segmented && ! $hasPrefix,
                'rounded-lg border border-clipboard-border' => ! $segmented,
                $size['field'] => ! $segmented,
                $size['padding'] => ! $segmented,
            ])
        />

        @if ($segmented)
            <div
                class="relative -me-px flex h-10 shrink-0"
                x-on:mouseenter="ceplokHovered = true; if (!ceplokFocusVisible && ceplokMessage !== 'Copied to clipboard.') ceplokTooltipDismissed = false"
                x-on:mouseleave="ceplokHovered = false"
            >
                <button
                    type="button"
                    @disabled($disabled)
                    x-bind:aria-disabled="ceplokPending ? 'true' : null"
                    aria-label="Copy to clipboard"
                    x-on:click="ceplokCopy()"
                    x-on:pointerdown="ceplokFocusVisible = false"
                    x-on:focus="ceplokFocusVisible = $el.matches(':focus-visible'); if (ceplokFocusVisible && ceplokMessage !== 'Copied to clipboard.') ceplokTooltipDismissed = false"
                    x-on:blur="ceplokFocusVisible = false"
                    @class([$action, 'relative -top-px h-10.5 w-10.5 rounded-e-lg border-s border-clipboard-border'])
                >
                    {{ svg('ceplok-o-clipboard', 'relative size-5', ['aria-hidden' => 'true', 'x-show' => "ceplokMessage !== 'Copied to clipboard.'"]) }}
                    {{ svg('ceplok-o-check', 'relative size-5', ['aria-hidden' => 'true', 'x-show' => "ceplokMessage === 'Copied to clipboard.'", 'x-cloak']) }}
                </button>
                <span x-show="ceplokTooltip" x-cloak aria-hidden="true" class="absolute start-1/2 bottom-full z-50 -translate-x-1/2 pb-2">
                    <span @class(['relative block whitespace-nowrap rounded-md bg-clipboard-tooltip px-3 py-2 text-white shadow-sm', $size['tooltip']])>
                        Copy to clipboard
                        <span class="absolute start-1/2 -bottom-1 size-2 -translate-x-1/2 rotate-45 bg-clipboard-tooltip"></span>
                    </span>
                </span>
            </div>
        @else
            <button type="button" @disabled($disabled) x-bind:aria-disabled="ceplokPending ? 'true' : null" x-on:click="ceplokCopy()" @class([$action, 'rounded-lg', $size['actionSize'], $size['action']])>Copy</button>
        @endif
    </div>

    @if ($hasHelper)
        <div id="{{ $helperId }}" @class(['mt-2 font-medium text-clipboard-caption', $size['caption']])>{{ $helperText }}</div>
    @endif
    <span role="status" class="sr-only" x-text="ceplokMessage"></span>
    <p aria-hidden="true" x-text="ceplokMessage" x-bind:class="ceplokMessage === 'Could not copy. Select the text and copy it manually.' ? 'text-feedback-error' : 'text-clipboard-caption'" @class(['mt-2', $size['text'], $size['feedback']])></p>
</div>
