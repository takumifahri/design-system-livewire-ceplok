{{--
    Tombol mode gelap, di ujung bawah rail dan di top bar mobile — port
    ThemeToggle di docs React.

    Namanya tetap "Mode gelap" dan keadaannya dibawa aria-pressed, jadi pembaca
    layar mendengar satu tombol yang menyala atau mati, bukan label yang
    berganti-ganti. Ikonnya menunjukkan hasil bila ditekan: bulan saat terang,
    matahari saat gelap. Keduanya ditumpuk di sel grid yang sama, sama seperti
    di docs React.

    Di halaman yang belum punya tampilan gelap tombolnya diredupkan. Dipakai
    aria-disabled, bukan disabled, supaya tetap bisa difokus dan tooltip
    alasannya tetap muncul saat disentuh. `hover` hanya dipasang selama
    tombolnya bisa dipakai, supaya versi redupnya tidak menyala saat disentuh.
--}}
@props([
    // Mode gelap yang sedang berlaku di halaman ini.
    'dark' => false,
    // Halaman ini sudah punya tampilan gelap.
    'available' => false,
    'hover' => '',
])

@php
    // Ikon bulan dan matahari, dari set ikon outline kit.
    $moon = 'M12 21a9 9 0 0 1-.5-17.986V3c-.354.966-.5 1.911-.5 3a9 9 0 0 0 9 9c.239 0 .254.018.488 0A9 9 0 0 1 12 21';
    $sun = 'M12 5V3m0 18v-2M7.05 7.05 5.636 5.636m12.728 12.728L16.95 16.95M5 12H3m18 0h-2M7.05 16.95l-1.414 1.414M18.364 5.636 16.95 7.05M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0';
    $title = match (true) {
        ! $available => 'Mode gelap belum tersedia di halaman ini',
        $dark => 'Matikan mode gelap',
        default => 'Nyalakan mode gelap',
    };
@endphp

<button
    type="button"
    @if ($available) x-on:click="$docsTheme(@js(! $dark))" @else aria-disabled="true" @endif
    aria-pressed="{{ $dark ? 'true' : 'false' }}"
    aria-label="Mode gelap"
    title="{{ $title }}"
    {{ $attributes->class([$available ? $hover : 'cursor-not-allowed opacity-40']) }}
>
    <span class="grid size-5 place-items-center *:col-start-1 *:row-start-1 *:size-5 *:transition-[opacity,scale,rotate] *:duration-300">
        <svg @class([$dark ? 'scale-50 -rotate-90 opacity-0' : 'scale-100 rotate-0 opacity-100']) fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $moon }}" />
        </svg>
        <svg @class([$dark ? 'scale-100 rotate-0 opacity-100' : 'scale-50 rotate-90 opacity-0']) fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sun }}" />
        </svg>
    </span>
</button>
