<?php

use Livewire\Component;

new class extends Component
{
    /** Tampilan gelap, mengikuti mode docs halamannya. */
    public bool $dark = false;

    public bool $open = false;

    public int $changes = 0;

    public function changed(bool $open): void
    {
        $this->open = $open;
        $this->changes++;
    }
};
?>

<div class="space-y-4">
    <div class="flex min-h-24 items-center gap-3">
        <x-ceplok::popover title="Status pengajuan" wire:model.live="open" wire:open-change="changed($event.detail.open)" :dark-mode="$dark">
            <x-slot:trigger raw type="button" class="inline-flex rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500">
                Lihat status
            </x-slot:trigger>
            Pengajuan sedang diverifikasi.
        </x-ceplok::popover>
    </div>
    <p class="text-body-sm text-content-subtle dark:text-gray-300" role="status">
        $open = {{ $open ? 'true' : 'false' }} · berubah {{ $changes }}×
    </p>
</div>
