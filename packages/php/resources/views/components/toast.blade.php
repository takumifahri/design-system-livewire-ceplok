@props([
    'variant' => 'info',
    'heading' => null,
    'icon' => null,
    'dismissible' => true,
])

@php
    // Beda dari Alert: kartu Toast selalu netral (putih + shadow), hanya
    // badge ikon yang membawa warna variant. Menjaga notifikasi tetap ringan
    // saat beberapa toast menumpuk di layar sekaligus.
    $variants = [
        'success' => ['badge' => 'bg-green-100 text-green-600', 'icon' => 'check'],
        'danger' => ['badge' => 'bg-red-100 text-red-600', 'icon' => 'close'],
        'warning' => ['badge' => 'bg-yellow-100 text-yellow-600', 'icon' => 'exclamation-circle'],
        'info' => ['badge' => 'bg-primary-100 text-primary-600', 'icon' => 'info-circle'],
        'purple' => ['badge' => 'bg-purple-100 text-purple-600', 'icon' => 'info-circle'],
    ];

    $v = $variants[$variant] ?? $variants['info'];

    // icon={false} menyembunyikan badge ikon; null memakai default per variant.
    $showIcon = $icon !== false;
    $iconName = $showIcon ? ($icon ?? $v['icon']) : null;

    // Baris rata tengah untuk pesan satu baris; rata atas begitu ada heading
    // atau tombol aksi supaya badge ikon tetap sejajar dengan baris pertama.
    $multiline = $heading || isset($actions);
@endphp

<div
    x-data="dkDisclosure(true)"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-2 opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    role="status"
    aria-live="polite"
    {{ $attributes->merge(['class' => 'w-full max-w-sm rounded-xl bg-surface p-4 shadow-xl']) }}
>
    <div class="flex {{ $multiline ? 'items-start' : 'items-center' }} gap-3">
        @if ($showIcon)
            <span class="{{ $v['badge'] }} grid size-9 shrink-0 place-items-center rounded-lg">
                <x-dk::icon :name="$iconName" class="size-4" />
            </span>
        @endif

        <div class="min-w-0 flex-1">
            @if ($heading)
                <p class="text-sm font-bold text-gray-900">{{ $heading }}</p>
            @endif

            @if ($slot->isNotEmpty())
                <div class="{{ $heading ? 'mt-1' : '' }} text-sm leading-6 text-gray-600">
                    {{ $slot }}
                </div>
            @endif

            @isset($actions)
                <div class="mt-3">
                    {{ $actions }}
                </div>
            @endisset
        </div>

        @if ($dismissible)
            <button
                type="button"
                @click="close()"
                class="shrink-0 rounded-md p-1 text-gray-400 transition-colors hover:text-gray-600 focus-visible:text-gray-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600"
                aria-label="Tutup notifikasi"
            >
                <x-dk::icon name="close" class="size-3.5" />
            </button>
        @endif
    </div>
</div>
