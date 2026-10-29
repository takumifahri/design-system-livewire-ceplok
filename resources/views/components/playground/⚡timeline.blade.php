<?php

use CeplokUi\DesignKit\Enums\TimelinePlatform;
use CeplokUi\DesignKit\Enums\TimelineSize;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground dan Penggunaan halaman Timeline View: platform, ukuran, mode,
 * jumlah item, dan cara lampiran dibuka. Kode Penggunaan menulis isi lengkap
 * tiap item yang tampil, dirakit dari data yang sama dengan preview-nya.
 */
new class extends Component
{
    public TimelinePlatform $platform = TimelinePlatform::Desktop;

    public TimelineSize $size = TimelineSize::Large;

    public bool $dark = false;

    /** Jumlah item: 1–4. */
    public int $count = 4;

    /** Cara lampiran dibuka: `teks`, `tab-baru`, atau `unduh`. */
    public string $lampiran = 'tab-baru';

    /**
     * Isi contoh dari desain: item pertama ringkas, sisanya lengkap. Lampiran
     * tetap teks seperti desain, atau menautkan PDF dummy yang dibuka di tab
     * baru atau diunduh dengan nama yang tampil.
     *
     * @return list<array<string, mixed>>
     */
    #[Computed]
    public function items(): array
    {
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
        $status = ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true];

        $items = array_slice([
            ['name' => 'User 01', 'role' => 'Role / Department', 'time' => '2026-01-01 09:00', 'description' => $lorem],
            ...array_map(fn (int $n) => [
                'name' => "User 0{$n}",
                'role' => 'Role / Department',
                'time' => '2026-01-01 09:'.str_pad((string) (($n - 1) * 5), 2, '0', STR_PAD_LEFT),
                'elapsed' => '5 minutes',
                'description' => $lorem,
                'details' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'attachments' => [['name' => "sample-document-0{$n}.pdf"], ['name' => 'sample-document-0'.($n - 1).'.pdf']],
                'status' => $status,
            ], [2, 3, 4]),
        ], 0, max(1, min(4, $this->count)));

        $link = match ($this->lampiran) {
            'tab-baru' => fn (array $file) => [...$file, 'href' => asset('files/sample-document.pdf'), 'target' => '_blank'],
            'unduh' => fn (array $file) => [...$file, 'href' => asset('files/sample-document.pdf'), 'download' => $file['name']],
            default => null,
        };

        if ($link === null) {
            return $items;
        }

        return array_map(
            fn (array $item) => isset($item['attachments']) ? [...$item, 'attachments' => array_map($link, $item['attachments'])] : $item,
            $items,
        );
    }

    /** Lebar artboard mobile di desain (440px dan 360px) dikurangi margin 16px. */
    #[Computed]
    public function stageWidth(): string
    {
        if ($this->platform === TimelinePlatform::Desktop) {
            return 'max-w-full';
        }

        return $this->size === TimelineSize::Large ? 'max-w-102' : 'max-w-82';
    }

    /**
     * Lebar paling kecil Timeline desktop. Satu rem lebih lebar daripada demo
     * desktop di halaman, karena kontrol Font bisa memilih Inter, font yang
     * paling lebar: dengan Inter, nama dan jabatan di sebelah Badge status baru
     * muat sebaris mulai sekitar 645px. Di layar yang lebih sempit panggungnya
     * digeser ke samping.
     */
    #[Computed]
    public function stageMinWidth(): ?string
    {
        return $this->platform === TimelinePlatform::Desktop ? '41rem' : null;
    }

    /** Isi lengkap item yang tampil; `target` dan `download` disorot karena mengikuti kontrol Lampiran. */
    #[Computed]
    public function usage(): string
    {
        $q = fn (string $value) => "'".addslashes($value)."'";
        $lines = ['@php', '    $items = ['];

        foreach ($this->items as $item) {
            $lines[] = '        [';

            foreach (['name', 'role', 'time', 'elapsed', 'description', 'details'] as $key) {
                if (isset($item[$key])) {
                    $lines[] = "            '{$key}' => ".$q($item[$key]).',';
                }
            }

            if (isset($item['attachments'])) {
                $lines[] = "            'attachments' => [";

                foreach ($item['attachments'] as $file) {
                    $parts = array_filter([
                        "'name' => ".$q($file['name']),
                        isset($file['href']) ? "'href' => asset('files/sample-document.pdf')" : null,
                        isset($file['target']) ? "⟦'target'⟧ => ".$q($file['target']) : null,
                        isset($file['download']) ? "⟦'download'⟧ => ".$q($file['download']) : null,
                    ]);
                    $lines[] = '                ['.implode(', ', $parts).'],';
                }

                $lines[] = '            ],';
            }

            if (isset($item['status'])) {
                $lines[] = "            'status' => ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true],";
            }

            $lines[] = '        ],';
        }

        return implode("\n", array_filter([
            ...$lines,
            '    ];',
            '@endphp',
            '',
            '<x-ceplok::timeline',
            '    :items="$items"',
            $this->platform !== TimelinePlatform::Desktop ? '    ⟦platform⟧="'.$this->platform->value.'"' : null,
            $this->size !== TimelineSize::Large ? '    ⟦size⟧="'.$this->size->value.'"' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '/>',
        ], fn ($line) => $line !== null));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Ubah platform, ukuran, mode, jumlah item, dan cara lampiran dibuka. Lampiran di item kedua
            sampai keempat menautkan PDF dummy. Kode di bagian Penggunaan ikut berubah.
        </x-docs.lead>

        <x-docs.stage :max-width="$this->stageWidth" :min-width="$this->stageMinWidth" :dark="$dark">
            <x-ceplok::timeline :items="$this->items" :platform="$platform" :size="$size" :dark-mode="$dark" />
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'desktop', 'label' => 'Desktop'],
                    ['value' => 'mobile', 'label' => 'Mobile'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Size">
                <x-docs.segmented label="Pilih ukuran" model="size" :value="$size" :options="[
                    ['value' => 'large', 'label' => 'Large'],
                    ['value' => 'small', 'label' => 'Small'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Jumlah item">
                <x-docs.segmented label="Pilih jumlah item" model="count" :value="$count" :options="[
                    ['value' => 1, 'label' => '1'],
                    ['value' => 2, 'label' => '2'],
                    ['value' => 3, 'label' => '3'],
                    ['value' => 4, 'label' => '4'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Lampiran">
                <x-docs.segmented label="Pilih cara lampiran dibuka" model="lampiran" :value="$lampiran" :options="[
                    ['value' => 'teks', 'label' => 'Teks'],
                    ['value' => 'tab-baru', 'label' => 'Tab baru'],
                    ['value' => 'unduh', 'label' => 'Unduh'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground, termasuk isi lengkap tiap item yang tampil. Prop
            Timeline yang masih memakai nilai bawaan sengaja tidak ditulis.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
