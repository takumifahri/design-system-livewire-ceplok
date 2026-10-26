<?php

use CeplokUi\DesignKit\Enums\PopoverSide;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Popover, port Playground di PopoverPage docs React:
 * preferensi sisi dan tampilan terang/gelap. Buka-tutupnya diurus Popover
 * sendiri di peramban, jadi tidak ada properti `open` di sini.
 */
new class extends Component
{
    public PopoverSide $side = PopoverSide::Right;

    public bool $dark = false;

    #[Computed]
    public function usage(): string
    {
        return implode("\n", array_filter([
            '<x-ceplok::popover',
            '    ⟦title⟧="Popover"',
            $this->side === PopoverSide::Right ? null : '    ⟦side⟧="'.$this->side->value.'"',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '>',
            '    <x-slot:⟦trigger⟧',
            '        raw',
            '        aria-label="Buka informasi"',
            '        class="inline-flex size-9 items-center justify-center rounded-md '.($this->dark ? 'text-gray-400' : 'text-gray-500').' focus-visible:outline-2"',
            '    >',
            '        <x-ceplok::icon>{{ svg(\'ceplok-o-info-circle\') }}</x-ceplok::icon>',
            '    </x-slot:trigger>',
            '    Popover Body Text, Popover Body Text, Popover Body Text',
            '</x-ceplok::popover>',
        ]));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Pilih preferensi posisi dan tampilan, lalu buka Popover melalui trigger untuk menguji
            lifecycle komponen yang sebenarnya.
        </x-docs.lead>

        <x-docs.stage max-width="max-w-[640px]" :dark="$dark">
            <div class="flex min-h-32 items-center justify-center p-6">
                <x-docs.popover-example label="Buka Popover Playground" :side="$side" :dark="$dark" />
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan Popover" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Side">
                <x-docs.segmented label="Pilih preferensi posisi Popover" model="side" :value="$side" wrap :options="[
                    ['value' => 'right', 'label' => 'Right'],
                    ['value' => 'left', 'label' => 'Left'],
                    ['value' => 'top', 'label' => 'Top'],
                    ['value' => 'bottom', 'label' => 'Bottom'],
                ]" />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Kode mengikuti pilihan Playground. Nilai default <span class="text-primary-500">right</span> dan tampilan light tidak
            ditulis. Trigger tetap butuh nama aksesibel sendiri; Popover memasang <span class="text-primary-500">id</span>,
            <span class="text-primary-500">aria-controls</span>, dan <span class="text-primary-500">aria-expanded</span> di trigger itu.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />

        <h3 class="mt-8 text-sm font-black text-gray-900">Accessibility</h3>
        <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
            Popover menambahkan <span class="text-primary-500">aria-expanded</span> dan <span class="text-primary-500">aria-controls</span> pada trigger, sedangkan
            trigger tetap harus memiliki nama aksesibel. Panel bersifat non-modal dan tidak memindahkan
            atau mengunci fokus. Role dan atribut ARIA tambahan dapat diteruskan sesuai isi panel.
        </p>
    </x-docs.section>
</div>
