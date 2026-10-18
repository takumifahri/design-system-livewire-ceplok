<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Input Field: isian terikat wire:model,
 * dan hasil validasi dipetakan ke state="failed" beserta pesannya.
 */
new class extends Component
{
    public string $nama = '';

    public ?string $tersimpan = null;

    public function simpan(): void
    {
        $this->reset('tersimpan');

        $this->validate(
            ['nama' => 'required|min:3'],
            ['nama.required' => 'Nama lengkap wajib diisi.', 'nama.min' => 'Nama lengkap minimal 3 huruf.'],
        );

        $this->tersimpan = $this->nama;
    }
};
?>

<form wire:submit="simpan" class="w-full max-w-91 space-y-4">
    <x-ceplok::input-field
        label="Nama lengkap"
        placeholder="Masukkan nama lengkap"
        wire:model="nama"
        :state="$errors->has('nama') ? 'failed' : 'default'"
        :helper-text="$errors->first('nama') ?: 'Sesuai yang tertera pada KTP.'"
        clearable
    />
    <div class="flex flex-wrap items-center gap-3">
        <x-ceplok::button type="submit">Simpan</x-ceplok::button>
        @if ($tersimpan !== null)
            <span class="text-body-sm text-gray-500">Tersimpan: {{ $tersimpan }}</span>
        @endif
    </div>
</form>
