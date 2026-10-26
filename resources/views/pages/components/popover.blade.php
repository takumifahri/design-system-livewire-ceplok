@php
    $props = [
        ['trigger', 'slot', 'wajib', 'Tombol pembuka. Atribut slot menjadi props Button kit, atau <button> polos dengan raw. Popover memasang id, aria-controls, dan aria-expanded di sana.'],
        ['title', 'string | slot', 'wajib', 'Konten judul pada header Popover.'],
        ['slot', 'slot', 'wajib', 'Konten body Popover.'],
        ['side', '"top" | "right" | "bottom" | "left"', 'right', 'Preferensi posisi panel terhadap trigger; posisi dapat berbalik agar aman di viewport.'],
        ['dark-mode', 'bool', 'false', 'Menggunakan tampilan gelap pada panel Popover.'],
        ['open', 'bool', 'false', 'Membuka Popover sejak awal, padanan defaultOpen.'],
        ['wire:model', 'atribut', '—', 'Mengikat visibilitas ke properti boolean Livewire (atau x-model untuk Alpine), padanan open + onOpenChange.'],
        ['wire:open-change', 'event', '—', 'Dipicu setiap kali Popover dibuka atau ditutup, dengan $event.detail.open.'],
        ['…atribut', 'atribut HTML', '—', 'Atribut lain diteruskan ke panel <div>. wire:model, x-model, dan wire:key dipasang di pembungkusnya.'],
    ];
    $hl = 'text-primary-500';
    $sides = ['right' => 'Right', 'left' => 'Left', 'top' => 'Top', 'bottom' => 'Bottom'];
@endphp

<x-docs.layout title="Popover">
    <x-docs.page
        eyebrow="Components · Popover"
        title="Popover"
        description="Panel informasi non-modal yang dibuka dari trigger dan ditempatkan secara aman di sekitar trigger."
        :toc="[
            'popover' => 'Popover',
            'positions' => 'Positions',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="popover" title="Popover">
            <x-docs.lead>
                Gunakan Popover untuk informasi kontekstual yang perlu dibuka dari satu elemen button-like.
                Ikon informasi di bawah hanya contoh; trigger dapat berupa tombol lain yang sesuai konteks.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-[640px]">
                <div class="flex min-h-32 items-center justify-center gap-3 p-6">
                    <span class="text-sm font-medium text-gray-900">Nomor referensi</span>
                    <x-docs.popover-example label="Informasi nomor referensi" title="Nomor referensi">
                        Nomor referensi digunakan untuk melacak status pengajuan.
                    </x-docs.popover-example>
                </div>
            </x-docs.stage>
        </x-docs.section>

        <x-docs.section id="positions" title="Positions">
            <x-docs.lead>
                <span class="{{ $hl }}">side</span> menentukan preferensi posisi panel terhadap trigger. Popover dapat berbalik ke
                sisi berlawanan ketika ruang viewport tidak cukup.
            </x-docs.lead>
            <div class="grid gap-8">
                @foreach ($sides as $value => $label)
                    <article>
                        <h3 class="mb-3 text-sm font-black text-gray-900">{{ $label }}</h3>
                        <div class="flex min-h-32 items-center justify-center rounded-xl border border-border bg-surface-subtle p-6">
                            <x-docs.popover-example :label="'Buka Popover '.$label" :side="$value" />
                        </div>
                    </article>
                @endforeach
            </div>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Prop <span class="{{ $hl }}">dark-mode</span> menggunakan header gray-700, body gray-800, judul gray-50, dan teks
                body gray-400. Arrow mengikuti surface yang bersentuhan dengannya.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-[640px]" dark>
                <div class="flex min-h-32 items-center justify-center p-6">
                    <x-docs.popover-example label="Buka Popover dark mode" side="top" dark />
                </div>
            </x-docs.stage>
            <x-docs.code>
                @verbatim
                <x-ceplok::popover ⟦title⟧="Popover" ⟦side⟧="top" ⟦dark-mode⟧>
                    <x-slot:⟦trigger⟧
                        raw
                        aria-label="Buka informasi"
                        class="inline-flex size-9 items-center justify-center text-gray-400 focus-visible:outline-2"
                    >
                        <x-ceplok::icon>{{ svg('ceplok-o-info-circle') }}</x-ceplok::icon>
                    </x-slot:trigger>
                    Popover Body Text
                </x-ceplok::popover>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.popover />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut HTML native
                yang relevan juga dapat diteruskan.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
