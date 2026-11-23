<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Dropdown. Panelnya dibuka sejak halaman dimuat dan
 * dibuka lagi setelah kontrol diganti (lihat resources/js/docs/dropdown-playground.js).
 *
 * `wire:key` Dropdown-nya memuat mode, contoh, dan `align`: ketiganya
 * mengubah x-data atau bentuk panelnya (menu atau daftar pilihan), yang
 * dibaca kit sekali saat Dropdown diinisialisasi.
 */
new class extends Component
{
    /** `action` atau `selection`. */
    public string $mode = 'action';

    /** `actions`, `icons`, `radio`, `checkbox`, atau `scroll`. */
    public string $example = 'actions';

    public bool $separator = false;

    public bool $description = false;

    public bool $disabled = false;

    public string $align = 'start';

    public bool $closeOnSelect = true;

    public bool $dark = false;

    /** Pilihan aktif pada mode Selection. */
    public string $selected = 'Terbaru';

    /** Kelompok pada daftar aksi dan daftar pilihan dibuat lewat data; pada isi bebas lewat <hr>. */
    #[Computed]
    public function separatorFromData(): bool
    {
        return $this->mode === 'selection' || in_array($this->example, ['actions', 'icons'], true);
    }

    /** Kode di blok Penggunaan, mengikuti mode dan kontrol Playground. */
    #[Computed]
    public function usage(): string
    {
        $lines = match (true) {
            $this->mode === 'selection' => $this->selectionUsage(),
            $this->mode === 'navigation' => $this->navigationUsage(),
            in_array($this->example, ['actions', 'icons'], true) => $this->actionUsage(),
            default => $this->contentUsage(),
        };

        return implode("\n", array_filter($lines, fn (?string $line) => $line !== null));
    }

    /** @return list<?string> */
    private function actionUsage(): array
    {
        $icons = $this->example === 'icons';
        $item = function (string $label, string $icon, ?string $description = null, ?string $tone = null, bool $disabled = false) use ($icons): string {
            $parts = array_filter([
                "'label' => '{$label}'",
                $icons ? "⟦'icon'⟧ => '{$icon}'" : null,
                $description ? "⟦'description'⟧ => '{$description}'" : null,
                $tone ? "'tone' => '{$tone}'" : null,
                $disabled ? "⟦'disabled'⟧ => true" : null,
            ]);

            return '['.implode(', ', $parts).'],';
        };

        $profil = $item('Profil', 'ceplok-o-user', $this->description ? 'Lihat dan ubah profil.' : null);
        $pengaturan = $item('Pengaturan', 'ceplok-o-cog', $this->description ? 'Atur preferensi akun.' : null);
        $bantuan = $item('Bantuan', 'ceplok-o-question-circle', disabled: $this->disabled);
        $keluar = $item('Keluar', 'ceplok-o-arrow-right-to-bracket', tone: 'danger');

        $list = $this->separator
            ? [
                '    :⟦groups⟧="[',
                '        [',
                "            'id' => 'utama',",
                "            'items' => [",
                '                '.$profil,
                '                '.$pengaturan,
                '                '.$bantuan,
                '            ],',
                '        ],',
                '        [',
                "            'id' => 'keluar',",
                "            ⟦'separator'⟧ => true,",
                "            'items' => [",
                '                '.$keluar,
                '            ],',
                '        ],',
                '    ]"',
            ]
            : [
                '    :items="[',
                '        '.$profil,
                '        '.$pengaturan,
                '        '.$bantuan,
                '        '.$keluar,
                '    ]"',
            ];

        return [
            '<x-ceplok::dropdown',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            "    ⟦align⟧=\"{$this->align}\"",
            '    :⟦close-on-select⟧="'.($this->closeOnSelect ? 'true' : 'false').'"',
            ...$list,
            '>',
            '    <x-slot:trigger raw class="…">Dropdown button</x-slot:trigger>',
            '</x-ceplok::dropdown>',
        ];
    }

    /** @return list<?string> */
    private function contentUsage(): array
    {
        $hr = $this->separator
            ? '        <hr ⟦class⟧="'.($this->dark ? 'my-1 border-0 border-t border-gray-700' : 'my-1 border-0 border-t border-border').'">'
            : null;
        $disabled = $this->disabled ? ' ⟦disabled⟧' : '';
        $contentLabel = match ($this->example) {
            'radio' => 'Pilih akses',
            'scroll' => 'Pilih notifikasi dengan scroll',
            default => 'Pilih notifikasi',
        };

        $body = match ($this->example) {
            'radio' => [
                '    <div class="flex flex-col gap-4">',
                '        <x-ceplok::⟦radio⟧ :⟦dark-mode⟧="$dark" name="access" value="viewer" label="Viewer" />',
                '        <x-ceplok::⟦radio⟧ :⟦dark-mode⟧="$dark" name="access" value="editor" label="Editor"'.($this->description ? ' ⟦helper-text⟧="Dapat mengubah konten."' : '').' checked />',
                $hr,
                '        <x-ceplok::⟦radio⟧ :⟦dark-mode⟧="$dark" name="access" value="admin" label="Admin"'.$disabled.' />',
                '    </div>',
            ],
            'scroll' => [
                '    <div ⟦class⟧="flex max-h-48 flex-col gap-4 overflow-y-auto overscroll-y-contain'.($this->dark ? ' ds-scroll-y …' : '').'">',
                '        <x-ceplok::checkbox :⟦dark-mode⟧="$dark" label="Email"'.($this->description ? ' ⟦helper-text⟧="Notifikasi melalui email."' : '').' checked />',
                '        <x-ceplok::checkbox :dark-mode="$dark" label="Push notification" />',
                '        {{-- … --}}',
                $hr,
                '        <x-ceplok::checkbox :dark-mode="$dark" label="Pembaruan produk" />',
                '        {{-- … --}}',
                '        <x-ceplok::checkbox :dark-mode="$dark" label="Keamanan"'.$disabled.' />',
                '        {{-- … --}}',
                '        <x-ceplok::checkbox :dark-mode="$dark" label="Sistem" />',
                '    </div>',
            ],
            default => [
                '    <div class="flex flex-col gap-4">',
                '        <x-ceplok::⟦checkbox⟧ :⟦dark-mode⟧="$dark" label="Email"'.($this->description ? ' ⟦helper-text⟧="Kirim pembaruan melalui email."' : '').' />',
                '        <x-ceplok::⟦checkbox⟧ :⟦dark-mode⟧="$dark" label="Push notification" checked />',
                $hr,
                '        <x-ceplok::⟦checkbox⟧ :⟦dark-mode⟧="$dark" label="SMS"'.$disabled.' />',
                '    </div>',
            ],
        };

        return [
            '@php($dark = '.($this->dark ? 'true' : 'false').')',
            '',
            '<x-ceplok::dropdown',
            '    :⟦dark-mode⟧="$dark"',
            "    ⟦align⟧=\"{$this->align}\"",
            "    ⟦content-label⟧=\"{$contentLabel}\"",
            '    ⟦content-class⟧="p-4"',
            '>',
            '    <x-slot:trigger raw class="…">Dropdown button</x-slot:trigger>',
            ...$body,
            '</x-ceplok::dropdown>',
        ];
    }

    /** @return list<?string> */
    private function selectionUsage(): array
    {
        $item = fn (string $label, ?string $description = null, bool $disabled = false) => [
            '        [',
            "            'label' => '{$label}',",
            $description ? "            ⟦'description'⟧ => '{$description}'," : null,
            $disabled ? "            ⟦'disabled'⟧ => true," : null,
            "            ⟦'selected'⟧ => \$urut === '{$label}',",
            "            ⟦'attributes'⟧ => ['wire:click' => \"\\\$set('urut', '{$label}')\"],",
            '        ],',
        ];

        $list = $this->separator
            ? [
                '    :⟦groups⟧="[',
                "        ['id' => 'waktu', 'label' => 'Waktu', 'items' => array_slice(\$urutan, 0, 2)],",
                "        ['id' => 'nama', ⟦'separator'⟧ => true, 'items' => array_slice(\$urutan, 2)],",
                '    ]"',
            ]
            : ['    :⟦items⟧="$urutan"'];

        return [
            "{{-- Komponen Livewire: public string \$urut = '{$this->selected}'; --}}",
            '@php',
            '    $urutan = [',
            ...$item('Terbaru', $this->description ? 'Urutkan dari yang paling baru.' : null),
            ...$item('Terlama', $this->description ? 'Urutkan dari yang paling lama.' : null),
            ...$item('Nama A–Z', disabled: $this->disabled),
            '    ];',
            '@endphp',
            '',
            '<x-ceplok::dropdown',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            "    ⟦align⟧=\"{$this->align}\"",
            '    :⟦close-on-select⟧="'.($this->closeOnSelect ? 'true' : 'false').'"',
            '    ⟦content-label⟧="Urutan hasil"',
            ...$list,
            '>',
            '    <x-slot:⟦trigger⟧ raw class="…">Urutkan: {{ $urut }}</x-slot:trigger>',
            '</x-ceplok::dropdown>',
        ];
    }

    /** @return list<?string> */
    private function navigationUsage(): array
    {
        return [
            '<x-ceplok::dropdown',
            $this->dark ? '    dark-mode' : null,
            "    ⟦align⟧=\"{$this->align}\"",
            '    :⟦close-on-select⟧="'.($this->closeOnSelect ? 'true' : 'false').'"',
            '    :items="[',
            "        ['label' => 'Ringkasan', 'href' => '/ringkasan', 'current' => true],",
            "        ['label' => 'Dokumentasi', 'href' => 'https://example.com', 'target' => '_blank', 'rel' => 'noopener noreferrer'],",
            "        ['label' => 'Tujuan terkunci', 'href' => '/terkunci', 'disabled' => true],",
            '    ]"',
            '>',
            '    <x-slot:trigger raw class="…">Buka navigasi</x-slot:trigger>',
            '</x-ceplok::dropdown>',
        ];
    }
};
?>

<div class="space-y-16" x-data="docsDropdownPlayground">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Mode Action menjalankan perintah, Navigation memakai link native melalui item generated, dan Mode
            Selection memakai <span class="text-primary-500">selected</span> untuk membentuk listbox berisi option yang dapat
            dinavigasi lewat keyboard. Pratinjaunya dibuka sejak halaman dimuat supaya tiap variasi
            mudah dibandingkan.
        </x-docs.lead>

        <x-docs.stage max-width="max-w-xl" :dark="$dark">
            <div x-ref="stage" class="flex min-h-80 items-start justify-center pt-10">
                @if ($mode === 'selection')
                    <x-docs.dropdown-example
                        wire:key="dropdown-selection-{{ $align }}-{{ (int) $closeOnSelect }}"
                        example="selection"
                        model="selected"
                        :selected="$selected"
                        :separator="$separator"
                        :description="$description"
                        :disabled="$disabled"
                        :align="$align"
                        :close-on-select="$closeOnSelect"
                        :dark="$dark"
                    />
                @elseif ($mode === 'navigation')
                    <x-ceplok::dropdown
                        wire:key="dropdown-navigation-{{ $align }}-{{ (int) $closeOnSelect }}"
                        :dark-mode="$dark"
                        :align="$align"
                        :close-on-select="$closeOnSelect"
                        :items="[
                            ['label' => 'Ringkasan', 'href' => '/ringkasan', 'current' => true],
                            ['label' => 'Dokumentasi', 'href' => 'https://example.com', 'target' => '_blank', 'rel' => 'noopener noreferrer'],
                            ['label' => 'Tujuan terkunci', 'href' => '/terkunci', 'disabled' => $disabled],
                        ]"
                    >
                        <x-slot:trigger raw class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary-700 px-4 text-base font-medium text-white transition-colors duration-200 hover:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-400">Buka navigasi</x-slot:trigger>
                    </x-ceplok::dropdown>
                @else
                    <x-docs.dropdown-example
                        wire:key="dropdown-{{ $example }}-{{ $align }}-{{ (int) $closeOnSelect }}"
                        :example="$example"
                        group-name="playground-dropdown"
                        :separator="$separator"
                        :description="$description"
                        :disabled="$disabled"
                        :align="$align"
                        :close-on-select="$closeOnSelect"
                        :dark="$dark"
                    />
                @endif
            </div>
        </x-docs.stage>

        <x-docs.controls x-on:pointerdown.capture="remember($event)">
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode Dropdown" model="mode" :value="$mode" :options="[
                    ['value' => 'action', 'label' => 'Action'],
                    ['value' => 'navigation', 'label' => 'Navigation'],
                    ['value' => 'selection', 'label' => 'Selection'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Align">
                <x-docs.segmented label="Pilih perataan panel" model="align" :value="$align" :options="[
                    ['value' => 'start', 'label' => 'Start'],
                    ['value' => 'center', 'label' => 'Center'],
                    ['value' => 'end', 'label' => 'End'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Close on select">
                <x-docs.segmented
                    label="Tutup panel setelah item dijalankan"
                    model="closeOnSelect"
                    :value="$closeOnSelect"
                    :disabled="$mode === 'action' && ! in_array($example, ['actions', 'icons'], true)"
                    :options="[
                        ['value' => true, 'label' => 'true'],
                        ['value' => false, 'label' => 'false'],
                    ]"
                />
                @if ($mode === 'action' && ! in_array($example, ['actions', 'icons'], true))
                    <p class="mt-2 text-xs text-gray-500">
                        Hanya berlaku pada item generated; konten kustom menutup panel secara eksplisit.
                    </p>
                @endif
            </x-docs.control>

            <x-docs.control label="Kelompok">
                <x-docs.segmented label="Tampilkan kelompok" model="separator" :value="$separator" :disabled="$mode === 'navigation'" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak Ada'],
                ]" />
                <p class="mt-2 text-xs text-gray-500">
                    {{ $mode === 'navigation' ? 'Navigation memakai satu daftar items agar contoh link tetap ringkas.' : ($this->separatorFromData ? 'Kelompok dibuat melalui groups dengan separator: true.' : 'Kelompok dibuat dengan garis pemisah (border) di antara isi slot.') }}
                </p>
            </x-docs.control>

            <x-docs.control label="Keterangan">
                <x-docs.segmented label="Tampilkan keterangan" model="description" :value="$description" :disabled="$mode === 'navigation'" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak Ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Disable">
                <x-docs.segmented label="Matikan satu pilihan" model="disabled" :value="$disabled" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak Ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Contoh">
                <x-docs.segmented
                    label="Pilih contoh Dropdown"
                    model="example"
                    :value="$example"
                    item-class="basis-1/2 justify-center px-2.5 sm:basis-1/3"
                    :disabled="$mode !== 'action'"
                    wrap
                    :options="[
                        ['value' => 'actions', 'label' => 'Tanpa ikon'],
                        ['value' => 'icons', 'label' => 'Dengan ikon'],
                        ['value' => 'radio', 'label' => 'Radio'],
                        ['value' => 'checkbox', 'label' => 'Checkbox'],
                        ['value' => 'scroll', 'label' => 'Dengan scroll'],
                    ]"
                />
                @if ($mode !== 'action')
                    <p class="mt-2 text-xs text-gray-500">
                        Contoh custom content hanya tersedia pada Action. Navigation dan Selection memakai item generated.
                    </p>
                @endif
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Action cukup diisi lewat <span class="text-primary-500">items</span> atau <span class="text-primary-500">groups</span>.
            Navigation memakai struktur item yang sama dengan <span class="text-primary-500">href</span>,
            <span class="text-primary-500">target</span>, <span class="text-primary-500">rel</span>, dan
            <span class="text-primary-500">current</span>, tanpa mode production tambahan. Selection
            menambahkan <span class="text-primary-500">selected</span> dan pembaruan state pada setiap option. Panel
            yang berisi kontrol bebas diisi lewat slot dan diberi jarak sendiri lewat
            <span class="text-primary-500">content-class</span>. Kode berikut mengikuti Mode dan kontrol Playground.
        </x-docs.lead>
        <p class="mt-2 mb-4 max-w-2xl text-body-sm text-gray-500">
            Slot <span class="text-primary-500">trigger</span> dirender Dropdown sendiri: sebagai Button kit dengan atribut slot sebagai
            props-nya, atau sebagai <span class="text-primary-500">&lt;button&gt;</span> polos dengan <span class="text-primary-500">raw</span>.
            Dropdown mengelola <span class="text-primary-500">popovertarget</span>,
            <span class="text-primary-500">popovertargetaction</span>, dan
            <span class="text-primary-500">aria-expanded</span>; Selection juga menerima
            <span class="text-primary-500">aria-haspopup=&quot;listbox&quot;</span> dan
            <span class="text-primary-500">aria-controls</span>. Atribut ini tetap terpasang ketika
            Livewire merender ulang. Trigger icon-only wajib memiliki
            nama aksesibel, misalnya lewat <span class="text-primary-500">aria-label</span> pada slotnya. Isi slot trigger tidak boleh berisi
            tombol atau tautan lain.
        </p>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
