@php
    $props = [
        ['size', '"default" | "large"', 'default', 'Menentukan ukuran Spinner: 50×50 px untuk default dan 100×100 px untuk large.'],
        ['…atribut', 'HTML (SVG)', '—', 'Atribut SVG native yang relevan diteruskan (class, aria-label, aria-hidden, wire:loading, …). Ukuran dan geometri internal, termasuk width, height, viewBox, dan fill, dikontrol oleh Spinner.'],
    ];
@endphp

<x-docs.layout title="Spinner">
    <x-docs.page
        title="Spinner"
        description="Indikator proses berputar untuk kondisi loading yang durasinya belum diketahui."
        :toc="[
            'sizes' => 'Sizes',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="sizes" title="Sizes">
            <div class="grid gap-4 md:grid-cols-2">
                <article>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white">Default</h3>
                    <p class="mt-1 mb-3 text-body-sm text-gray-500 dark:text-gray-400">
                        Ukuran standar 50×50 px untuk indikator loading pada area komponen atau konten umum.
                        Ukuran ini digunakan secara default, jadi prop <span class="text-primary-500 dark:text-primary-400">size</span> tidak perlu ditulis.
                    </p>
                    <x-docs.example>
                        <x-ceplok::spinner />
                    </x-docs.example>
                </article>

                <article>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white">Large</h3>
                    <p class="mt-1 mb-3 text-body-sm text-gray-500 dark:text-gray-400">
                        Ukuran 100×100 px untuk loading state yang membutuhkan penekanan visual lebih besar.
                        Gunakan <span class="text-primary-500 dark:text-primary-400">size="large"</span> untuk memilih ukuran ini.
                    </p>
                    <x-docs.example>
                        <x-ceplok::spinner size="large" />
                    </x-docs.example>
                </article>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::spinner />

                <x-ceplok::spinner ⟦size="large"⟧ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.spinner />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Pasang <span class="text-primary-500 dark:text-primary-400">wire:loading</span> pada pembungkus Spinner supaya ia hanya tampil selama aksi
                Livewire berjalan, dan <span class="text-primary-500 dark:text-primary-400">wire:target</span> untuk membatasinya ke satu aksi.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.spinner-loading />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::button wire:click="load" wire:loading.attr="disabled" wire:target="load">
                    Muat data
                </x-ceplok::button>

                <div ⟦wire:loading.flex⟧ ⟦wire:target⟧="load" class="items-center gap-3">
                    <x-ceplok::spinner aria-hidden="true" />
                    <span>Memuat data…</span>
                </div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut SVG native
                yang relevan tetap dapat diteruskan.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap [&_td:nth-child(2)]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="46rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
