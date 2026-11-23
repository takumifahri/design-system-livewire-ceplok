@php
    use App\Docs\Theme;

    $props = [
        ['trigger', 'slot', 'wajib', 'Button kit dalam button mode, atau native <button> polos dengan raw. Atribut dan isi slot diteruskan ke tombol; trigger anchor tidak didukung.'],
        ['title', 'string | slot', 'wajib', 'Konten judul pada header Popover.'],
        ['slot', 'slot', 'wajib', 'Konten body Popover.'],
        ['side', '"top" | "right" | "bottom" | "left"', 'right', 'Preferensi posisi panel terhadap trigger; posisi dapat berbalik agar aman di viewport.'],
        ['dark-mode', 'bool', 'false', 'Memaksa panel Popover bertema gelap. Tanpa prop ini Popover mengikuti tema pembungkusnya.'],
        ['wire:model', 'atribut', '—', 'Mengontrol visibilitas Popover dari luar melalui properti boolean Livewire; gunakan x-model untuk Alpine.'],
        ['open', 'bool', 'false', 'Menentukan visibilitas awal saat Popover tidak dikontrol.'],
        ['wire:open-change', 'event', '—', 'Dikirim ketika interaksi meminta atau menghasilkan perubahan visibilitas. Nilai baru tersedia di $event.detail.open.'],
        ['…atribut', 'atribut HTML', '—', 'Atribut <div> native yang relevan diteruskan. Native title, popover, dan beforetoggle tidak tersedia karena judul serta lifecycle native dikelola oleh Popover.'],
    ];
    $hl = 'text-primary-500 dark:text-primary-400';
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

    // Contoh di halaman ini mengikuti mode docs. Bagian Dark mode tetap gelap
    // di kedua mode, sama seperti ModeSection pada docs React.
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Popover">
    <x-docs.page eyebrow="Components" title="Popover"
        description="Panel informasi non-modal yang dibuka dari trigger dan ditempatkan secara aman di sekitar trigger."
        :toc="['popover' => 'Popover', 'positions' => 'Positions', 'dark-mode' => 'Dark mode', 'playground' => 'Playground', 'penggunaan' => 'Penggunaan', 'livewire' => 'Livewire', 'properties' => 'Properties']">
        <x-docs.section id="popover" title="Popover">
            <x-docs.lead>
                Gunakan Popover untuk informasi kontekstual yang dibuka dari tombol. Atribut dan isi slot
                <span class="{{ $hl }}">trigger</span> diterapkan ke Button kit, atau ke native
                <span class="{{ $hl }}">&lt;button&gt;</span> polos bila memakai <span class="{{ $hl }}">raw</span>.
                Ikon informasi di bawah hanya contoh.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-[640px]" :dark="$docsDark">
                <div class="flex min-h-32 items-center justify-center gap-0 p-6">
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Nomor referensi</span>
                    <x-docs.popover-example label="Informasi nomor referensi" title="Nomor referensi" trigger-class="-ml-1" :dark="$docsDark" :dark-mode="$docsDark">
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
                        <h3 class="mb-3 text-sm font-black text-gray-900 dark:text-white">{{ $label }}</h3>
                        <div
                            @if ($docsDark) data-theme="dark" @endif
                            @class([
                                'overflow-x-auto rounded-xl border',
                                $docsDark ? 'border-gray-800 bg-gray-900 dark:border-gray-700' : 'border-border bg-surface-subtle',
                            ])
                        >
                            <div class="relative h-80 min-w-[600px]">
                                <span aria-hidden="true" @class([
                                    'absolute top-1/2 left-1/2 inline-flex size-9 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-md',
                                    $docsDark ? 'text-gray-400' : 'text-gray-500',
                                ])>
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
                <x-ceplok::popover title="Popover" ⟦side⟧="top"⟨ dark-mode⟩>
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

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="{{ $hl }}">dark-mode</span> mengubah tema panel Popover. Tanpa prop tersebut, panel mengikuti
                <span class="{{ $hl }}">data-theme</span> dari elemen pembungkus. Header memakai gray-700, body gray-800, judul
                gray-50, dan teks body gray-400. Arrow mengikuti surface yang bersentuhan dengannya.
            </x-slot:lead>
            <x-docs.stage max-width="max-w-[640px]" dark>
                <div class="flex min-h-32 items-center justify-center p-6">
                    <x-docs.popover-example label="Buka Popover dark mode" side="top" dark dark-mode />
                </div>
            </x-docs.stage>
            <x-docs.code>
                @verbatim
                <x-ceplok::popover ⟦dark-mode⟧ title="Popover" side="top">
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
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.popover :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Ikat visibilitas dengan <span class="{{ $hl }}">wire:model</span> dan tangkap perubahan melalui
                <span class="{{ $hl }}">wire:open-change</span> ketika state tersebut perlu diproses di server.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-[640px]" :dark="$docsDark">
                <livewire:playground.popover-livewire :dark="$docsDark" />
            </x-docs.stage>
            <x-docs.code>
                @verbatim
                <x-ceplok::popover
                    title="Status pengajuan"
                    ⟦wire:model.live⟧="open"
                    ⟦wire:open-change⟧="changed($event.detail.open)"
                    ⟨dark-mode⟩
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
