<?php

use CeplokUi\DesignKit\Enums\PaginationTheme;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Pagination. Halaman aktifnya diikat wire:model.live —
 * padanan onPageChange={setCurrentPage} di React — jadi setiap klik dirender
 * ulang server dengan halaman yang baru.
 */
new class extends Component
{
    public int $current = 1;

    public PaginationTheme $theme = PaginationTheme::Primary;

    public int $total = 100;

    public bool $dark = false;

    /** Halaman aktif tidak boleh melewati jumlah halaman yang baru. */
    public function updatedTotal(): void
    {
        $this->total = max(1, $this->total);
        $this->current = min($this->current, $this->total);
    }

    public function updatedCurrent(): void
    {
        $this->current = max(1, min($this->current, $this->total));
    }

    #[Computed]
    public function usage(): string
    {
        return implode("\n", array_filter([
            '<x-ceplok::pagination',
            '    :⟦current-page⟧="$page"',
            '    :⟦total-pages⟧="'.$this->total.'"',
            '    ⟦wire:model.live⟧="page"',
            '    ⟦theme⟧="'.$this->theme->value.'"',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '/>',
        ]));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <p class="mb-6 text-body-sm text-gray-500">
            Coba konfigurasi Pagination secara langsung melalui kontrol di bawah ini untuk melihat
            perubahan halaman dan theme.
        </p>

        <x-docs.stage max-width="max-w-[700px]" :dark="$dark">
            <div class="flex min-h-40 items-center justify-center">
                <x-ceplok::pagination :current-page="$current" :total-pages="$total" wire:model.live="current" :theme="$theme" :dark-mode="$dark" />
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Theme">
                <x-docs.segmented label="Pilih theme" model="theme" :value="$theme" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'primary', 'label' => 'Primary'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Total pages">
                <x-docs.segmented
                    label="Pilih jumlah halaman"
                    model="total"
                    :value="$total"
                    :options="array_map(fn ($n) => ['value' => $n, 'label' => (string) $n], [25, 50, 75, 100])"
                />
            </x-docs.control>

            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <p class="mb-6 text-body-sm text-gray-500">
            Bagian ini menampilkan contoh kode penggunaan Pagination berdasarkan konfigurasi yang
            dipilih pada Playground. <span class="text-primary-500">$page</span> adalah properti publik di komponen Livewire Anda.
        </p>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
