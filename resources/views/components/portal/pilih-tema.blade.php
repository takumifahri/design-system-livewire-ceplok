{{--
    Toggle tema di kaki Sidebar Portal Dashboard — padanan toggle di docs React.
    Sorotan pilihan aktif bergeser di belakang tombol. Warnanya sama dengan
    item aktif Sidebar: putih di mode terang, gray-600 di mode gelap. Ikon
    aktif memakai warna brand, dan warna wadahnya memudar 200ms, seirama dengan
    Sidebar. Pergantiannya diatur resources/js/docs/portal-tema.js: sorotannya
    digeser dulu, baru halamannya dimuat ulang dengan tema baru.
--}}
@props([
    'darkMode' => false,
])

@php
    $darkMode = (bool) $darkMode;
    $pilihan = [
        ['gelap' => false, 'label' => 'Terang', 'ikon' => 'ceplok-o-sun', 'diam' => 'rotate-90 scale-90'],
        ['gelap' => true, 'label' => 'Gelap', 'ikon' => 'ceplok-o-moon', 'diam' => '-rotate-12 scale-90'],
    ];
    $ikon = 'size-5 transition-[color,rotate,scale] duration-300 ease-out motion-reduce:transition-none';
@endphp

<div
    x-data="portalTema(@js($darkMode))"
    role="group"
    aria-label="Pilih tema"
    {{ $attributes->class('relative grid grid-cols-2 rounded-full border border-border bg-surface-subtle p-1 transition-colors duration-200') }}
>
    <span
        aria-hidden="true"
        @class([
            'pointer-events-none absolute inset-y-1 left-1 w-[calc(50%-0.25rem)] rounded-full bg-white shadow-sm ring-1 ring-border transition-[translate,background-color,box-shadow] duration-300 ease-out motion-reduce:transition-none in-data-[theme=dark]:bg-gray-600',
            'translate-x-full' => $darkMode,
        ])
        x-bind:class="{ 'translate-x-full': gelap }"
    ></span>

    @foreach ($pilihan as $tema)
        @php
            $aktif = $darkMode === $tema['gelap'];
            $nilai = $tema['gelap'] ? 'true' : 'false';
        @endphp
        <button
            type="button"
            aria-pressed="{{ $aktif ? 'true' : 'false' }}"
            x-bind:aria-pressed="gelap === {{ $nilai }} ? 'true' : 'false'"
            x-on:click="pilih({{ $nilai }})"
            @class([
                'relative flex h-9 items-center justify-center gap-2 rounded-full text-sm font-medium transition-colors duration-300 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand',
                'text-content' => $aktif,
                'text-content-muted hover:text-content' => ! $aktif,
            ])
            x-bind:class="{ 'text-content': gelap === {{ $nilai }}, 'text-content-muted hover:text-content': gelap !== {{ $nilai }} }"
        >
            {{ svg($tema['ikon'], $ikon.' '.($aktif ? 'text-brand' : $tema['diam']), [
                'aria-hidden' => 'true',
                'x-bind:class' => "{ 'text-brand': gelap === {$nilai}, '{$tema['diam']}': gelap !== {$nilai} }",
            ]) }}
            {{ $tema['label'] }}
        </button>
    @endforeach
</div>
