<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Drawer: status buka/tutup diikat ke
 * $buka lewat wire:model.live, dan wire:close menangkap event penutupan.
 */
new class extends Component
{
    public bool $buka = false;

    public int $ditutup = 0;

    public string $pesan = 'Belum ada menu yang dipilih';

    public function closed(): void
    {
        $this->ditutup++;
    }

    public function pilihMenu(string $nama): void
    {
        $this->pesan = 'Menu terpilih: '.$nama;
        $this->buka = false;
    }
};
?>

<div class="space-y-4">
    <div class="flex flex-wrap items-center gap-3">
        <x-ceplok::button size="s" wire:click="$set('buka', true)">
            Buka Drawer (Livewire)
        </x-ceplok::button>
        <span class="text-sm text-gray-600">
            <code class="text-xs font-bold text-gray-700">$buka</code> = {{ $buka ? 'true' : 'false' }}
            @if ($ditutup > 0)
                · ditutup {{ $ditutup }}×
            @endif
        </span>
    </div>

    <p class="text-sm font-medium text-gray-700">
        {{ $pesan }}
    </p>

    <x-ceplok::drawer
        wire:model.live="buka"
        wire:close="closed"
        position="right"
        size="s"
        eyebrow="MENU UTAMA"
        :items="[
            ['label' => 'Beranda', 'icon' => 'ceplok-s-chart-pie', 'attributes' => ['wire:click' => 'pilihMenu(\'Beranda\')']],
            ['label' => 'Laporan', 'icon' => 'ceplok-s-file-lines', 'attributes' => ['wire:click' => 'pilihMenu(\'Laporan\')']],
            ['label' => 'Pesan Masuk', 'icon' => 'ceplok-s-inbox', 'badge' => '3', 'attributes' => ['wire:click' => 'pilihMenu(\'Pesan Masuk\')']],
        ]"
    />
</div>
