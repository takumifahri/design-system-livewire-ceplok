<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Floating Label: nilai yang diisi dari
 * server lewat $set membuat labelnya naik sendiri.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya (`:dark="$docsDark"`). */
    public bool $dark = false;

    public string $kota = '';
};
?>

<div class="flex w-full max-w-91 flex-col items-start gap-4">
    <x-ceplok::floating-label label="Kota domisili" wire:model="kota" clearable :dark-mode="$dark" />
    <x-ceplok::button wire:click="$set('kota', 'Bandung')">Isi dari profil</x-ceplok::button>
</div>
