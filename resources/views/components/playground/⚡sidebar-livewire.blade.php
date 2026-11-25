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

    /** Mengikuti mode docs halaman, diisi saat mount (lihat App\Docs\Theme). */
    public bool $dark = false;

    public function navigasi(string $nama): void
    {
        $this->halaman = $nama;
        $this->jumlahKlik++;
    }
};
?>

<div class="flex flex-col gap-4">
    <div class="order-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-gray-600 dark:text-gray-300">
        <span>
            <code class="text-xs font-bold text-gray-700 dark:text-gray-300">$collapsed</code> = {{ $collapsed ? 'true' : 'false' }}
            · Halaman aktif: <strong class="text-primary-600 dark:text-primary-400">{{ $halaman }}</strong>
            · Diklik {{ $jumlahKlik }}×
        </span>
    </div>

    <x-docs.stage max-width="max-w-[768px]" min-width="768px" :dark="$dark">
        <div class="flex h-[520px] w-full items-center justify-center py-4">
            <div
        class="flex h-full w-full overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800"
            >
        <x-ceplok::sidebar
            class="!min-h-0 h-full shrink-0"
            style="transition: width 200ms ease-out"
            :collapsed="$collapsed"
            :dark-mode="$dark"
            x-on:collapse="$wire.set('collapsed', $event.detail.collapsed)"
            :items="[
                ['label' => 'Dashboard', 'icon' => 'ceplok-s-chart-pie', 'active' => $halaman === 'Dashboard', 'attributes' => ['wire:click' => 'navigasi(\'Dashboard\')']],
                ['label' => 'Pesanan', 'icon' => 'ceplok-s-cart', 'active' => $halaman === 'Pesanan', 'attributes' => ['wire:click' => 'navigasi(\'Pesanan\')']],
                ['label' => 'Kotak Masuk', 'icon' => 'ceplok-s-inbox', 'active' => $halaman === 'Kotak Masuk', 'attributes' => ['wire:click' => 'navigasi(\'Kotak Masuk\')']],
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

        <div class="flex-1 overflow-y-auto p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konten {{ $halaman }}</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                Komponen Livewire memproses navigasi menu tanpa memuat ulang halaman penuh.
                Sidebar diperbarui secara instan lewat re-render Livewire.
            </p>
        </div>
            </div>
        </div>
    </x-docs.stage>
</div>
