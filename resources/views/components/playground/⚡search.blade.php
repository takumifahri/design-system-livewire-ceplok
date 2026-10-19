<?php

use CeplokUi\DesignKit\Enums\SearchApplication;
use CeplokUi\DesignKit\Enums\SearchPlatform;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Search Form. Isian dan kategorinya diikat lewat
 * wire:model, dan event `search` ditangkap wire:search untuk menampilkan
 * pencarian terakhir. `wire:key` memuat kategori: bentuk polos dan bentuk
 * kategori punya input yang berbeda, dan pendengar Enter dipasang sekali saat
 * Search diinisialisasi.
 */
new class extends Component
{
    public SearchApplication $application = SearchApplication::Default;

    public SearchPlatform $platform = SearchPlatform::Default;

    public bool $category = false;

    public bool $label = false;

    public bool $helper = false;

    public bool $dark = false;

    public string $value = '';

    public string $kategori = '';

    public ?string $terakhir = null;

    public function cari(string $value, string $category = ''): void
    {
        $value = $value === '' ? '(kosong)' : $value;
        $this->terakhir = $category !== '' ? "{$value} · {$category}" : $value;
    }

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::search',
            $this->application !== SearchApplication::Default ? '    ⟦application⟧="'.$this->application->value.'"' : null,
            $this->platform !== SearchPlatform::Default && ! $this->category ? '    ⟦platform⟧="mobile"' : null,
            $this->category ? '    :⟦categories⟧="$kategori"' : null,
            $this->label ? '    ⟦label⟧="Cari data"' : null,
            $this->helper ? '    ⟦helper-text⟧="Tekan Enter atau tombol Cari."' : null,
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '    placeholder="Search Civitas, Organisasi…"',
            '    wire:model="kata"',
            $this->category ? "    :category-attributes=\"['wire:model' => 'kategori']\"" : null,
            '    wire:search="cari($event.detail.value, $event.detail.category)"',
            '/>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Satu komponen yang bisa Anda utak-atik lewat kontrol di bawahnya. Tekan tombolnya atau Enter di
            dalam field untuk melihat nilai yang dikirim event <span class="text-primary-500">search</span>.
        </x-docs.lead>

        <x-docs.stage
            :dark="$dark"
            :max-width="$category
                ? ($platform === SearchPlatform::Mobile ? 'max-w-95.5' : 'max-w-220.5')
                : ($platform === SearchPlatform::Mobile ? 'max-w-87' : 'max-w-140')"
        >
            <x-ceplok::search
                wire:key="search-{{ (int) $category }}"
                wire:model="value"
                :application="$application"
                :platform="$platform"
                :categories="$category ? [
                    ['value' => 'civitas', 'label' => 'Civitas'],
                    ['value' => 'organisasi', 'label' => 'Organisasi'],
                    ['value' => 'berita', 'label' => 'Berita'],
                    ['value' => 'layanan', 'label' => 'Layanan'],
                ] : null"
                :category-attributes="['wire:model' => 'kategori']"
                :label="$label ? 'Cari data' : null"
                :helper-text="$helper ? 'Tekan Enter atau tombol Cari.' : null"
                placeholder="Search Civitas, Organisasi…"
                :dark-mode="$dark"
                wire:search="cari($event.detail.value, $event.detail.category ?? '')"
            />
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Application">
                <x-docs.segmented label="Pilih aplikasi" model="application" :value="$application" item-class="px-2.5" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Platform">
                <x-docs.segmented label="Pilih platform" model="platform" :value="$platform" :options="[
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'mobile', 'label' => 'Mobile'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Kategori">
                <x-docs.segmented label="Tampilkan kategori" model="category" :value="$category" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Label">
                <x-docs.segmented label="Tampilkan label" model="label" :value="$label" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Helper text">
                <x-docs.segmented label="Tampilkan helper text" model="helper" :value="$helper" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500">
            Pencarian terakhir:
            @if ($terakhir === null)
                <em>belum ada</em>.
            @else
                <span class="text-primary-500">{{ $terakhir }}</span>.
            @endif
            Saat kategori dinyalakan, <em>Platform</em> hanya mengganti lebar wadah pratinjaunya — 882px
            atau 382px — sedangkan tinggi dan isi ruasnya tetap sama.
        </p>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground — ubah kontrolnya, kodenya ikut berubah. Prop yang
            nilainya masih bawaan sengaja tidak ditulis.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
