{{-- Judul kartu Portal Dashboard: ikon kecil (slot `ikon`), judul, keterangan opsional, lalu isi slot di kanan. --}}
@props([
    'judul',
    'keterangan' => null,
    'ikon' => null,
])

<div class="flex flex-wrap items-start justify-between gap-3">
    <div class="flex min-w-0 items-start gap-2.5">
        {{ $ikon }}
        <div class="min-w-0">
            <h2 class="text-body font-bold text-content">{{ $judul }}</h2>
            @if (filled($keterangan))
                <p class="mt-0.5 text-body-sm text-content-subtle">{{ $keterangan }}</p>
            @endif
        </div>
    </div>
    {{ $slot }}
</div>
