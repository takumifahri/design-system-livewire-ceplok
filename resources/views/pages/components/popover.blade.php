@php
    $props = [
        ['trigger', 'slot', 'wajib', 'Satu elemen native <button>. Elemen non-interaktif, wrapper, dan trigger yang dirender sebagai anchor tidak didukung.'],
        ['title', 'string | slot', 'wajib', 'Konten judul pada header Popover.'],
        ['slot', 'slot', 'wajib', 'Konten body Popover.'],
        ['side', '"top" | "right" | "bottom" | "left"', 'right', 'Preferensi posisi panel terhadap trigger; posisi dapat berbalik agar aman di viewport.'],
        ['data-theme', '"light" | "dark"', 'inherit', 'Boundary tema eksplisit. Tanpa atribut ini Popover mengikuti data-theme ancestor; dark-mode tetap memaksa dark.'],
        ['dark-mode', 'bool', 'false', 'Menggunakan tampilan gelap pada panel Popover sebagai override kompatibilitas.'],
        ['open', 'bool', 'false', 'Membuka Popover sejak awal, padanan defaultOpen.'],
        ['wire:model', 'atribut', '—', 'Mengikat visibilitas ke properti boolean Livewire (atau x-model untuk Alpine), padanan open dan onOpenChange.'],
        ['wire:open-change', 'event', '—', 'Dipicu ketika interaksi meminta atau menghasilkan perubahan visibilitas, dengan $event.detail.open.'],
        ['…atribut', 'atribut HTML', '—', 'Atribut <div> yang relevan diteruskan ke panel. Native title dan popover dikelola oleh Popover; wire:model, x-model, dan wire:key dipasang di pembungkus.'],
    ];
    $hl = 'text-primary-500';
    $positionSides = ['top' => 'Top', 'right' => 'Right', 'bottom' => 'Bottom', 'left' => 'Left'];
    $panelClasses = [
        'top' => 'bottom-[calc(50%+26px)] left-1/2 -translate-x-1/2',
        'right' => 'top-1/2 left-[calc(50%+28px)] -translate-y-1/2',
        'bottom' => 'top-[calc(50%+26px)] left-1/2 -translate-x-1/2',
        'left' => 'top-1/2 right-[calc(50%+28px)] -translate-y-1/2',
    ];
    $arrowClasses = [
        'top' => 'bottom-[-8px] left-1/2 -translate-x-1/2 rotate-180',
        'right' => 'top-1/2 left-[-8px] -translate-y-1/2 -rotate-90',
        'bottom' => 'top-[-8px] left-1/2 -translate-x-1/2',
        'left' => 'top-1/2 right-[-8px] -translate-y-1/2 rotate-90',
    ];
@endphp

<x-docs.layout title="Popover">
    <x-docs.page eyebrow="Components · Popover" title="Popover"
        description="Panel informasi non-modal yang dibuka dari trigger dan ditempatkan secara aman di sekitar trigger."
        :toc="['popover' => 'Popover', 'positions' => 'Positions', 'dark-mode' => 'Dark mode', 'playground' => 'Playground', 'penggunaan' => 'Penggunaan', 'livewire' => 'Livewire', 'properties' => 'Properties']">
        <x-docs.section id="popover" title="Popover">
            <x-docs.lead>
                Gunakan Popover untuk informasi kontekstual yang perlu dibuka dari native <span class="{{ $hl }}">&lt;button&gt;</span>.
                Ikon informasi di bawah hanya contoh; trigger dapat berupa tombol native lain yang sesuai konteks.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-[640px]">
                <div class="flex min-h-32 items-center justify-center gap-0 p-6">
                    <span class="text-sm font-medium text-gray-900">Nomor referensi</span>
                    <x-docs.popover-example label="Informasi nomor referensi" title="Nomor referensi" trigger-class="-ml-1">
                        Nomor referensi digunakan untuk melacak status pengajuan.
                    </x-docs.popover-example>
                </div>
            </x-docs.stage>
        </x-docs.section>

        <x-docs.section id="positions" title="Positions">
            <x-docs.lead>
                <span class="{{ $hl }}">side</span> menentukan preferensi posisi panel terhadap trigger. Popover dapat berbalik ke sisi berlawanan ketika ruang viewport tidak cukup.
            </x-docs.lead>
            <div class="grid gap-8">
                @foreach ($positionSides as $value => $label)
                    <article>
                        <h3 class="mb-3 text-sm font-black text-gray-900">{{ $label }}</h3>
                        <div class="overflow-x-auto rounded-xl border border-border bg-surface-subtle">
                            <div class="relative h-80 min-w-[600px]">
                                <span aria-hidden="true" class="absolute top-1/2 left-1/2 inline-flex size-9 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-md text-gray-500">
                                    <x-ceplok::icon>{{ svg('ceplok-o-info-circle') }}</x-ceplok::icon>
                                </span>
                                <div aria-hidden="true" class="absolute w-[255px] shrink-0 [filter:drop-shadow(0_1px_2px_rgb(0_0_0/0.08))] {{ $panelClasses[$value] }}">
                                    <span class="pointer-events-none absolute z-30 size-4 {{ $arrowClasses[$value] }}">
                                        <span class="absolute top-[3.25px] left-[3px] size-2.5 rotate-45 border-t border-l border-popover-border {{ $value === 'bottom' ? 'bg-popover-header-surface' : 'bg-popover-surface' }}"></span>
                                    </span>
                                    <div class="relative z-10 overflow-hidden rounded-md bg-popover-surface">
                                        <div class="border-b border-popover-border bg-popover-header-surface px-3 py-1.5 text-sm leading-[1.5] font-semibold text-popover-header-content">Popover</div>
                                        <div class="bg-popover-surface px-3 py-2 text-sm leading-[1.5] font-medium text-popover-body-content">Popover Body Text, Popover Body Text, Popover Body Text</div>
                                        <span class="pointer-events-none absolute inset-0 z-20 rounded-md border border-popover-border"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <x-docs.code class="mt-8">
                @verbatim
                <x-ceplok::popover title="Popover" ⟦side⟧="top">
                    <x-slot:trigger
                        raw
                        type="button"
                        aria-label="Buka informasi"
                    >
                        <x-ceplok::icon>{{ svg('ceplok-o-info-circle') }}</x-ceplok::icon>
                    </x-slot:trigger>
                    Popover Body Text
                </x-ceplok::popover>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Popover mewarisi <span class="{{ $hl }}">data-theme</span> dari consumer boundary. Pada Dark, header memakai gray-700,
                body gray-800, judul gray-50, dan teks body gray-400. Prop <span class="{{ $hl }}">dark-mode</span> tetap tersedia sebagai
                override kompatibilitas. Arrow mengikuti surface yang bersentuhan dengannya.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-[640px]" dark>
                <div data-theme="dark" class="flex min-h-32 items-center justify-center p-6">
                    <x-docs.popover-example label="Buka Popover dark mode" side="top" dark />
                </div>
            </x-docs.stage>
            <x-docs.code>
                @verbatim
                <div ⟦data-theme⟧="dark">
                    <x-ceplok::popover ⟦title⟧="Popover" ⟦side⟧="top">
                        <x-slot:⟦trigger⟧
                            raw
                            type="button"
                            aria-label="Buka informasi"
                            class="inline-flex size-9 items-center justify-center text-gray-400 focus-visible:outline-2"
                        >
                            <x-ceplok::icon>{{ svg('ceplok-o-info-circle') }}</x-ceplok::icon>
                        </x-slot:trigger>
                        Popover Body Text
                    </x-ceplok::popover>
                </div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.popover />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Ikat visibilitas dengan <span class="{{ $hl }}">wire:model</span> dan tangkap perubahan melalui
                <span class="{{ $hl }}">wire:open-change</span> ketika state tersebut perlu diproses di server.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-[640px]">
                <livewire:playground.popover-livewire />
            </x-docs.stage>
            <x-docs.code>
                @verbatim
                <x-ceplok::popover
                    title="Status pengajuan"
                    ⟦wire:model.live⟧="open"
                    ⟦wire:open-change⟧="changed($event.detail.open)"
                >
                    <x-slot:trigger raw type="button">
                        Lihat status
                    </x-slot:trigger>
                    Pengajuan sedang diverifikasi.
                </x-ceplok::popover>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut HTML native yang relevan juga dapat diteruskan.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
