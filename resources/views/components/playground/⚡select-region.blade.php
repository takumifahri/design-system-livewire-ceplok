<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Regular Select Form: daftar
 * kabupaten/kota mengikuti provinsi yang dipilih. Opsi Select kedua diganti
 * server setiap provinsi berganti, dan pilihannya dikosongkan.
 */
new class extends Component
{
    public string $provinsi = '';

    public string $kota = '';

    /** @var array<string, string> */
    public array $daftarProvinsi = [
        'jabar' => 'Jawa Barat',
        'jateng' => 'Jawa Tengah',
        'jatim' => 'Jawa Timur',
    ];

    /** @return array<string, string> */
    #[Computed]
    public function daftarKota(): array
    {
        return match ($this->provinsi) {
            'jabar' => ['bandung' => 'Kota Bandung', 'bogor' => 'Kota Bogor', 'cirebon' => 'Kota Cirebon'],
            'jateng' => ['semarang' => 'Kota Semarang', 'surakarta' => 'Kota Surakarta', 'tegal' => 'Kota Tegal'],
            'jatim' => ['surabaya' => 'Kota Surabaya', 'malang' => 'Kota Malang', 'kediri' => 'Kota Kediri'],
            default => [],
        };
    }

    public function updatedProvinsi(): void
    {
        $this->reset('kota');
    }
};
?>

<div class="grid w-full gap-5 sm:grid-cols-2">
    <x-ceplok::select
        label="Provinsi"
        placeholder="Pilih provinsi"
        wire:model.live="provinsi"
        :options="$daftarProvinsi"
    />
    <x-ceplok::select
        label="Kabupaten/Kota"
        placeholder="Pilih kabupaten/kota"
        wire:model="kota"
        :options="$this->daftarKota"
        :state="$provinsi ? 'default' : 'inactive'"
    />
</div>
