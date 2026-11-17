<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Alert: tampil/sembunyinya diikat ke
 * $notice lewat wire:model.live, dan wire:dismiss menghitung penutupan.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya (`:dark="$docsDark"`). */
    public bool $dark = false;

    public bool $notice = true;

    /** Jumlah penutupan. Namanya sengaja beda dari metode `dismissed`: $wire mendahulukan properti. */
    public int $closed = 0;

    public function dismissed(): void
    {
        $this->closed++;
    }
};
?>

<div class="space-y-4">
    <x-ceplok::alert variant="info" heading="Pemeliharaan terjadwal" wire:model.live="notice" wire:dismiss="dismissed" :dark-mode="$dark">
        Layanan tidak dapat diakses pada Sabtu pukul 22.00–24.00 WIB.
    </x-ceplok::alert>
    <div class="flex flex-wrap items-center gap-3">
        <x-ceplok::button size="s" variant="outline" theme="gray" wire:click="$set('notice', true)" :disabled="$notice">
            Tampilkan lagi
        </x-ceplok::button>
        <span class="text-sm text-gray-600 dark:text-gray-300">
            <code class="text-xs font-bold text-gray-700 dark:text-gray-300">$notice</code> = {{ $notice ? 'true' : 'false' }} · ditutup {{ $closed }}×
        </span>
    </div>
</div>
