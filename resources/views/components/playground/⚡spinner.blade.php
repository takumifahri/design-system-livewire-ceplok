<?php

use CeplokUi\DesignKit\Enums\SpinnerSize;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground dan Penggunaan halaman Spinner. Bagian Aksesibilitas ikut di
 * sini karena satu section dengan kode yang mengikuti kontrol Size.
 */
new class extends Component
{
    public SpinnerSize $size = SpinnerSize::Default;

    /** Nilai bawaan tidak perlu ditulis. */
    #[Computed]
    public function usage(): string
    {
        return $this->size === SpinnerSize::Default
            ? '<x-ceplok::spinner />'
            : '<x-ceplok::spinner ⟦size="large"⟧ />';
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <p class="mb-4 max-w-2xl text-body-sm text-gray-500">
            Ubah Size untuk melihat ukuran Spinner. Nilai default tidak perlu ditulis pada kode penggunaan.
        </p>
        <x-docs.example>
            <x-ceplok::spinner :size="$size" />
        </x-docs.example>

        <div class="mt-4 flex flex-wrap items-center gap-3">
            <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Size</span>
            <x-docs.segmented label="Pilih ukuran Spinner" model="size" :value="$size" :options="[
                ['value' => 'default', 'label' => 'Default'],
                ['value' => 'large', 'label' => 'Large'],
            ]" />
        </div>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <p class="mb-4 max-w-2xl text-body-sm text-gray-500">
            Blok ini mengikuti kontrol di Playground — ubah Size, preview dan kodenya ikut berubah. Prop
            yang masih memakai nilai bawaan sengaja tidak ditulis.
        </p>
        <x-docs.code :code="$this->usage" flush />

        <h3 class="mt-8 text-sm font-black text-gray-900">Accessibility</h3>
        <h4 class="mt-4 text-sm font-black text-gray-900">Standalone loading</h4>
        <p class="mt-1 mb-3 max-w-2xl text-body-sm text-gray-500">
            Gunakan Spinner secara standalone ketika tidak ada teks loading lain. Spinner memiliki status
            aksesibel dengan label default “Loading”. Sesuaikan <span class="text-primary-500">aria-label</span> jika proses yang
            berlangsung perlu dijelaskan lebih spesifik.
        </p>
        <x-docs.example>
            <x-ceplok::spinner aria-label="Memuat daftar pengguna" />
        </x-docs.example>

        <h4 class="mt-6 text-sm font-black text-gray-900">Spinner dengan teks</h4>
        <p class="mt-1 mb-3 max-w-2xl text-body-sm text-gray-500">
            Jika teks di sebelah Spinner sudah menjelaskan proses loading, sembunyikan Spinner dari screen
            reader dengan <span class="text-primary-500">aria-hidden="true"</span> agar informasi yang sama tidak diumumkan dua kali.
            Teks yang terlihat menjadi sumber informasi loading bagi pengguna.
        </p>
        <x-docs.example>
            <div class="flex items-center gap-3">
                <x-ceplok::spinner aria-hidden="true" />
                <span class="text-body-sm text-gray-700">Memuat data…</span>
            </div>
        </x-docs.example>

        <x-docs.code>
            @verbatim
            <x-ceplok::spinner ⟦aria-label="Memuat daftar pengguna"⟧ />

            <div class="flex items-center gap-3">
                <x-ceplok::spinner ⟦aria-hidden="true"⟧ />
                <span>Memuat data...</span>
            </div>
            @endverbatim
        </x-docs.code>

        <h4 class="mt-6 text-sm font-black text-gray-900">Loading region &amp; reduced motion</h4>
        <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
            Letakkan <span class="text-primary-500">aria-busy="true"</span> pada region yang sedang dimuat, bukan pada Spinner.
            Saat pengguna mengaktifkan reduced motion, animasi Spinner dihentikan tetapi indikator loading
            tetap terlihat.
        </p>
    </x-docs.section>
</div>
