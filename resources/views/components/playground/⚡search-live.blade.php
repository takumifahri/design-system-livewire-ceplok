<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Search Form: dengan trigger="auto",
 * event `search` dikirim setiap isian berubah (setelah jeda debounce), dan
 * daftar layanannya disaring server.
 */
new class extends Component
{
    public string $kata = '';

    /** @var list<string> */
    private const LAYANAN = [
        'Izin Usaha Mikro',
        'Izin Mendirikan Bangunan',
        'Kartu Tanda Penduduk',
        'Kartu Keluarga',
        'Akta Kelahiran',
        'Paspor',
        'Surat Izin Mengemudi',
        'Nomor Pokok Wajib Pajak',
    ];

    public function cari(string $kata): void
    {
        $this->kata = trim($kata);
    }

    /** @return list<string> */
    #[Computed]
    public function hasil(): array
    {
        if ($this->kata === '') {
            return self::LAYANAN;
        }

        return array_values(array_filter(self::LAYANAN, fn (string $nama) => str_contains(mb_strtolower($nama), mb_strtolower($this->kata))));
    }
};
?>

<div class="w-full max-w-140 space-y-4">
    <x-ceplok::search
        aria-label="Cari layanan"
        placeholder="Cari layanan…"
        trigger="auto"
        :debounce="250"
        wire:search="cari($event.detail.value)"
    />
    <p class="text-body-sm text-gray-500" role="status">
        {{ count($this->hasil) }} layanan{{ $kata !== '' ? ' untuk “'.$kata.'”' : '' }}
    </p>
    <ul class="divide-y divide-border rounded-lg border border-border bg-surface text-body-sm text-gray-700">
        @forelse ($this->hasil as $nama)
            <li wire:key="layanan-{{ $loop->index }}-{{ $nama }}" class="px-4 py-2.5">{{ $nama }}</li>
        @empty
            <li class="px-4 py-2.5 text-gray-500">Tidak ada layanan yang cocok.</li>
        @endforelse
    </ul>
</div>
