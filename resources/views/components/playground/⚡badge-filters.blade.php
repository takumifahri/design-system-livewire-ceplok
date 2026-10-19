<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Badge: tiap filter terikat ke satu
 * properti lewat wire:model.live, dan wire:dismiss mencatat filter terakhir
 * yang dihapus.
 */
new class extends Component
{
    /** @var array<string, bool> */
    public array $filters = ['aktif' => true, 'jakarta' => true, 'tahun' => true];

    public ?string $last = null;

    public function removed(string $filter): void
    {
        $this->last = $filter;
    }

    public function restore(): void
    {
        $this->filters = array_map(fn () => true, $this->filters);
        $this->last = null;
    }
};
?>

<div class="flex flex-col items-start gap-4">
    <div class="flex flex-wrap items-center gap-3">
        <x-ceplok::badge variant="brand" dismissible wire:model.live="filters.aktif" wire:dismiss="removed('Status')">Status: Aktif</x-ceplok::badge>
        <x-ceplok::badge variant="brand" dismissible wire:model.live="filters.jakarta" wire:dismiss="removed('Lokasi')">Lokasi: Jakarta</x-ceplok::badge>
        <x-ceplok::badge variant="brand" dismissible wire:model.live="filters.tahun" wire:dismiss="removed('Tahun')">Tahun: 2026</x-ceplok::badge>
    </div>
    <p class="text-sm text-gray-600">
        Filter aktif: <strong class="text-gray-900">{{ count(array_filter($filters)) }}</strong>
        @if ($last)
            · terakhir dihapus: {{ $last }}
        @endif
    </p>
    <x-ceplok::button size="s" variant="outline" theme="gray" wire:click="restore">Pulihkan filter</x-ceplok::button>
</div>
