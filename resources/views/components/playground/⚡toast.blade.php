<?php

use CeplokUi\DesignKit\Enums\ToastVariant;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Toast. Bingkainya mensimulasikan viewport supaya keempat
 * sudut bisa dicoba. "Tampilkan Toast" dan pergantian isi menaikkan wire:key,
 * jadi Toast yang sudah ditutup dipasang ulang dari nol — seperti `key` di
 * Playground React; perubahan lain cukup ditambal morph.
 */
new class extends Component
{
    /** Pesan singkat per variant. */
    public const MESSAGES = [
        'success' => 'Sukses Membuat Data!',
        'danger' => 'Gagal Membuat Data!',
        'warning' => 'Sesi Anda akan berakhir dalam 5 menit.',
        'info' => 'Data sedang diproses.',
        'purple' => 'Fitur baru sudah tersedia.',
    ];

    /**
     * Empat sudut viewport. Kelasnya ditulis utuh per sudut, bukan dirakit
     * dari potongan string, supaya Tailwind tetap bisa memindainya.
     */
    public const CORNERS = [
        'top-left' => ['label' => 'Atas kiri', 'vertical' => 'top-4', 'horizontal' => 'left-4'],
        'top-right' => ['label' => 'Atas kanan', 'vertical' => 'top-4', 'horizontal' => 'right-4'],
        'bottom-left' => ['label' => 'Bawah kiri', 'vertical' => 'bottom-4', 'horizontal' => 'left-4'],
        'bottom-right' => ['label' => 'Bawah kanan', 'vertical' => 'bottom-4', 'horizontal' => 'right-4'],
    ];

    public ToastVariant $variant = ToastVariant::Success;

    /** Lebar bingkai: mobile atau desktop. */
    public string $width = 'desktop';

    public string $position = 'bottom-left';

    public bool $actions = false;

    public bool $dark = false;

    public int $shown = 0;

    public function show(): void
    {
        $this->shown++;
    }

    #[Computed]
    public function corner(): array
    {
        return self::CORNERS[$this->position] ?? self::CORNERS['bottom-left'];
    }

    #[Computed]
    public function usage(): string
    {
        $corner = $this->corner;

        return collect([
            '<div class="fixed ⟦'.$corner['vertical'].' '.$corner['horizontal'].'⟧ z-50 space-y-3">',
            '    <x-ceplok::toast',
            $this->variant !== ToastVariant::Info ? '        ⟦variant⟧="'.$this->variant->value.'"' : null,
            $this->dark ? '        ⟦dark-mode⟧' : null,
            $this->actions ? '        :⟦icon⟧="svg(\'ceplok-o-refresh\')"' : null,
            $this->actions ? '        ⟦heading⟧="Update 2FA sudah tersedia!"' : null,
            '    >',
            $this->actions ? '        Update Metode Password anda sekarang' : '        '.self::MESSAGES[$this->variant->value],
            $this->actions ? '        <x-slot:⟦actions⟧>' : null,
            $this->actions ? '            <x-ceplok::button size="xs" class="w-full">Update 2FA</x-ceplok::button>' : null,
            $this->actions ? '        </x-slot:actions>' : null,
            '    </x-ceplok::toast>',
            '</div>',
        ])->filter()->implode("\n");
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Bingkai di bawah ini mensimulasikan viewport halaman supaya keempat sudut bisa dicoba. Setiap
            perubahan langsung terlihat, dan bagian Penggunaan menuliskan kodenya. Setelah Toast ditutup,
            klik <span class="text-primary-500">Tampilkan Toast</span> untuk memasangnya kembali.
        </x-docs.lead>

        {{--
            Bukan <x-docs.stage>: panggung Playground biasa hanya kartu berlatar
            netral, sedangkan di sini perlu tinggi tetap dan `relative` supaya
            Toast bisa benar-benar ditempel ke sudut bingkainya.
        --}}
        <div @class([
            'relative h-72 overflow-hidden rounded-2xl border p-4 transition-colors sm:h-80 sm:p-6',
            $dark ? 'border-gray-800 bg-gray-900' : 'border-border bg-surface-subtle',
        ])>
            <div @class([
                'relative mx-auto h-full transition-[max-width] duration-300 ease-out',
                $width === 'mobile' ? 'max-w-75' : 'max-w-full',
            ])>
                {{-- Kerangka konten halaman, hanya latar. Ikut gelap bersama bingkainya. --}}
                @php
                    $titleLine = $dark ? 'bg-gray-600' : 'bg-gray-300';
                    $line = $dark ? 'bg-gray-700' : 'bg-gray-200';
                @endphp
                <div class="space-y-3 opacity-60" aria-hidden="true">
                    <div class="h-2 w-2/5 rounded {{ $titleLine }}"></div>
                    <div class="h-1.5 w-full rounded {{ $line }}"></div>
                    <div class="h-1.5 w-5/6 rounded {{ $line }}"></div>
                    <div class="h-1.5 w-3/4 rounded {{ $line }}"></div>
                    <div class="mt-6 h-2 w-1/3 rounded {{ $titleLine }}"></div>
                    <div class="h-1.5 w-full rounded {{ $line }}"></div>
                    <div class="h-1.5 w-4/6 rounded {{ $line }}"></div>
                </div>

                <div @class([
                    'absolute',
                    $this->corner['vertical'],
                    $width === 'mobile' ? 'inset-x-3' : 'w-full max-w-80 '.$this->corner['horizontal'],
                ])>
                    @if ($actions)
                        <x-ceplok::toast
                            wire:key="toast-aksi-{{ $shown }}"
                            :variant="$variant"
                            :dark-mode="$dark"
                            :icon="svg('ceplok-o-refresh')"
                            heading="Update 2FA sudah tersedia!"
                        >
                            Update Metode Password anda sekarang
                            <x-slot:actions>
                                <x-ceplok::button size="xs" class="w-full">Update 2FA</x-ceplok::button>
                            </x-slot:actions>
                        </x-ceplok::toast>
                    @else
                        <x-ceplok::toast wire:key="toast-singkat-{{ $shown }}" :variant="$variant" :dark-mode="$dark">
                            {{ $this::MESSAGES[$variant->value] }}
                        </x-ceplok::toast>
                    @endif
                </div>
            </div>
        </div>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Variant">
                <x-docs.segmented label="Pilih variant" model="variant" :value="$variant" item-class="px-2.5" wrap :options="[
                    ['value' => 'success', 'label' => 'Success'],
                    ['value' => 'danger', 'label' => 'Danger'],
                    ['value' => 'warning', 'label' => 'Warning'],
                    ['value' => 'info', 'label' => 'Info'],
                    ['value' => 'purple', 'label' => 'Purple'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Lebar">
                <x-docs.segmented label="Pilih lebar toast" model="width" :value="$width" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'desktop', 'label' => 'Desktop'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Isi">
                <x-docs.segmented label="Pilih isi toast" model="actions" :value="$actions" :options="[
                    ['value' => false, 'label' => 'Pesan singkat'],
                    ['value' => true, 'label' => 'Heading + aksi'],
                ]" />
            </x-docs.control>


            <x-docs.control label="Posisi">
                <div class="inline-grid grid-cols-2 gap-1 rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih posisi toast">
                    @foreach ($this::CORNERS as $value => $corner)
                        <button
                            type="button"
                            wire:click="$set('position', @js($value))"
                            @class([
                                'grid size-9 place-items-center rounded-md transition-colors',
                                $position === $value ? 'bg-primary-50 text-primary-700' : 'text-gray-400 hover:text-gray-700',
                            ])
                            aria-pressed="{{ $position === $value ? 'true' : 'false' }}"
                            aria-label="{{ $corner['label'] }}"
                            title="{{ $corner['label'] }}"
                        >
                            <span class="size-1.5 rounded-full bg-current"></span>
                        </button>
                    @endforeach
                </div>
            </x-docs.control>
        </x-docs.controls>

        <button
            type="button"
            wire:click="show"
            class="mt-5 inline-flex items-center rounded-lg bg-primary-700 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-primary-800"
        >
            Tampilkan Toast
        </button>

        <p class="mt-4 text-body-sm text-gray-500">
            Lebar mobile memakai <span class="text-primary-500">inset-x-3</span> agar Toast merentang mengikuti layar; di desktop lebarnya
            dibatasi lalu ditempel ke satu sisi.
        </p>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground — ubah kontrolnya, kodenya ikut berubah. Wadah
            <span class="text-primary-500">fixed</span> ikut ditulis karena posisinya memang ditentukan di sana, bukan di dalam Toast.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
