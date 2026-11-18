<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Pagination: daftar pengajuan dibagi
 * per lima baris. Halamannya diikat wire:model.live dan dicatat di alamat
 * lewat #[Url], jadi bisa dibagikan dan bertahan saat dimuat ulang.
 */
new class extends Component
{
    public const TOTAL = 42;

    public const PER_PAGE = 5;

    #[Url(as: 'halaman')]
    public int $page = 1;

    public bool $dark = false;

    public function totalPages(): int
    {
        return (int) ceil(self::TOTAL / self::PER_PAGE);
    }

    public function updatedPage(): void
    {
        $this->page = max(1, min($this->page, $this->totalPages()));
    }

    /** @return list<array{nomor: string, status: string, variant: string}> */
    #[Computed]
    public function rows(): array
    {
        $statuses = [['Menunggu', 'warning'], ['Disetujui', 'success'], ['Ditolak', 'danger']];
        $page = max(1, min($this->page, $this->totalPages()));
        $first = ($page - 1) * self::PER_PAGE + 1;
        $last = min($first + self::PER_PAGE - 1, self::TOTAL);

        return array_map(function (int $n) use ($statuses) {
            [$status, $variant] = $statuses[($n * 7) % 3];

            return ['nomor' => sprintf('Pengajuan #%03d', $n), 'status' => $status, 'variant' => $variant];
        }, range($first, $last));
    }
};
?>

<div class="space-y-4">
    <ul @class(['divide-y overflow-hidden rounded-lg border', $dark ? 'divide-gray-700 border-gray-700 bg-gray-800' : 'divide-border border-border bg-white'])>
        @foreach ($this->rows as $row)
            <li wire:key="{{ $row['nomor'] }}" @class(['flex items-center justify-between gap-4 px-4 py-2.5 text-sm', $dark ? 'text-gray-100' : 'text-gray-900'])>
                {{ $row['nomor'] }}
                <x-ceplok::badge :variant="$row['variant']" :dark-mode="$dark">{{ $row['status'] }}</x-ceplok::badge>
            </li>
        @endforeach
    </ul>
    <div class="flex flex-wrap items-center justify-between gap-4">
        <span @class(['text-sm', $dark ? 'text-gray-400' : 'text-gray-500'])>Halaman {{ $page }} dari {{ $this->totalPages() }} · {{ $this::TOTAL }} pengajuan</span>
        <x-ceplok::pagination :current-page="$page" :total-pages="$this->totalPages()" wire:model.live="page" size="s" :dark-mode="$dark" />
    </div>
</div>
