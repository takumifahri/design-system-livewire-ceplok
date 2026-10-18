<?php

use CeplokUi\DesignKit\Support\Dates;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Datepicker: periode diikat
 * wire:model.live, dan daftar pengajuan disaring server setiap periodenya
 * berganti. Tanggal contohnya mengikuti hari ini, supaya kalender yang
 * terbuka di bulan berjalan langsung berisi data.
 */
new class extends Component
{
    /** @var array{start: ?string, end: ?string}|null */
    public ?array $periode = null;

    /** @return list<array{nama: string, tanggal: string}> */
    #[Computed]
    public function pengajuan(): array
    {
        $daftar = [
            [0, 'Izin usaha mikro'],
            [2, 'Perpanjangan paspor'],
            [5, 'Akta kelahiran'],
            [9, 'Kartu keluarga'],
            [14, 'Surat izin mengemudi'],
            [20, 'Nomor pokok wajib pajak'],
            [27, 'Izin mendirikan bangunan'],
            [33, 'Kartu tanda penduduk'],
        ];

        return array_map(fn (array $baris) => ['nama' => $baris[1], 'tanggal' => today()->subDays($baris[0])->toDateString()], $daftar);
    }

    /** @return list<array{nama: string, tanggal: string}> */
    #[Computed]
    public function hasil(): array
    {
        $mulai = $this->periode['start'] ?? null;
        $selesai = $this->periode['end'] ?? $mulai;

        return array_values(array_filter(
            $this->pengajuan,
            fn (array $baris) => ($mulai === null || $baris['tanggal'] >= $mulai) && ($selesai === null || $baris['tanggal'] <= $selesai),
        ));
    }

    #[Computed]
    public function keterangan(): string
    {
        $mulai = $this->periode['start'] ?? null;
        $selesai = $this->periode['end'] ?? null;

        return match (true) {
            $this->periode === null => 'semua tanggal',
            $mulai === null && $selesai === null => 'Semua Waktu',
            $selesai === null => 'mulai '.Dates::write($mulai),
            default => Dates::writeRange($mulai, $selesai),
        };
    }
};
?>

<div class="w-full max-w-140 space-y-4">
    <x-ceplok::datepicker type="period" label="Tanggal pengajuan" wire:model.live="periode" />
    <p class="text-body-sm text-gray-500" role="status">{{ count($this->hasil) }} pengajuan · {{ $this->keterangan }}</p>
    <ul class="divide-y divide-border rounded-lg border border-border bg-surface text-body-sm text-gray-700">
        @forelse ($this->hasil as $baris)
            <li wire:key="pengajuan-{{ $baris['tanggal'] }}-{{ $loop->index }}" class="flex items-center justify-between gap-4 px-4 py-2.5">
                <span>{{ $baris['nama'] }}</span>
                <span class="text-gray-500">{{ Dates::write($baris['tanggal']) }}</span>
            </li>
        @empty
            <li class="px-4 py-2.5 text-gray-500">Tidak ada pengajuan pada periode ini.</li>
        @endforelse
    </ul>
</div>
