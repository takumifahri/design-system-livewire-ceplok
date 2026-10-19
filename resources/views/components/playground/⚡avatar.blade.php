<?php

use CeplokUi\DesignKit\Enums\AvatarSize;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Avatar. `wire:key` ikut berubah bersama kontrolnya:
 * berganti antara foto dan inisial mengubah struktur elemennya (ada/tidaknya
 * x-data), jadi avatarnya diganti utuh, bukan ditambal morph.
 */
new class extends Component
{
    public AvatarSize $size = AvatarSize::Default;

    public bool $photo = true;

    public bool $named = true;

    public bool $dark = false;

    /** Prop yang nilainya masih bawaan sengaja tidak ditulis. */
    #[Computed]
    public function usage(): string
    {
        return collect([
            '<x-ceplok::avatar',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            $this->size !== AvatarSize::Default ? '    ⟦size⟧="'.$this->size->value.'"' : null,
            $this->photo ? '    ⟦src⟧="/foto/putri.jpg"' : null,
            '    ⟦initials⟧="PH"',
            $this->named ? '    ⟦alt⟧="Putri Handayani"' : null,
            '/>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Satu avatar yang bisa Anda utak-atik lewat kontrol di bawahnya. Matikan fotonya untuk
            melihat inisialnya mengambil alih.
        </x-docs.lead>

        {{-- Tinggi minimum dikunci supaya kotaknya tidak naik-turun saat ukuran avatar diganti. --}}
        <x-docs.stage max-width="max-w-[320px]" :dark="$dark">
            <div class="flex min-h-30 items-center justify-center gap-3">
                <x-ceplok::avatar
                    wire:key="avatar-{{ $size->value }}-{{ (int) $photo }}-{{ (int) $named }}-{{ (int) $dark }}"
                    :size="$size"
                    :src="$photo ? '/images/avatar-sample.svg' : null"
                    initials="PH"
                    :alt="$named ? 'Putri Handayani' : ''"
                    :dark-mode="$dark"
                />
                <span @class(['text-sm', $dark ? 'text-gray-100' : 'text-gray-900'])>Putri Handayani</span>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Size">
                <x-docs.segmented label="Pilih ukuran" model="size" :value="$size" :options="[
                    ['value' => 'small', 'label' => 'Small'],
                    ['value' => 'default', 'label' => 'Default'],
                    ['value' => 'large', 'label' => 'Large'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Foto">
                <x-docs.segmented label="Tampilkan foto" model="photo" :value="$photo" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Nama (alt)">
                <x-docs.segmented label="Isi nama" model="named" :value="$named" :options="[
                    ['value' => true, 'label' => 'Ada'],
                    ['value' => false, 'label' => 'Tidak ada'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
        </x-docs.controls>

        <p class="mt-4 text-body-sm text-gray-500">
            Contoh di atas sengaja menaruh nama di sebelah avatarnya — susunan yang paling sering
            dipakai. Dalam keadaan itu <em>Nama (alt)</em> justru lebih baik dikosongkan, karena
            namanya sudah terbaca dari teks di sampingnya.
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
