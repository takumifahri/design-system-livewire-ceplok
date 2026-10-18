@php
    $variants = ['gray', 'danger', 'warning', 'success', 'brand'];

    $props = [
        ['variant', '"gray" | "danger" | "warning" | "success" | "brand"', 'gray', 'Warna semantik badge.'],
        ['size', '"sm" | "lg"', 'sm', 'Ukuran teks, ikon, dan tombol tutup.'],
        ['icon', 'Htmlable | slot', 'null', 'Ikon di kiri label, mis. svg(\'ceplok-s-clock\'). Pakai SVG dengan currentColor.'],
        ['dismissible', 'bool', 'false', 'Menampilkan tombol tutup (×) di kanan label.'],
        ['open', 'bool', 'true', 'Tampil sejak awal. Bisa dikendalikan dari luar lewat wire:model atau x-model.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: latar -900 dengan teks -300.'],
        ['wire:dismiss', 'event', '—', 'Dipicu saat tombol tutup diklik; padanan onDismiss. Juga bisa x-on:dismiss.'],
        ['slot', 'slot', '—', 'Label badge.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <span> diteruskan (class, id, …).'],
    ];
@endphp

<x-docs.layout title="Badge">
    <x-docs.page
        title="Badge"
        description="Label status ringkas untuk menandai kondisi sebuah entitas. Lima variant warna, dua ukuran, ikon dan tombol tutup opsional. Tersedia juga dalam tampilan gelap."
        :toc="[
            'badge' => 'Badge',
            'opsional' => 'Ikon dan tombol tutup',
            'ukuran' => 'Ukuran',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="badge" title="Badge">
            <x-docs.lead>
                Label beradius 6px dengan padding 2px × 12px. Lima variant warnanya punya makna semantik yang sama
                dengan Alert dan token warna lain di design system ini.
            </x-docs.lead>
            <x-docs.demo>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ceplok::badge variant="gray">Draf</x-ceplok::badge>
                    <x-ceplok::badge variant="danger">Ditolak</x-ceplok::badge>
                    <x-ceplok::badge variant="warning">Menunggu</x-ceplok::badge>
                    <x-ceplok::badge variant="success">Aktif</x-ceplok::badge>
                    <x-ceplok::badge variant="brand">Baru</x-ceplok::badge>
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::badge ⟦variant⟧="success">Aktif</x-ceplok::badge>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="opsional" title="Ikon dan tombol tutup">
            <x-docs.lead>
                Ikon kiri dan tombol tutup sama-sama opsional. Ikon mengikuti warna teks, jadi cukup kirim SVG
                dengan <span class="text-primary-500">currentColor</span>. Tombol tutup menyembunyikan badge dengan transisi singkat.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-3">
                <x-docs.demo label="Dengan ikon">
                    <x-ceplok::badge variant="brand" :icon="svg('ceplok-s-clock')">Badge</x-ceplok::badge>
                </x-docs.demo>
                <x-docs.demo label="Dengan tombol tutup">
                    <x-ceplok::badge variant="brand" dismissible>Badge</x-ceplok::badge>
                </x-docs.demo>
                <x-docs.demo label="Keduanya">
                    <x-ceplok::badge variant="brand" :icon="svg('ceplok-s-clock')" dismissible>Badge</x-ceplok::badge>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::badge variant="brand" :⟦icon⟧="svg('ceplok-s-clock')" ⟦dismissible⟧>Badge</x-ceplok::badge>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="ukuran" title="Ukuran">
            <x-docs.lead>
                <span class="text-primary-500">sm</span> (bawaan) memakai teks 14px, cocok untuk tabel dan kartu. <span class="text-primary-500">lg</span> memakai teks 16px
                untuk area yang lebih lega. Padding dan radiusnya sama.
            </x-docs.lead>
            <x-docs.demo>
                <div class="flex flex-col gap-4">
                    @foreach (['sm', 'lg'] as $size)
                        <div class="flex flex-wrap items-center gap-4">
                            @foreach ($variants as $variant)
                                <x-ceplok::badge :variant="$variant" :size="$size" :icon="svg('ceplok-s-clock')" dismissible>Badge</x-ceplok::badge>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::badge ⟦size⟧="lg" variant="success" :icon="svg('ceplok-s-clock')" dismissible>Badge</x-ceplok::badge>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Prop <span class="text-primary-500">dark-mode</span> membalik pasangan warnanya: latar -900 (gray-700 untuk variant gray) dan
                teks -300, keduanya di atas 4,5:1.
            </x-docs.lead>
            <x-docs.demo dark>
                <div class="flex flex-col gap-4">
                    @foreach (['sm', 'lg'] as $size)
                        <div class="flex flex-wrap items-center gap-4">
                            @foreach ($variants as $variant)
                                <x-ceplok::badge :variant="$variant" :size="$size" :icon="svg('ceplok-s-clock')" dismissible dark-mode>Badge</x-ceplok::badge>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::badge ⟦dark-mode⟧ variant="success" :icon="svg('ceplok-s-clock')" dismissible>Badge</x-ceplok::badge>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.badge />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Tampil/sembunyinya badge bisa diikat ke properti komponen dengan <span class="text-primary-500">wire:model</span>, dan
                tombol tutupnya memicu event <span class="text-primary-500">dismiss</span> yang bisa ditangkap <span class="text-primary-500">wire:dismiss</span>.
                Coba tutup salah satu filter di bawah, lalu pulihkan.
            </x-docs.lead>
            <x-docs.demo>
                <livewire:playground.badge-filters />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::badge
                    variant="brand"
                    dismissible
                    ⟦wire:model.live⟧="filters.aktif"
                    ⟦wire:dismiss⟧="removed('Status')"
                >
                    Status: Aktif
                </x-ceplok::badge>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="text-primary-500">&lt;span&gt;</span> standar juga diteruskan apa adanya.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="52rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
