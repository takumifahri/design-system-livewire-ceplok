<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Card. Kontrol "Lebar kolom" tidak mengirim prop apa pun
 * ke kartu — yang berubah hanya lebar pembungkusnya.
 */
new class extends Component
{
    public const TITLE = 'Ceplok Design System Card Desktop';

    public const DESCRIPTION = 'Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.';

    /** Pilihan aksi kartu: two, one, link, atau none. */
    public const ACTIONS = [
        'two' => 'Dua tombol',
        'one' => 'Satu tombol',
        'link' => 'Tautan',
        'none' => 'Tanpa aksi',
    ];

    /** Lebar kolom: mobile atau desktop. */
    public string $view = 'desktop';

    public bool $image = true;

    public string $action = 'two';

    public bool $dark = false;

    #[Computed]
    public function usage(): string
    {
        $width = $this->view === 'mobile' ? 'max-w-[238px]' : 'max-w-[384px]';
        $summary = implode(' · ', [
            $this->view === 'mobile' ? 'Mobile 238px' : 'Desktop 384px',
            $this->image ? 'dengan gambar' : 'tanpa gambar',
            mb_strtolower(self::ACTIONS[$this->action] ?? ''),
            $this->dark ? 'gelap' : 'terang',
        ]);
        $buttons = in_array($this->action, ['two', 'one'], true);
        $secondary = $this->dark
            ? [
                '            <x-ceplok::button variant="outline" size="s"',
                '                class="border-primary-400! text-primary-400! hover:bg-primary-400/10!">',
                '                Button text',
                '            </x-ceplok::button>',
            ]
            : ['            <x-ceplok::button size="s">Button text</x-ceplok::button>'];

        return collect([
            "{{-- {$summary} --}}",
            '{{-- Kartu tidak punya prop ukuran — lebarnya mengikuti kolom',
            '     tempatnya berada. Yang berubah cuma kolomnya. --}}',
            '<div class="⟦*'.$width.'⟧">',
            '    <x-ceplok::card',
            $this->dark ? '        ⟦*dark-mode⟧' : null,
            $this->image ? '        ⟦*image⟧="/images/card-sample.svg"' : null,
            $this->image ? '        ⟦*image-alt⟧="Suasana kerja tim"' : null,
            '        title="'.self::TITLE.'"',
            '        description="Here are the biggest enterprise technology acquisitions of 2021."',
            $this->action === 'link' ? '        ⟦*href⟧="/panduan"' : null,
            $this->action === 'link' ? '        ⟦*link-label⟧="See our guideline"' : null,
            $buttons ? '    >' : '    />',
            ...($buttons ? ['        <x-slot:⟦*actions⟧>'] : []),
            ...($this->action === 'two' ? $secondary : []),
            ...($buttons ? ['            <x-ceplok::button size="s">Button text</x-ceplok::button>', '        </x-slot:actions>', '    </x-ceplok::card>'] : []),
            '</div>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Menggabungkan prop-prop di atas dalam satu tampilan. Ganti kontrolnya,
            dan bagian Penggunaan menuliskan kodenya. Kontrol <em>Lebar kolom</em>
            tidak mengirim prop apa pun ke kartu — yang berubah lebar pembungkusnya
            saja.
        </x-docs.lead>

        <x-docs.stage :max-width="$view === 'mobile' ? 'max-w-[238px]' : 'max-w-[384px]'" :dark="$dark">
            <x-ceplok::card
                :dark-mode="$dark"
                :image="$image ? '/images/card-sample.svg' : null"
                image-alt="Suasana kerja tim"
                :title="$this::TITLE"
                :description="$this::DESCRIPTION"
                :href="$action === 'link' ? '#' : null"
                :link-label="$action === 'link' ? 'See our guideline' : null"
            >
                @if (in_array($action, ['two', 'one'], true))
                    <x-slot:actions>
                        @if ($action === 'two')
                            <x-docs.card-button variant="secondary" :dark="$dark" />
                        @endif
                        <x-docs.card-button />
                    </x-slot:actions>
                @endif
            </x-ceplok::card>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Terang'],
                    ['value' => true, 'label' => 'Gelap'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Lebar kolom">
                <x-docs.segmented label="Pilih lebar kolom" model="view" :value="$view" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'desktop', 'label' => 'Desktop'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Gambar">
                <x-docs.segmented label="Tampilkan gambar" model="image" :value="$image" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Aksi">
                <x-docs.segmented
                    label="Pilih jenis aksi"
                    model="action"
                    :value="$action"
                    item-class="px-2.5"
                    wrap
                    :options="collect($this::ACTIONS)->map(fn ($label, $value) => ['value' => $value, 'label' => $label])->values()->all()"
                />
            </x-docs.control>

        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Mengikuti kontrol di Playground — nama prop yang sedang dikendalikan
            ditandai dengan warna biru. Tombol <em>Salin</em> selalu menyalin
            persis yang sedang tampil.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
