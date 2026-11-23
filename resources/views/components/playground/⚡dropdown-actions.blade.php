<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Dropdown: tiap aksi memanggil metode
 * komponen lewat `attributes` item-nya, dan pemicunya Button kit.
 */
new class extends Component
{
    public ?string $terakhir = null;

    /** Mengikuti mode docs halaman, diisi saat mount (lihat App\Docs\Theme). */
    public bool $dark = false;

    public function duplikat(): void
    {
        $this->terakhir = 'Dokumen diduplikat.';
    }

    public function arsipkan(): void
    {
        $this->terakhir = 'Dokumen diarsipkan.';
    }

    public function hapus(): void
    {
        $this->terakhir = 'Dokumen dihapus.';
    }
};
?>

<div class="flex min-h-56 w-full flex-col items-center gap-4 pt-3">
    <x-ceplok::dropdown :dark-mode="$dark" :items="[
        ['label' => 'Duplikat', 'attributes' => ['wire:click' => 'duplikat']],
        ['label' => 'Arsipkan', 'attributes' => ['wire:click' => 'arsipkan']],
        ['label' => 'Hapus', 'tone' => 'danger', 'attributes' => ['wire:click' => 'hapus']],
    ]" :dark-mode="$dark">
         <x-slot:trigger variant="outline" :tone="$dark ? 'dark' : 'light'">Aksi dokumen</x-slot:trigger>
    </x-ceplok::dropdown>
    <p class="text-body-sm text-gray-500 dark:text-gray-400" role="status">{{ $terakhir ?? 'Belum ada aksi.' }}</p>
</div>
