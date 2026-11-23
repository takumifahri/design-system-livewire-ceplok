<?php

use Livewire\Component;

/*
 * Contoh integrasi Livewire di halaman Text Area: tombol kirim memicu event
 * `submit` dan tombol toolbar memicu `toolbar-action`, keduanya dari elemen
 * textarea, jadi cukup ditangkap wire:submit dan wire:toolbar-action.
 */
new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya (`:dark="$docsDark"`). */
    public bool $dark = false;

    public string $pesan = '';

    /** @var list<string> */
    public array $terkirim = [];

    public ?string $alat = null;

    public function kirim(): void
    {
        $this->validate(['pesan' => 'required'], ['pesan.required' => 'Tulis pesannya dulu.']);

        $this->terkirim[] = $this->pesan;
        $this->reset('pesan', 'alat');
    }

    public function pakaiAlat(string $action): void
    {
        $this->alat = $action;
    }
};
?>

<div class="w-full max-w-123.5 space-y-4">
    <x-ceplok::text-area
        type="editor"
        label="Isi pesan"
        placeholder="Tulis pesan Anda…"
        wire:model="pesan"
        :helper-text="$errors->first('pesan') ?: ($alat ? 'Alat dipilih: '.$alat : null)"
        submit-label="Kirim"
        wire:submit="kirim"
        wire:toolbar-action="pakaiAlat($event.detail.action)"
        :dark-mode="$dark"
    />
    @if ($terkirim)
        <ul class="space-y-2 text-body-sm text-gray-700 dark:text-gray-300">
            @foreach ($terkirim as $i => $isi)
                <li wire:key="pesan-{{ $i }}" class="rounded-lg border border-border bg-surface px-4 py-2 dark:border-gray-700 dark:bg-gray-800">{{ $isi }}</li>
            @endforeach
        </ul>
    @endif
</div>
