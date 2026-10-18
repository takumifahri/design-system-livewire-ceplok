<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Spinner: Spinner hanya tampil selama
 * aksi `load` berjalan, lewat wire:loading.
 */
new class extends Component
{
    public int $loaded = 0;

    public function load(): void
    {
        // Pura-pura memuat data dari server supaya Spinner-nya sempat terlihat.
        usleep(1_200_000);

        $this->loaded++;
    }
};
?>

<div class="flex min-h-10 flex-wrap items-center gap-4">
    <x-ceplok::button wire:click="load" wire:loading.attr="disabled" wire:target="load">Muat data</x-ceplok::button>
    <div wire:loading.flex wire:target="load" class="items-center gap-3">
        <x-ceplok::spinner aria-hidden="true" />
        <span class="text-body-sm text-gray-700">Memuat data…</span>
    </div>
    <span wire:loading.remove wire:target="load" class="text-body-sm text-gray-500">
        {{ $loaded ? "Data dimuat {$loaded}×." : 'Belum ada data.' }}
    </span>
</div>
