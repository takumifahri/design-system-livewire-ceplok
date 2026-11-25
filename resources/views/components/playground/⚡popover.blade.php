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
        $code = implode("\n", array_filter([
            '<x-ceplok::popover',
            '    ⟦title⟧="Popover"',
            $this->side === PopoverSide::Right ? null : '    ⟦side⟧="'.$this->side->value.'"',
            $this->dark ? '    ⟦dark-mode⟧' : null,
            '>',
            '    <x-slot:⟦trigger⟧',
            '        raw',
            '        type="button"',
            '        aria-label="Buka informasi"',
            '        class="inline-flex size-9 items-center justify-center rounded-md '.($this->dark ? 'text-gray-400' : 'text-gray-500').' focus-visible:outline-2"',
            '    >',
            '        <x-ceplok::icon>{{ svg(\'ceplok-o-info-circle\') }}</x-ceplok::icon>',
            '    </x-slot:trigger>',
            '    Popover Body Text, Popover Body Text, Popover Body Text',
            '</x-ceplok::popover>',
        ]));

        return $code ?? '';
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
                <x-docs.popover-example label="Buka Popover Playground" :side="$side" :dark="$dark" :dark-mode="$dark" />
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
            Kode mengikuti pilihan Playground. Nilai default <span class="text-primary-500 dark:text-primary-400">right</span> dan tampilan light tidak
            ditulis. Popover merender Button kit dari atribut dan isi slot <span class="text-primary-500 dark:text-primary-400">trigger</span>;
            tambahkan <span class="text-primary-500 dark:text-primary-400">raw</span> untuk native <span class="text-primary-500 dark:text-primary-400">&lt;button&gt;</span>
            polos. Keduanya menghasilkan tombol native yang dapat menerima fokus serta diaktifkan dengan Enter dan Space.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />

        <h3 class="mt-8 text-sm font-black text-gray-900 dark:text-white">Accessibility</h3>
        <p class="mt-1 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
            Popover menambahkan <span class="text-primary-500 dark:text-primary-400">aria-expanded</span> dan <span class="text-primary-500 dark:text-primary-400">aria-controls</span> pada trigger, sedangkan
            trigger tetap harus memiliki nama aksesibel. Panel bersifat non-modal dan tidak memindahkan
            atau mengunci fokus. Popover memastikan hasil akhirnya native <span class="text-primary-500 dark:text-primary-400">&lt;button&gt;</span>:
            Button kit menjadi bawaan, sedangkan <span class="text-primary-500 dark:text-primary-400">raw</span> menghasilkan tombol polos.
            Trigger yang dirender sebagai anchor tidak didukung.
            Role dan atribut ARIA tambahan dapat diteruskan sesuai isi panel.
        </p>
    </x-docs.section>
</div>
