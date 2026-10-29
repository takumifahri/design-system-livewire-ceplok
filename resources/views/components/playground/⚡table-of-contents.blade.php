<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Table of Contents: tampilan, judul, dan anchor. Isi
 * bingkainya bagian-bagian yang bisa digulir; daftar isinya membaca wadah
 * gulir itu lewat scroll-container.
 *
 * Kontrol Platform dari docs React belum ada: layout Drawer menunggu Drawer
 * di-port ke kit Livewire.
 */
new class extends Component
{
    public bool $dark = false;

    public bool $judul = true;

    public bool $anchor = true;

    /** @return list<array{id: string, label: string}> */
    public function items(): array
    {
        return array_map(fn (array $item) => ['id' => 'pg-'.$item[0], 'label' => $item[1]], [
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

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <p class="mb-6 text-body-sm text-gray-500">
            Satu daftar isi yang bisa Anda utak-atik lewat kontrol di bawahnya; bagian Penggunaan
            menuliskan kodenya. Isi bingkai berupa bagian yang bisa digulir: klik entri untuk melompat,
            atau gulir isinya dan lihat sorotan ikut berpindah.
        </p>

        <x-docs.stage :dark="$dark">
            {{-- Bingkai diberi data-theme supaya token isinya ikut Tampilan. --}}
            <div data-theme="{{ $dark ? 'dark' : 'light' }}" class="relative h-140 overflow-hidden rounded-xl border border-border-strong bg-toc-surface transition-colors duration-300 sm:h-105">
                <div class="flex h-full flex-col sm:flex-row sm:gap-8 sm:pl-6">
                    <div id="pg-toc-scroll" class="order-last min-h-0 min-w-0 flex-1 overflow-y-auto px-4 py-6 sm:order-none sm:pr-2 sm:pl-0">
                        <div class="space-y-8 pb-[60%]">
                            @foreach ($this->items() as $i => $item)
                                <section id="{{ $item['id'] }}" wire:key="{{ $item['id'] }}">
                                    <h3 class="group flex items-baseline gap-2 text-base font-black text-content">
                                        <span class="min-w-0">{{ $item['label'] }}</span>
                                        @if ($anchor)
                                            {{-- Tautan judul menggulir wadahnya sendiri, tanpa mengubah alamat halaman docs. --}}
                                            <a
                                                href="#{{ $item['id'] }}"
                                                x-on:click.prevent="
                                                    const root = document.getElementById('pg-toc-scroll');
                                                    const el = document.getElementById(@js($item['id']));
                                                    root.scrollTo({ top: el.getBoundingClientRect().top - root.getBoundingClientRect().top + root.scrollTop - 16, behavior: 'smooth' });
                                                "
                                                aria-label="Tautan ke bagian {{ $item['label'] }}"
                                                class="rounded text-brand opacity-0 transition-opacity group-hover:opacity-100 focus-visible:opacity-100"
                                            >#</a>
                                        @endif
                                    </h3>
                                    <div class="mt-3 space-y-2" aria-hidden="true">
                                        <div class="h-2 w-full rounded bg-border transition-colors duration-300"></div>
                                        <div class="h-2 w-11/12 rounded bg-border transition-colors duration-300"></div>
                                        <div class="h-2 w-4/6 rounded bg-border transition-colors duration-300"></div>
                                        @if ($i % 3 === 1)
                                            <div class="mt-4! h-20 rounded-lg bg-border transition-colors duration-300"></div>
                                        @endif
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>

                    {{-- wire:key berganti bersama anchor: scroll-spy dipasang ulang dengan pengaturan baru. --}}
                    <x-ceplok::table-of-contents
                        wire:key="pg-toc-{{ $anchor ? 'anchor' : 'tombol' }}"
                        class="shrink-0 border-b border-border p-4 sm:border-0 sm:pt-6 sm:pr-6 sm:pl-0"
                        :items="$this->items()"
                        :title="$judul ? 'On this page' : false"
                        :anchor="$anchor"
                        :dark-mode="$dark"
                        scroll-container="#pg-toc-scroll"
                        :offset="24"
                    />
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
            <x-docs.control label="Judul">
                <x-docs.segmented label="Tampilkan judul" model="judul" :value="$judul" :options="[
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
            ditulis.
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
