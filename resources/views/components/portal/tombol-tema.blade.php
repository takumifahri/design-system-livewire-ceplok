{{--
    Tombol tema berikon Portal Dashboard, bergaya sama dengan lonceng di
    header. Ikonnya menunjukkan hasil bila ditekan: bulan saat terang, matahari
    saat gelap. Keduanya bertumpuk dan bertukar dengan memudar serta berputar
    sebelum halamannya dimuat ulang dengan tema baru (lihat
    resources/js/docs/portal-tema.js). Dipakai di header layar sempit dan di
    kaki Sidebar yang diringkas.
--}}
@props([
    'darkMode' => false,
])

@php
    $darkMode = (bool) $darkMode;
    $ikon = 'col-start-1 row-start-1 size-5 transition-[opacity,rotate,scale] duration-300 ease-out motion-reduce:transition-none';
@endphp

<button
    type="button"
    x-data="portalTema(@js($darkMode))"
    aria-label="Mode gelap"
    aria-pressed="{{ $darkMode ? 'true' : 'false' }}"
    x-bind:aria-pressed="gelap ? 'true' : 'false'"
    title="{{ $darkMode ? 'Matikan mode gelap' : 'Nyalakan mode gelap' }}"
    x-on:click="pilih(! gelap)"
    {{ $attributes->class('grid size-9 shrink-0 place-items-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand') }}
>
    {{ svg('ceplok-o-sun', $ikon.' '.($darkMode ? 'opacity-100' : '-rotate-90 scale-50 opacity-0'), [
        'aria-hidden' => 'true',
        'x-bind:class' => "{ 'opacity-100': gelap, '-rotate-90 scale-50 opacity-0': ! gelap }",
    ]) }}
    {{ svg('ceplok-o-moon', $ikon.' '.($darkMode ? 'rotate-90 scale-50 opacity-0' : 'opacity-100'), [
        'aria-hidden' => 'true',
        'x-bind:class' => "{ 'opacity-100': ! gelap, 'rotate-90 scale-50 opacity-0': gelap }",
    ]) }}
</button>
