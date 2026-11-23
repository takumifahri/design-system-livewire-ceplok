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
    <x-docs.stage max-width="max-w-[520px]" min-width="520px">
        <div class="flex h-[520px] w-full items-center justify-center py-4">
            <div class="relative flex h-full w-[380px] max-w-full flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md dark:border-gray-700 dark:bg-gray-800">
                <div class="flex shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 py-3.5 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-2.5">
                        <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-7 shrink-0 object-contain">
                        <span class="text-base font-bold tracking-tight text-gray-900 dark:text-white">CEPLOK</span>
                    </div>
                    <button type="button" aria-label="Buka menu drawer" wire:click="$set('buka', true)" class="inline-flex shrink-0 items-center justify-center rounded-lg p-1.5 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <x-ceplok-o-bars-from-left class="size-6" aria-hidden="true" />
                    </button>
                </div>

                <div class="flex-1 bg-gray-50/50 p-6 dark:bg-gray-800">
                    <h4 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">{{ $menuTerpilih }}</h4>
                    <p class="text-xs leading-5 text-gray-500 dark:text-gray-400">Klik tombol hamburger untuk membuka Drawer, lalu pilih menu untuk memperbarui state Livewire.</p>
                </div>

                @if ($buka)
                    <div class="absolute inset-0 z-10 bg-gray-900/40" wire:click="$set('buka', false)"></div>
                    <div class="absolute inset-y-0 right-0 z-20 flex w-[260px] flex-col border-l border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex shrink-0 items-center justify-between gap-4 px-5 pt-4 pb-2"><span class="text-base font-semibold tracking-wider uppercase text-gray-500 dark:text-gray-400">MENU</span><button type="button" aria-label="Tutup drawer" wire:click="$set('buka', false)" class="inline-flex size-8 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"><x-ceplok-o-close class="size-[18px]" /></button></div>
                        <div class="min-h-0 flex-1 space-y-1 overflow-y-auto p-3 text-xs text-gray-600">
                            @foreach (['Beranda' => 'ceplok-s-chart-pie', 'Laporan' => 'ceplok-s-file-lines', 'Pesan Masuk' => 'ceplok-s-inbox'] as $label => $icon)
                                <button type="button" wire:click="pilihMenu('{{ $label }}')" @class(['group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-base font-medium transition-all', $menuTerpilih === $label ? 'bg-blue-50 text-blue-600 dark:bg-gray-700 dark:text-white' : 'text-gray-900 hover:bg-blue-50 hover:text-blue-600 dark:text-gray-50 dark:hover:bg-gray-700 dark:hover:text-white'])><span class="flex size-[18px] shrink-0 items-center justify-center">{!! \CeplokUi\DesignKit\Support\Content::icon($icon) !!}</span>{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-docs.stage>

    <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-gray-600 dark:text-gray-300">
        <span>State <code class="text-xs font-bold text-gray-700 dark:text-gray-200">$buka</code> = <strong class="text-gray-900 dark:text-white">{{ $buka ? 'true' : 'false' }}</strong></span>
        <span>Menu terpilih: <strong class="text-gray-900 dark:text-white">{{ $menuTerpilih }}</strong></span>
    </div>
</div>
