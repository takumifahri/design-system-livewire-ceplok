@php
    use App\Docs\Theme;

    $docsDark = Theme::dark();
    $items = [
        ['label' => 'Home', 'href' => '#'],
        ['label' => 'Menu 1', 'href' => '#'],
        ['label' => 'Menu 2', 'href' => '#'],
    ];

    // Tautan sungguhan ke halaman docs, berpindah lewat wire:navigate.
    $navigate = ['wire:navigate' => true];
    $docs = [
        ['label' => 'Home', 'href' => route('home'), 'attributes' => $navigate],
        ['label' => 'Components', 'href' => route('components.index'), 'attributes' => $navigate],
        ['label' => 'Breadcrumb'],
    ];

    $props = [
        ['items', 'array', '[]', "Daftar item: ['label' => …, 'href' => …, 'icon' => …, 'attributes' => […]]. Item terakhir jadi halaman aktif dan tidak pernah menjadi tautan. attributes diteruskan ke tautannya — padanan onClick, mis. ['wire:navigate' => true]."],
        ['size', '"sm" | "base"', 'base', 'Menentukan ukuran teks Breadcrumb. sm = 12px dan base = 14px.'],
        ['background', '"bg" | "none"', 'none', 'Menentukan apakah Breadcrumb menggunakan background.'],
        ['separator', 'slot', 'chevron', 'Pemisah antar-item; bawaannya ikon chevron.'],
        ['dark-mode', 'bool', 'false', 'Mengaktifkan tampilan mode gelap pada Breadcrumb secara manual.'],
    ];
@endphp

<x-docs.layout title="Breadcrumb">
    <x-docs.page
        eyebrow="Components · Breadcrumb"
        title="Breadcrumb"
        description="Menampilkan posisi halaman saat ini dalam struktur navigasi dan membantu pengguna berpindah ke halaman sebelumnya. Tersedia juga dalam tampilan gelap."
        stay-dark-mode
        :toc="[
            'sizes' => 'Sizes',
            'variants' => 'Variants',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="sizes" title="Sizes">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Breadcrumb tersedia dalam dua ukuran untuk menyesuaikan kebutuhan hierarki dan kepadatan
                informasi pada layout. Ukuran <span class="text-primary-500">base</span> adalah ukuran default. Ukuran <span class="text-primary-500">sm</span>
                digunakan ketika Breadcrumb diimplementasikan pada mobile.
            </p>

            <div class="mb-4 grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="sm">
                    <div>
                        <x-ceplok::breadcrumb :items="$items" size="sm" background="none" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="base - 14 px">
                    <x-ceplok::breadcrumb :items="$items" size="base" background="none" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                @php
                    $items = [
                        ['label' => 'Home', 'href' => '#'],
                        ['label' => 'Menu 1', 'href' => '#'],
                        ['label' => 'Menu 2', 'href' => '#'],
                    ];
                @endphp

                <x-ceplok::breadcrumb
                    :items="$items"
                    ⟦size⟧="base"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="variants" title="Variants">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Breadcrumb dapat digunakan dengan atau tanpa background. Background digunakan ketika
                Breadcrumb perlu memiliki area visual tersendiri dari konten halaman.
            </p>

            <div class="mb-4 grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Tanpa Background">
                    <x-ceplok::breadcrumb :items="$items" size="base" background="none" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Dengan Background">
                    <x-ceplok::breadcrumb :items="$items" size="base" background="bg" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                {{-- Tanpa background --}}
                <x-ceplok::breadcrumb
                    :items="$items"
                    ⟦background⟧="none"
                    ⟨dark-mode⟩
                />

                {{-- Dengan background --}}
                <x-ceplok::breadcrumb
                    :items="$items"
                    ⟦background⟧="bg"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section name="Breadcrumb" stay-dark>
            <x-slot:lead>
                Breadcrumb mengadopsi sistem token warna semantik dan token komponen (<span class="text-primary-300">--color-breadcrumb-surface</span>,
                <span class="text-primary-300">--color-breadcrumb-active</span>, dan <span class="text-primary-300">--color-breadcrumb-link-hover</span>). Mode gelap dapat
                diaktifkan lewat prop <span class="text-primary-300">dark-mode</span> maupun pewarisan otomatis atribut
                <span class="text-primary-300">data-theme="dark"</span> dari kontainer pembungkusnya. Latar belakang (pada varian
                <span class="text-primary-300">bg</span>) menjadi <span class="text-primary-300">gray-700</span>, item aktif <span class="text-primary-300">gray-300</span>, serta item tidak aktif dan
                ikon pemisah (chevron) menggunakan <span class="text-primary-300">gray-400</span>.
            </x-slot:lead>

            <div class="mb-4 grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Tanpa Background" dark>
                    <x-ceplok::breadcrumb dark-mode :items="$items" size="base" background="none" />
                </x-docs.demo>
                <x-docs.demo label="Dengan Background" dark>
                    <x-ceplok::breadcrumb dark-mode :items="$items" size="base" background="bg" />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                {{-- Tanpa background --}}
                <x-ceplok::breadcrumb
                    ⟦dark-mode⟧
                    :items="$items"
                    background="none"
                />

                {{-- Dengan background --}}
                <x-ceplok::breadcrumb
                    ⟦dark-mode⟧
                    :items="$items"
                    background="bg"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.breadcrumb :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Kunci <span class="text-primary-500">attributes</span> pada tiap item diteruskan ke tautannya — padanan onClick di React.
                Isi <span class="text-primary-500">wire:navigate</span> supaya perpindahan halaman berjalan tanpa memuat ulang
                seluruh halaman. Tautan di bawah benar-benar membuka halaman docs.
            </x-docs.lead>
            <x-docs.demo>
                <x-ceplok::breadcrumb :items="$docs" background="bg" :dark-mode="$docsDark" />
            </x-docs.demo>
            <x-docs.code mark="'attributes'">
                @verbatim
                <x-ceplok::breadcrumb :items="[
                    ['label' => 'Home', 'href' => route('home'), 'attributes' => ['wire:navigate' => true]],
                    ['label' => 'Components', 'href' => route('components.index'), 'attributes' => ['wire:navigate' => true]],
                    ['label' => 'Breadcrumb'],
                ]"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Referensi semua prop yang tersedia pada komponen Breadcrumb.
            </p>
            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
