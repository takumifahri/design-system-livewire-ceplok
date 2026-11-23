<?php

use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

/*
 * Contoh integrasi Livewire di halaman Upload Form: berkas diunggah lewat
 * wire:model dan divalidasi begitu terunggah. Berkasnya tidak disimpan —
 * contoh ini hanya membaca nama dan ukurannya.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya (`:dark="$docsDark"`). */
    public bool $dark = false;

    use WithFileUploads;

    /** @var TemporaryUploadedFile|null Tanpa tipe, seperti properti unggahan Livewire pada umumnya. */
    public $dokumen = null;

    public function updatedDokumen(): void
    {
        $this->validate(
            ['dokumen' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg'],
            ['dokumen.max' => 'Ukuran berkas maksimal 2 MB.', 'dokumen.mimes' => 'Berkas harus PDF atau JPG.'],
        );
    }
};
?>

<div class="w-full max-w-134.5 space-y-3">
    <x-ceplok::upload
        label="Unggah dokumen"
        accept="application/pdf,image/jpeg"
        wire:model="dokumen"
        :helper-text="$errors->first('dokumen') ?: 'PDF atau JPG, maksimal 2 MB.'"
        :dark-mode="$dark"
    />
    <p class="text-body-sm text-gray-500 dark:text-gray-400" role="status">
        <span wire:loading wire:target="dokumen">Mengunggah…</span>
        <span wire:loading.remove wire:target="dokumen">
            @if ($dokumen && ! $errors->has('dokumen'))
                Terunggah sementara: <span class="text-gray-900 dark:text-white">{{ $dokumen->getClientOriginalName() }}</span>
                ({{ number_format($dokumen->getSize() / 1024, 1, ',', '.') }} KB)
            @else
                Belum ada berkas yang terunggah.
            @endif
        </span>
    </p>
</div>
