<?php

use Livewire\Component;

/*
 * Canvas integrasi Livewire untuk Drawer. Root Drawer memakai <dialog> dan
 * berada di viewport, maka canvas ini memakai subkomponennya agar perilaku
 * state tetap dapat dicoba tanpa menutupi halaman dokumentasi.
 */
new class extends Component
{
    public bool $buka = false;

    public string $menuTerpilih = 'Belum ada menu yang dipilih';

    public function pilihMenu(string $menu): void
    {
        $this->menuTerpilih = $menu;
        $this->buka = false;
    }
};
?>

<div class="space-y-4">
    <x-docs.stage max-width="max-w-[520px]">
        <div class="flex h-[520px] w-full items-center justify-center py-4">
            <div class="relative flex h-full w-[380px] max-w-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md">
                <div class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 py-3.5">
                    <div class="flex items-center gap-2.5">
                        <div class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">C</div>
                        <span class="text-base font-bold tracking-tight text-gray-900">CEPLOK</span>
                    </div>
                    <x-ceplok::drawer.trigger wire:click="$set('buka', true)" />
                </div>

                <div class="flex-1 bg-gray-50/50 p-6">
                    <h4 class="mb-2 text-lg font-bold tracking-tight text-gray-900">{{ $menuTerpilih }}</h4>
                    <p class="text-xs leading-5 text-gray-500">Klik tombol hamburger untuk membuka Drawer, lalu pilih menu untuk memperbarui state Livewire.</p>
                </div>

                @if ($buka)
                    <div class="absolute inset-0 z-10 bg-gray-900/40" wire:click="$set('buka', false)"></div>
                    <div class="absolute inset-y-0 right-0 z-20 flex w-[260px] flex-col border-l border-gray-200 bg-white shadow-2xl">
                        <x-ceplok::drawer.header eyebrow="MENU" wire:close="$set('buka', false)" />
                        <x-ceplok::drawer.body class="p-3 space-y-1">
                            <x-ceplok::drawer.nav-item
                                icon="ceplok-s-chart-pie"
                                label="Beranda"
                                :active="$menuTerpilih === 'Beranda'"
                                wire:click="pilihMenu('Beranda')"
                            />
                            <x-ceplok::drawer.nav-item
                                icon="ceplok-s-file-lines"
                                label="Laporan"
                                :active="$menuTerpilih === 'Laporan'"
                                wire:click="pilihMenu('Laporan')"
                            />
                            <x-ceplok::drawer.nav-item
                                icon="ceplok-s-inbox"
                                label="Pesan Masuk"
                                :active="$menuTerpilih === 'Pesan Masuk'"
                                wire:click="pilihMenu('Pesan Masuk')"
                            />
                        </x-ceplok::drawer.body>
                    </div>
                @endif
            </div>
        </div>
    </x-docs.stage>

    <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-gray-600">
        <span>State <code class="text-xs font-bold text-gray-700">$buka</code> = <strong class="text-gray-900">{{ $buka ? 'true' : 'false' }}</strong></span>
        <span>Menu terpilih: <strong class="text-gray-900">{{ $menuTerpilih }}</strong></span>
    </div>
</div>
