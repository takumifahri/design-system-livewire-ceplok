{{--
    Isi mockup ponsel di halaman Table of Contents: navbar, isi halaman, dan
    dua panel simulasi yang muncul di dalam bingkai ponsel.
    - Ikon kiri navbar membuka menu sidebar dari kiri.
    - Ikon kanan membuka menu navbar dari kanan: tautan navbar, lalu daftar
      isi halaman yang bisa dilipat. Daftar isinya slot `toc`, biasanya
      Table of Contents `layout="list"` dengan `:title="false"`; judulnya
      sudah menjadi tombol lipat. Klik entri menutup panelnya.

    Slot bawaan adalah isi halaman; beri `flex-1` dan `overflow-y-auto` bila
    isinya digulir. Panel menempel di pembungkus `relative` terdekat, yaitu
    bingkai ponselnya, jadi komponen ini sendiri tidak `relative`.

    Tautan sidebar dan navbar hanya tiruan: kliknya menutup panel.
--}}
@props([
    'toc' => null,
])

@php
    $sidebar = [
        'Foundations' => ['Colors', 'Typography', 'Icons'],
        'Components' => ['Alert', 'Badge', 'Button', 'Table of Contents', 'Timeline'],
    ];
    $navbar = ['Beranda', 'Foundations', 'Components', 'Form'];
    $panelClass = 'absolute inset-y-0 flex w-64 max-w-[85%] flex-col bg-surface text-content shadow-2xl outline-none transition-transform duration-300 ease-out in-data-[theme=dark]:bg-gray-800';
    $closeClass = '-mr-2 ml-auto inline-flex size-8 shrink-0 items-center justify-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle hover:text-content focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand';
    $iconButtonClass = 'inline-flex shrink-0 items-center justify-center rounded-lg p-1.5 text-content-muted transition-colors hover:bg-surface-subtle hover:text-content focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand';
    $itemClass = 'flex w-full items-center rounded-lg px-3 py-2.5 text-left text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand';
@endphp

<div
    x-data="{
        panel: null,
        daftarIsi: true,
        buka(nama) {
            this.panel = nama;
            // Dibuka lagi setelah memilih entri daftar isi: mulai dari atas, dari tautan navbar.
            this.$refs['isi-' + nama].scrollTop = 0;
            // Panelnya baru bisa difokus setelah inert dan invisible-nya dicabut.
            this.$nextTick(() => requestAnimationFrame(() => this.$refs['panel-' + nama].focus()));
        },
        tutup() {
            const nama = this.panel;
            this.panel = null;
            if (nama) this.$refs['pembuka-' + nama].focus();
        },
    }"
    {{ $attributes->class('flex h-full flex-col') }}
>
    {{-- Navbar di luar area gulir, bukan sticky: elemen sticky menjadi containing
         block, sehingga panel akan terkurung di navbar, bukan memenuhi bingkai ponsel. --}}
    <div class="flex shrink-0 items-center justify-between gap-3 border-b border-border bg-toc-surface px-3 py-2.5 transition-colors duration-300">
        <button
            type="button"
            x-ref="pembuka-sidebar"
            x-on:click="buka('sidebar')"
            aria-label="Buka sidebar"
            aria-haspopup="dialog"
            aria-expanded="false"
            x-bind:aria-expanded="panel === 'sidebar' ? 'true' : 'false'"
            class="{{ $iconButtonClass }}"
        ><x-ceplok-o-bars class="size-5" aria-hidden="true" /></button>
        <button
            type="button"
            x-ref="pembuka-menu"
            x-on:click="buka('menu')"
            aria-label="Buka menu dan daftar isi"
            aria-haspopup="dialog"
            aria-expanded="false"
            x-bind:aria-expanded="panel === 'menu' ? 'true' : 'false'"
            class="{{ $iconButtonClass }}"
        ><x-ceplok-o-bars-from-left class="size-5" aria-hidden="true" /></button>
    </div>

    {{ $slot }}

    <div
        class="invisible absolute inset-0 z-20 overflow-hidden rounded-[inherit] transition-[visibility] duration-300"
        x-bind:class="{ 'visible': panel, 'invisible': ! panel }"
        inert
        x-bind:inert="! panel"
        x-on:keydown.escape.stop="tutup()"
    >
        <div
            aria-hidden="true"
            x-on:click="tutup()"
            class="absolute inset-0 bg-gray-900/50 opacity-0 transition-opacity duration-300"
            x-bind:class="{ 'opacity-100': panel, 'opacity-0': ! panel }"
        ></div>

        {{-- Menu sidebar dari kiri. --}}
        <div
            role="dialog"
            aria-label="Sidebar"
            tabindex="-1"
            x-ref="panel-sidebar"
            x-bind:inert="panel !== 'sidebar'"
            class="left-0 -translate-x-full {{ $panelClass }}"
            x-bind:class="{ 'translate-x-0': panel === 'sidebar', '-translate-x-full': panel !== 'sidebar' }"
        >
            <div class="flex shrink-0 items-center justify-between gap-4 px-5 pt-4 pb-2">
                <p class="text-base font-black text-content">Ceplok UI</p>
                <button type="button" x-on:click="tutup()" aria-label="Tutup sidebar" class="{{ $closeClass }}"><x-ceplok-o-close class="size-4.5" aria-hidden="true" /></button>
            </div>
            <div x-ref="isi-sidebar" class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-y-contain px-3 pt-3 pb-6">
                @foreach ($sidebar as $group => $links)
                    <div>
                        <p class="px-3 text-[11px] leading-4 font-bold tracking-[0.14em] text-content-disabled uppercase">{{ $group }}</p>
                        <ul class="mt-2 space-y-0.5">
                            @foreach ($links as $link)
                                <li>
                                    <button
                                        type="button"
                                        x-on:click="tutup()"
                                        @if ($link === 'Table of Contents') aria-current="page" @endif
                                        @class([
                                            $itemClass,
                                            'bg-surface-subtle text-brand' => $link === 'Table of Contents',
                                            'text-content hover:bg-surface-subtle' => $link !== 'Table of Contents',
                                        ])
                                    >{{ $link }}</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Menu navbar dari kanan: tautan navbar, lalu daftar isi yang bisa dilipat. --}}
        <div
            role="dialog"
            aria-label="Menu"
            tabindex="-1"
            x-ref="panel-menu"
            x-bind:inert="panel !== 'menu'"
            class="right-0 translate-x-full {{ $panelClass }}"
            x-bind:class="{ 'translate-x-0': panel === 'menu', 'translate-x-full': panel !== 'menu' }"
        >
            <div class="flex shrink-0 items-center justify-between gap-4 px-5 pt-4 pb-2">
                <p class="text-xs font-bold tracking-[0.14em] text-content-disabled uppercase">Menu</p>
                <button type="button" x-on:click="tutup()" aria-label="Tutup menu" class="{{ $closeClass }}"><x-ceplok-o-close class="size-4.5" aria-hidden="true" /></button>
            </div>
            <div x-ref="isi-menu" class="min-h-0 flex-1 overflow-y-auto overscroll-y-contain px-3 pt-2 pb-6">
                <ul class="space-y-0.5">
                    @foreach ($navbar as $link)
                        <li>
                            <button
                                type="button"
                                x-on:click="tutup()"
                                @if ($link === 'Components') aria-current="page" @endif
                                @class([
                                    $itemClass,
                                    'text-brand' => $link === 'Components',
                                    'text-content hover:bg-surface-subtle' => $link !== 'Components',
                                ])
                            >{{ $link }}</button>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-3 border-t border-border pt-3">
                    <button
                        type="button"
                        x-on:click="daftarIsi = ! daftarIsi"
                        aria-expanded="true"
                        x-bind:aria-expanded="daftarIsi ? 'true' : 'false'"
                        class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2 text-left text-[11px] leading-4 font-bold tracking-[0.14em] text-content-disabled uppercase transition-colors hover:text-content focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand"
                    >
                        On this page
                        <x-ceplok-o-chevron-down class="size-4 shrink-0 transition-transform duration-200" x-bind:class="daftarIsi ? 'rotate-180' : ''" aria-hidden="true" />
                    </button>
                    {{-- Entri memakai ukuran mobile (14px, baris 40px) seperti daftar di Drawer kit,
                         walau daftarnya layout list, dan latarnya mengikuti panel. --}}
                    <div
                        x-show="daftarIsi"
                        class="mt-2 px-3 [&_[data-id]]:py-2.5! [&_[data-id]]:text-sm! [&>nav]:bg-transparent!"
                        x-on:item-click="tutup()"
                    >{{ $toc }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
