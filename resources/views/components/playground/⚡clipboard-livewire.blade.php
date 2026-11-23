<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Clipboard: event hasil salin diteruskan
 * ke method server tanpa mengubah status internal Clipboard.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya. */
    public bool $dark = false;

    public string $message = 'Belum ada aktivitas salin.';

    public function copied(string $value): void
    {
        $this->message = "Berhasil disalin: {$value}";
    }

    public function failed(): void
    {
        $this->message = 'Penyalinan gagal.';
    }
};
?>

<div class="space-y-3">
    <div class="max-w-[452px]">
        <x-ceplok::clipboard
            value="INV-2026-001"
            label="Nomor referensi"
            variant="segmented"
            prefix="ID"
            wire:copy-success="copied($event.detail.value)"
            wire:copy-error="failed"
            :dark-mode="$dark"
        />
    </div>
    <p class="text-body-sm text-content-subtle" role="status">{{ $message }}</p>
</div>
