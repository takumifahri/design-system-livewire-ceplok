<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Radio Button: pilihan diikat
 * wire:model.live, dan daftar dokumen yang perlu dilampirkan dirender
 * ulang server mengikuti pilihannya.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya (`:dark="$docsDark"`). */
    public bool $dark = false;

    public string $kewarganegaraan = 'wni';
};
?>

<div class="w-full space-y-4">
    <div class="space-y-3">
        <x-ceplok::radio name="kewarganegaraan" value="wni" label="Warga negara Indonesia" wire:model.live="kewarganegaraan" :dark-mode="$dark" />
        <x-ceplok::radio name="kewarganegaraan" value="wna" label="Warga negara asing" wire:model.live="kewarganegaraan" :dark-mode="$dark" />
    </div>
    <p class="text-body-sm text-gray-500 dark:text-gray-400" role="status">
        Dokumen yang perlu dilampirkan:
        <span class="text-gray-900 dark:text-white">{{ $kewarganegaraan === 'wna' ? 'paspor dan izin tinggal' : 'KTP elektronik' }}</span>.
    </p>
</div>
