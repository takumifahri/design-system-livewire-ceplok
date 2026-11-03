<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Table of Contents: tampilan, platform, judul, dan
 * anchor. Isi bingkainya bagian-bagian yang bisa digulir; daftar isinya
 * membaca wadah gulir itu lewat scroll-container.
 *
 * Berbeda dari docs React, platform Mobile mensimulasikan navbar ponsel
 * (x-docs.toc-phone): ikon kiri membuka menu sidebar, ikon kanan membuka menu
 * navbar dengan daftar isi yang bisa dilipat di bawahnya. Daftar isinya
 * layout `list` tanpa judul, karena judulnya sudah menjadi tombol lipat;
 * kontrol Judul karena itu dimatikan di Mobile.
 */
new class extends Component
{
    public bool $dark = false;

    public bool $mobile = false;

    public bool $judul = true;

    public bool $anchor = true;

    /**
     * Entri Playground. Kedua lapis (Desktop dan Mobile) dirender bersamaan
     * saat transisi, jadi id bagiannya diberi awalan berbeda supaya tidak
     * bentrok.
     *
     * @return list<array{id: string, label: string}>
     */
    public function items(string $prefix): array
    {
        return array_map(fn (array $item) => ['id' => "pg-{$prefix}-".$item[0], 'label' => $item[1]], [
            ['dasar', 'Tampilan dasar'],
            ['variasi', 'Variasi'],
            ['platform', 'platform'],
            ['heading', 'heading · sub-heading · description'],
            ['button', 'show-button · button-label · button-href'],
            ['dark', 'Dark mode'],
            ['props', 'Properties'],
        ]);
    }

    #[Computed]
    public function usage(): string
    {
        if ($this->mobile) {
            return implode("\n", array_filter([
                '<x-ceplok::drawer wire:model="menu" position="right" size="s">',
                '    <nav>…tautan navbar…</nav>',
                '    <div x-data="{ buka: true }">',
                '        <button type="button" x-on:click="buka = ! buka">On this page</button>',
                '        <x-ceplok::table-of-contents',
                '            x-show="buka"',
                '            :⟦items⟧="$items"',
                '            ⟦*layout⟧="list"',
                '            :⟦*title⟧="false"',
                $this->dark ? '            ⟦*dark-mode⟧' : null,
                $this->anchor ? null : '            :⟦*anchor⟧="false"',
                '            x-on:item-click="$wire.menu = false"',
                '        />',
                '    </div>',
                '</x-ceplok::drawer>',
            ], fn ($line) => $line !== null));
        }

        return implode("\n", array_filter([
            '<x-ceplok::table-of-contents',
            '    :⟦items⟧="$items"',
            '    class="sticky top-10"',
            $this->dark ? '    ⟦*dark-mode⟧' : null,
            $this->judul ? null : '    :⟦*title⟧="false"',
            $this->anchor ? null : '    :⟦*anchor⟧="false"',
            '/>',
        ], fn ($line) => $line !== null));
    }
};
?>

@php
    // Lapis isi bingkai. Yang muncul memudar masuk sedikit setelah yang lama
    // mulai memudar keluar (delay 150ms), supaya keduanya tidak tampak
    // bertumpuk di tengah transisi.
    $lapis = fn (bool $tampil) => $tampil
        ? 'absolute inset-0 transition-[opacity,transform] duration-300 ease-out motion-reduce:transition-none translate-y-0 opacity-100 delay-150'
        : 'absolute inset-0 transition-[opacity,transform] duration-300 ease-out motion-reduce:transition-none pointer-events-none translate-y-2 opacity-0';
    // wire:key berganti bersama anchor: scroll-spy dipasang ulang dengan pengaturan baru.
    $kunci = $anchor ? 'anchor' : 'tombol';
@endphp

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <p class="mb-6 text-body-sm text-gray-500">
            Satu daftar isi yang bisa Anda utak-atik lewat kontrol di bawahnya; bagian Penggunaan
            menuliskan kodenya. Isi bingkai berupa bagian yang bisa digulir: klik entri untuk melompat,
            atau gulir isinya dan lihat sorotan ikut berpindah. Di platform Mobile, ikon kiri navbar
            membuka menu sidebar, dan ikon kanan membuka menu navbar dengan daftar isi yang bisa dilipat
            di bawahnya.
        </p>

        <x-docs.stage :max-width="$mobile ? 'max-w-[360px]' : 'max-w-full'" :dark="$dark">
            {{-- Bingkai diberi data-theme supaya token isinya ikut Tampilan, dan
                 `relative` supaya panel menu ponsel muncul di sini. --}}
            <div
                data-theme="{{ $dark ? 'dark' : 'light' }}"
                @class([
                    'relative h-105 overflow-hidden border-border-strong bg-toc-surface transition-[border-radius,border-width,box-shadow,background-color] duration-300 motion-reduce:transition-none',
                    'rounded-[1.75rem] border-4 shadow-lg' => $mobile,
                    'rounded-xl border shadow-none' => ! $mobile,
                ])
            >
                {{-- Dua lapis bertumpuk yang saling memudar; lapis yang tersembunyi
                     diberi `inert` supaya tidak bisa difokus. --}}
                <div wire:key="pg-toc-lapis-d" class="{{ $lapis(! $mobile) }}" @if ($mobile) inert @endif>
                    <div class="flex h-full gap-8 pl-6">
                        <div id="pg-toc-scroll-d" class="min-h-0 min-w-0 flex-1 overflow-y-auto py-6 pr-2">
                            <x-docs.toc-sections :items="$this->items('d')" scroll="pg-toc-scroll-d" :anchors="$anchor" />
                        </div>
                        <x-ceplok::table-of-contents
                            wire:key="pg-toc-d-{{ $kunci }}"
                            class="hidden shrink-0 pt-6 pr-6 sm:block"
                            :items="$this->items('d')"
                            layout="list"
                            :title="$judul ? 'On this page' : false"
                            :anchor="$anchor"
                            :dark-mode="$dark"
                            scroll-container="#pg-toc-scroll-d"
                            :offset="24"
                        />
                    </div>
                </div>

                <div wire:key="pg-toc-lapis-m" class="{{ $lapis($mobile) }}" @if (! $mobile) inert @endif>
                    <x-docs.toc-phone>
                        <x-slot:toc>
                            <x-ceplok::table-of-contents
                                wire:key="pg-toc-m-{{ $kunci }}"
                                :items="$this->items('m')"
                                layout="list"
                                :title="false"
                                :anchor="$anchor"
                                :dark-mode="$dark"
                                scroll-container="#pg-toc-scroll-m"
                                :offset="16"
                            />
                        </x-slot:toc>
                        <div id="pg-toc-scroll-m" class="min-h-0 flex-1 overflow-y-auto p-4">
                            <x-docs.toc-sections :items="$this->items('m')" scroll="pg-toc-scroll-m" :anchors="$anchor" />
                        </div>
                    </x-docs.toc-phone>
                </div>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="mobile" :value="$mobile" :options="[
                    ['value' => false, 'label' => 'Desktop'],
                    ['value' => true, 'label' => 'Mobile'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Judul">
                {{-- Di Mobile judul daftar isi sudah menjadi tombol lipat di menu navbar. --}}
                <x-docs.segmented label="Tampilkan judul" model="judul" :value="$judul" :disabled="$mobile" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Anchor">
                <x-docs.segmented label="Entri berupa anchor" model="anchor" :value="$anchor" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <p class="mb-6 text-body-sm text-gray-500">
            Blok ini mengikuti kontrol di Playground. Prop yang nilainya masih bawaan sengaja tidak
            ditulis. Di aplikasi, <span class="text-primary-500">layout</span> cukup dibiarkan
            <span class="text-primary-500">auto</span>: daftar di layar 1024px ke atas, Drawer di bawahnya.
            Untuk pola Mobile di Playground, daftar isinya diletakkan sendiri di menu navbar.
        </p>
        <x-docs.code :code="$this->usage" flush />

        <h3 class="mt-8 text-sm font-black text-gray-900">Menempel dan offset</h3>
        <p class="mt-1 mb-3 max-w-2xl text-body-sm text-gray-500">
            Komponen ini tidak menempel sendiri; beri <span class="text-primary-500">sticky top-*</span> lewat class. Beri
            bagian yang dituju <span class="text-primary-500">scroll-mt-*</span> supaya judulnya tidak tertutup navbar. Untuk router
            hash, bentuk alamatnya lewat <span class="text-primary-500">get-href</span>.
        </p>
        <x-docs.code flush>
            @verbatim
            <x-ceplok::table-of-contents
                :items="$items"
                class="sticky top-10"
                :⟦offset⟧="80"
                ⟦get-href⟧="#/panduan#{id}"
            />

            <section id="dasar" class="scroll-mt-24">…</section>
            @endverbatim
        </x-docs.code>
    </x-docs.section>
</div>
