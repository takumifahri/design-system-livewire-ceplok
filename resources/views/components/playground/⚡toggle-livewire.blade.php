<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Toggle Button: sakelar diikat
 * wire:model.live, jadi setiap ketukan langsung sampai ke server.
 */
new class extends Component
{
    public bool $notifikasi = true;

    public int $diubah = 0;

    public function updatedNotifikasi(): void
    {
        $this->diubah++;
    }
};
?>

<div class="w-full space-y-3">
    <x-ceplok::toggle
        label="Notifikasi email"
        helper-text="Kirim ringkasan permohonan baru setiap pagi."
        wire:model.live="notifikasi"
    />
    <p class="text-body-sm text-gray-500" role="status">
        Server mencatat notifikasi <span class="text-gray-900">{{ $notifikasi ? 'menyala' : 'mati' }}</span>
        @if ($diubah)
            · diubah {{ $diubah }}×
        @endif
    </p>
</div>
