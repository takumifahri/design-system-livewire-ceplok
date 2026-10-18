<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Toast: aksi server `save` memunculkan
 * Toast dengan mengisi $saved = true. Toast-nya terikat wire:model, jadi
 * menutupnya mengembalikan $saved ke false tanpa request tambahan.
 */
new class extends Component
{
    public bool $saved = false;

    public int $count = 0;

    public function save(): void
    {
        $this->count++;
        $this->saved = true;
    }
};
?>

<div class="flex flex-col items-start gap-4">
    <x-ceplok::button wire:click="save">Simpan data</x-ceplok::button>
    <x-ceplok::toast variant="success" wire:model="saved">Data #{{ $count }} berhasil disimpan!</x-ceplok::toast>
</div>
