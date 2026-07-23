@props([
    'variant' => 'info',
    'surface' => 'soft',
    'heading' => null,
    'icon' => null,
    'dismissible' => true,
])

@php
    // Setiap variant punya satu warna "ink" yang dipakai konsisten untuk
    // ikon, heading, dan tombol tutup — hanya body text yang sedikit lebih
    // muda, mengikuti pola dua-tingkat kontras pada desain.
    $variants = [
        'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-300', 'ink' => 'text-green-800', 'body' => 'text-green-700', 'button' => 'bg-green-800 hover:bg-green-900 focus-visible:outline-green-800', 'icon' => 'check-circle'],
        'danger' => ['bg' => 'bg-red-50', 'border' => 'border-red-300', 'ink' => 'text-red-800', 'body' => 'text-red-700', 'button' => 'bg-red-800 hover:bg-red-900 focus-visible:outline-red-800', 'icon' => 'close-circle'],
        'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300', 'ink' => 'text-yellow-800', 'body' => 'text-yellow-700', 'button' => 'bg-yellow-800 hover:bg-yellow-900 focus-visible:outline-yellow-800', 'icon' => 'exclamation-circle'],
        'info' => ['bg' => 'bg-primary-50', 'border' => 'border-primary-300', 'ink' => 'text-primary-800', 'body' => 'text-primary-700', 'button' => 'bg-primary-800 hover:bg-primary-900 focus-visible:outline-primary-800', 'icon' => 'info-circle'],
        'purple' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-300', 'ink' => 'text-purple-800', 'body' => 'text-purple-700', 'button' => 'bg-purple-800 hover:bg-purple-900 focus-visible:outline-purple-800', 'icon' => 'info-circle'],
    ];

    $v = $variants[$variant] ?? $variants['info'];

    // icon={false} menyembunyikan ikon kiri sepenuhnya; null memakai default
    // per variant; string memakai nama ikon kustom.
    $showIcon = $icon !== false;
    $iconName = $showIcon ? ($icon ?? $v['icon']) : null;

    // soft: latar bertinta, tanpa garis. outline: latar putih + garis + shadow-soft.
    $surfaceClasses = $surface === 'outline'
        ? 'border '.$v['border'].' bg-surface shadow-soft'
        : $v['bg'];
@endphp

<div
    x-data="dkDisclosure(true)"
    x-show="open"
    x-transition.opacity.duration.200ms
    role="alert"
    {{ $attributes->merge(['class' => "rounded-lg p-4 sm:p-5 $surfaceClasses"]) }}
>
    <div class="flex items-start gap-3">
        @if ($showIcon)
            <x-dk::icon :name="$iconName" class="{{ $v['ink'] }} mt-0.5 size-5 shrink-0" />
        @endif

        <div class="min-w-0 flex-1">
            @if ($heading)
                <p class="text-sm font-bold {{ $v['ink'] }}">{{ $heading }}</p>
            @endif

            @if ($slot->isNotEmpty())
                <div class="{{ $heading ? 'mt-1' : '' }} text-sm leading-6 {{ $v['body'] }}">
                    {{ $slot }}
                </div>
            @endif

            @isset($actions)
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    {{ $actions }}
                </div>
            @endisset
        </div>

        @if ($dismissible)
            <button
                type="button"
                @click="close()"
                class="shrink-0 rounded-md p-1 {{ $v['ink'] }} opacity-70 transition-opacity hover:opacity-100 focus-visible:opacity-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                aria-label="Tutup peringatan"
            >
                <x-dk::icon name="close" class="size-3.5" />
            </button>
        @endif
    </div>
</div>
