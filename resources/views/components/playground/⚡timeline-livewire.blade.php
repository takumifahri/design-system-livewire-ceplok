<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Contoh Livewire halaman Timeline View: tombol tutup Badge status memicu
 * event `dismiss`, `wire:dismiss` memanggil tutupStatus(), dan Timeline
 * dirender ulang tanpa status itu.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya (`:dark="$docsDark"`). */
    public bool $dark = false;

    /** @var list<int> Indeks langkah yang statusnya sudah ditutup. */
    public array $tertutup = [];

    public function tutupStatus(int $index): void
    {
        if (! in_array($index, $this->tertutup, true)) {
            $this->tertutup[] = $index;
        }
    }

    public function pulihkan(): void
    {
        $this->tertutup = [];
    }

    /** @return list<array<string, mixed>> */
    #[Computed]
    public function langkah(): array
    {
        $langkah = [
            ['name' => 'Rina Wulandari', 'role' => 'Pemohon', 'initials' => 'RW', 'time' => '2026-10-12 08:30', 'description' => 'Mengajukan permohonan izin usaha mikro.', 'status' => ['label' => 'Diajukan', 'icon' => 'ceplok-s-clock']],
            ['name' => 'Budi Santoso', 'role' => 'Verifikator / Dinas PMPTSP', 'initials' => 'BS', 'time' => '2026-10-12 10:05', 'description' => 'Memeriksa kelengkapan berkas permohonan.', 'status' => ['label' => 'Terverifikasi', 'icon' => 'ceplok-s-check-circle']],
            ['name' => 'Sari Dewi', 'role' => 'Kepala Seksi', 'initials' => 'SD', 'time' => '2026-10-12 13:20', 'description' => 'Menyetujui permohonan.', 'status' => ['label' => 'Disetujui', 'icon' => 'ceplok-s-check-circle']],
        ];

        return array_map(function (array $item, int $index) {
            if (in_array($index, $this->tertutup, true)) {
                unset($item['status']);

                return $item;
            }

            $item['status'] += ['dismissible' => true, 'attributes' => ['wire:dismiss' => "tutupStatus({$index})"]];

            return $item;
        }, $langkah, array_keys($langkah));
    }
};
?>

<div class="w-full space-y-4">
    <div class="max-w-102">
        <x-ceplok::timeline platform="mobile" :items="$this->langkah" :labels="['months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']]" :dark-mode="$dark" />
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <span class="text-body-sm text-gray-500 dark:text-gray-400">{{ count($tertutup) }} dari 3 status ditutup.</span>
        <x-ceplok::button size="s" variant="outline" wire:click="pulihkan" :disabled="$tertutup === []">Tampilkan lagi</x-ceplok::button>
    </div>
</div>
