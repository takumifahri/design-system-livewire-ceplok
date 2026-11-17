<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Checkbox: tiga checkbox sekelompok
 * diikat ke satu properti array lewat wire:model.live.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya (`:dark="$docsDark"`). */
    public bool $dark = false;

    /** @var list<string> */
    public array $berkas = ['ktp'];

    private const NAMA = ['ktp' => 'KTP elektronik', 'kk' => 'Kartu keluarga', 'npwp' => 'NPWP'];

    /** Nama berkas yang dicentang, urut seperti daftarnya. */
    #[Computed]
    public function terpilih(): string
    {
        $nama = array_values(array_intersect_key(self::NAMA, array_flip($this->berkas)));

        return $nama === [] ? 'belum ada' : implode(', ', $nama);
    }
};
?>

<div class="w-full space-y-4">
    <div class="space-y-3">
        <x-ceplok::checkbox value="ktp" label="KTP elektronik" wire:model.live="berkas" :dark-mode="$dark" />
        <x-ceplok::checkbox value="kk" label="Kartu keluarga" wire:model.live="berkas" :dark-mode="$dark" />
        <x-ceplok::checkbox value="npwp" label="NPWP" wire:model.live="berkas" :dark-mode="$dark" />
    </div>
    <p class="text-body-sm text-gray-500 dark:text-gray-400" role="status">
        Berkas yang akan dilampirkan: <span class="text-gray-900 dark:text-white">{{ $this->terpilih }}</span>.
    </p>
</div>
