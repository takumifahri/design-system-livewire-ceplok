<?php

use CeplokUi\DesignKit\Enums\ContainerSize;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Container. Varian, mode, dan pilihan lebar dirender
 * ulang server; lebar Custom dan angka hasil ukur hidup di Alpine — angkanya
 * diukur langsung dari elemen Container yang dirender, bukan dihitung ulang
 * di sini, jadi dokumentasinya tidak mungkin berbeda dari komponennya.
 */
new class extends Component
{
    public const MIN_WIDTH = 320;

    public const MAX_WIDTH = 1600;

    /** Batas lebar tiap varian, untuk teks di blok Penggunaan. */
    public const LIMITS = ['prose' => '720px', 'default' => '1126px', 'wide' => '1440px', 'full' => 'tanpa batas'];

    public ContainerSize $size = ContainerSize::Default;

    /** Lebar ruang: mobile (380px), desktop (1126px), atau custom. */
    public string $view = 'desktop';

    /** Lebar Custom; digeser di browser lalu ikut terkirim pada request berikutnya. */
    public int $width = 900;

    public bool $dark = false;

    public function updatedWidth(): void
    {
        $this->width = max(self::MIN_WIDTH, min(self::MAX_WIDTH, $this->width));
    }

    #[Computed]
    public function usage(): string
    {
        $limit = self::LIMITS[$this->size->value];
        $room = match ($this->view) {
            'custom' => 'ruang ⟪width|'.$this->width.'⟫px',
            'mobile' => 'ruang sempit (< 640px)',
            default => 'ruang lapang (≥ 640px)',
        };
        $attributes = ($this->size !== ContainerSize::Default ? ' ⟦*size="'.$this->size->value.'"⟧' : '')
            .($this->dark ? ' ⟦*dark-mode⟧' : '')
            .($this->view === 'custom' ? ' class="⟦*max-w-[⟪width|'.$this->width.'⟫px]⟧"' : '');

        return implode("\n", [
            '{{-- 1. '.($limit === 'tanpa batas' ? 'Tanpa batas lebar' : "Batas {$limit}")." di {$room}.",
            '     Terukur sekarang: ruang ⟪measured.avail|…⟫px · konten ⟪measured.content|…⟫px · padding ⟪measured.padding|…⟫px. --}}',
            "<x-ceplok::container{$attributes}>",
            '    ...',
            '</x-ceplok::container>',
            '',
            '{{-- 2. Tambah jarak vertikal lewat class --}}',
            '<x-ceplok::container class="⟦py-12 lg:py-16⟧">',
            '    ...',
            '</x-ceplok::container>',
            '',
            '{{-- 3. Ganti elemen pembungkus & matikan padding',
            '     (mis. saat section sudah punya padding sendiri) --}}',
            '<x-ceplok::container ⟦as⟧="section" :⟦padded⟧="false">',
            '    ...',
            '</x-ceplok::container>',
            '',
            '{{-- 4. Latar penuh layar: warna di elemen luar,',
            '     Container di dalam agar isinya tetap sejajar --}}',
            '<section class="bg-surface-subtle">',
            '    <x-ceplok::container class="py-12">',
            '        ...',
            '    </x-ceplok::container>',
            '</section>',
        ]);
    }
};
?>

@php
    $preset = ['mobile' => 380, 'desktop' => 1126][$view] ?? $width;
    $tone = $dark
        ? ['stage' => 'border-gray-800 bg-gray-900', 'room' => 'bg-gray-900 ring-gray-700', 'isi' => 'border-primary-400/70 bg-transparent', 'label' => 'text-primary-300', 'note' => 'text-gray-400']
        : ['stage' => 'border-border bg-surface-subtle', 'room' => 'bg-primary-50 ring-primary-100', 'isi' => 'border-primary-300 bg-surface', 'label' => 'text-primary-700', 'note' => 'text-gray-500'];
@endphp

<div
    x-data="{
        custom: $wire.entangle('width'),
        measured: { avail: 0, content: 0, padding: 0 },
        observer: null,
        get width() {
            return $wire.view === 'custom' ? this.custom : ($wire.view === 'mobile' ? 380 : 1126)
        },
        clamp(value) {
            return Math.min({{ $this::MAX_WIDTH }}, Math.max({{ $this::MIN_WIDTH }}, Number(value) || {{ $this::MIN_WIDTH }}))
        },
        init() {
            // Elemen dalam milik Container-lah yang memegang max-width dan padding.
            const update = () => {
                const content = this.$refs.content
                this.measured = {
                    avail: Math.round(this.$refs.avail.getBoundingClientRect().width),
                    content: Math.round(content.getBoundingClientRect().width),
                    padding: Math.round(parseFloat(getComputedStyle(content.parentElement).paddingLeft)),
                }
            }
            update()
            this.observer = new ResizeObserver(update)
            this.observer.observe(this.$refs.avail)
            this.observer.observe(this.$refs.content)
        },
        destroy() {
            this.observer?.disconnect()
        },
    }"
    class="space-y-16"
>
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Kotak biru muda adalah ruang yang tersedia; garis putus-putus adalah
            tempat konten berhenti. Selisih keduanya itulah padding. Ganti
            <strong class="text-gray-900">Varian</strong> untuk melihat batas
            lebarnya, lalu <span class="text-primary-500">Mobile</span>/<span class="text-primary-500">Desktop</span> untuk melihat ambang
            bawaannya — atau <span class="text-primary-500">Custom</span> untuk mengetik lebar sendiri. Yang
            berganti di atas benar-benar komponen <span class="text-primary-500">Container</span>, bukan kotak
            tiruan: angkanya diukur langsung dari elemen yang dirender.
        </x-docs.lead>

        <div @class(['rounded-2xl border p-4 transition-colors sm:p-6', $tone['stage']])>
            <div
                x-ref="avail"
                x-bind:style="{ width: width + 'px' }"
                style="width: {{ $preset }}px"
                @class(['mx-auto max-w-full rounded-xl ring-1 transition-[width] duration-300 ease-out', $tone['room']])
            >
                <x-ceplok::container :size="$size" :dark-mode="$dark" class="py-6">
                    <div x-ref="content" @class(['rounded-lg border-2 border-dashed px-4 py-8 text-center', $tone['isi']])>
                        <p @class(['text-sm font-black', $tone['label']])>size="{{ $size->value }}"</p>
                        <p @class(['mt-1 text-xs', $tone['note']])>
                            ruang <span x-text="measured.avail">…</span>px · konten <span x-text="measured.content">…</span>px ·
                            padding <span x-text="measured.padding">…</span>px
                        </p>
                    </div>
                </x-ceplok::container>
            </div>
        </div>

        <x-docs.controls>
            <x-docs.control label="Varian">
                <x-docs.segmented
                    label="Pilih varian lebar container"
                    model="size"
                    :value="$size"
                    :options="collect(ContainerSize::cases())->map(fn ($case) => ['value' => $case->value, 'label' => $case->value])->all()"
                />
            </x-docs.control>

            <x-docs.control label="Lebar">
                <x-docs.segmented label="Pilih lebar container" model="view" :value="$view" :options="[
                    ['value' => 'mobile', 'label' => 'Mobile'],
                    ['value' => 'desktop', 'label' => 'Desktop'],
                    ['value' => 'custom', 'label' => 'Custom'],
                ]" />
            </x-docs.control>

            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Terang'],
                    ['value' => true, 'label' => 'Gelap'],
                ]" />
            </x-docs.control>

            @if ($view === 'custom')
                <x-docs.control label="Lebar Custom">
                    <div class="flex flex-wrap items-center gap-3">
                        <input
                            type="range"
                            min="{{ $this::MIN_WIDTH }}"
                            max="{{ $this::MAX_WIDTH }}"
                            step="10"
                            x-bind:value="custom"
                            x-on:input="custom = clamp($event.target.value)"
                            aria-label="Geser lebar container"
                            class="h-1.5 w-40 cursor-pointer appearance-none rounded-full bg-border accent-primary-600"
                        >
                        <label class="inline-flex items-center gap-2 rounded-lg border border-border bg-surface px-3 py-1.5">
                            <input
                                type="number"
                                min="{{ $this::MIN_WIDTH }}"
                                max="{{ $this::MAX_WIDTH }}"
                                x-bind:value="custom"
                                x-on:input="custom = clamp($event.target.value)"
                                aria-label="Lebar container dalam piksel"
                                class="w-16 bg-transparent text-sm font-bold text-gray-900 outline-none"
                            >
                            <span class="text-xs font-bold text-gray-500">px</span>
                        </label>
                        <span class="text-xs text-gray-500">{{ $this::MIN_WIDTH }}–{{ $this::MAX_WIDTH }}px</span>
                    </div>
                </x-docs.control>
            @endif
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Contoh pertama bukan contoh tetap: ia dirakit ulang setiap kali pilihan
            di Playground berubah — saat ini varian <span class="text-primary-500">{{ $size->value }}</span> ({{ $this::LIMITS[$size->value] }})
            pada
            @if ($view === 'custom')
                ruang <span x-text="width">{{ $width }}</span>px,
            @elseif ($view === 'mobile')
                ruang sempit (&lt; 640px),
            @else
                ruang lapang (≥ 640px),
            @endif
            dengan angka yang diambil dari hasil pengukuran
            preview, bukan ditulis tangan. Tiga pola berikutnya berlaku di semua
            ukuran.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
