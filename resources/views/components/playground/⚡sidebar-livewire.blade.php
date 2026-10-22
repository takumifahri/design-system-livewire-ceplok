<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Sidebar: menu utama mengendalikan
 * tampilan halaman secara reaktif, dan status collapse terhubung ke properti
 * Livewire.
 */
new class extends Component
{
    public bool $collapsed = false;

    public string $halaman = 'Dashboard';

    public int $jumlahKlik = 0;

    public function toggleCollapse(): void
    {
        $this->collapsed = ! $this->collapsed;
    }

    public function navigasi(string $nama): void
    {
        $this->halaman = $nama;
        $this->jumlahKlik++;
    }
};
?>

<div class="space-y-4">
    <div class="flex flex-wrap items-center gap-3">
        <x-ceplok::button size="s" wire:click="toggleCollapse">
            {{ $collapsed ? 'Buka Sidebar' : 'Tutup Sidebar' }}
        </x-ceplok::button>
        <span class="text-sm text-gray-600 dark:text-gray-400">
            <code class="text-xs font-bold text-gray-700 dark:text-gray-300">$collapsed</code> = {{ $collapsed ? 'true' : 'false' }}
            · Halaman aktif: <strong class="text-primary-600 dark:text-primary-400">{{ $halaman }}</strong>
            · Diklik {{ $jumlahKlik }}×
        </span>
    </div>

    <div class="flex h-[360px] overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
        <x-ceplok::sidebar
            class="!min-h-0 h-full shrink-0"
            :collapsed="$collapsed"
            show-collapse-button
            wire:collapse="toggleCollapse"
            :items="[
                ['label' => 'Dashboard', 'icon' => 'ceplok-s-chart-pie', 'active' => $halaman === 'Dashboard', 'href' => '#', 'attributes' => ['wire:click.prevent' => 'navigasi(\'Dashboard\')']],
                ['label' => 'Pesanan', 'icon' => 'ceplok-s-cart', 'active' => $halaman === 'Pesanan', 'badge' => 3, 'href' => '#', 'attributes' => ['wire:click.prevent' => 'navigasi(\'Pesanan\')']],
                ['label' => 'Kotak Masuk', 'icon' => 'ceplok-s-inbox', 'active' => $halaman === 'Kotak Masuk', 'href' => '#', 'attributes' => ['wire:click.prevent' => 'navigasi(\'Kotak Masuk\')']],
            ]"
            :user="[
                'name' => 'Admin Livewire',
                'profileLabel' => 'Online',
            ]"
        >
            <x-slot:logo>
                <span class="font-bold text-sm tracking-tight">APP LIVEWIRE</span>
            </x-slot:logo>
            <x-slot:collapsed-logo>
                <span class="font-bold text-sm">AL</span>
            </x-slot:collapsed-logo>
        </x-ceplok::sidebar>

        <div class="flex-1 p-6 overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konten {{ $halaman }}</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Komponen Livewire memproses navigasi menu tanpa memuat ulang halaman penuh.
                Sidebar diperbarui secara instan lewat re-render Livewire.
            </p>
        </div>
    </div>
</div>
