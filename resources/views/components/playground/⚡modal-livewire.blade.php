<?php

use Livewire\Component;

/*
 * Contoh Modal terkontrol di halaman Modal, padanan ControlledModalExample di
 * docs React (`open` + `onOpenChange`). Visibilitasnya properti `$open` yang
 * diikat wire:model: tombol di luar Modal membukanya, dan `simpan()` di server
 * menutupnya setelah prosesnya selesai.
 */
new class extends Component
{
    public bool $dark = false;

    public bool $open = false;

    public string $nama = 'Andi Saputra';

    public int $tersimpan = 0;

    public function simpan(): void
    {
        $this->tersimpan++;
        $this->open = false;
    }
};
?>

<div class="mt-4 space-y-3">
    <div class="flex flex-wrap items-center gap-3">
        <x-ceplok::button :tone="$dark ? 'dark' : 'light'" wire:click="$set('open', true)">Edit data</x-ceplok::button>
        @if ($tersimpan > 0)
            <span class="text-body-sm text-gray-500">Tersimpan {{ $tersimpan }}× — nama: {{ $nama }}</span>
        @endif
    </div>

    <x-ceplok::modal wire:model="open" title="Edit data" :dark-mode="$dark">
        <x-ceplok::input-field label="Nama" wire:model="nama" />

        <x-slot:footer>
            <div class="flex w-full flex-wrap justify-end gap-3">
                <x-ceplok::button variant="outline" theme="gray" size="xs" x-on:click="close()">Batal</x-ceplok::button>
                <x-ceplok::button size="xs" wire:click="simpan">Simpan</x-ceplok::button>
            </div>
        </x-slot:footer>
    </x-ceplok::modal>
</div>
