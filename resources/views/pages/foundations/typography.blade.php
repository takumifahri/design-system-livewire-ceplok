@php
    use Illuminate\Support\HtmlString;

    // Font yang bisa dipratinjau; `cls` dipasang ke seluruh isi halaman.
    $fonts = [
        'sans' => ['name' => 'Lato', 'cls' => 'font-sans', 'role' => 'Primary typeface', 'description' => 'Lato dipilih karena terbuka, humanis, dan tetap terbaca baik pada layar kecil. Skala berikut menjaga hierarki tetap konsisten di seluruh produk.'],
        'alt' => ['name' => 'Inter', 'cls' => 'font-alt', 'role' => 'Alternate typeface', 'description' => 'Inter adalah font alternatif. Dirancang untuk layar, jelas di ukuran kecil, dan angkanya sama lebar. Skala dan token tetap sama dengan Lato.'],
        'alt2' => ['name' => 'Arial', 'cls' => 'font-alt-2', 'role' => 'Alternate typeface 2', 'description' => 'Arial adalah font alternatif kedua. Font sistem yang tersedia hampir di semua perangkat, jadi tidak perlu diunduh dan halaman tampil lebih cepat. Skala dan token tetap sama dengan Lato.'],
    ];

    // Pilihan font disimpan di query ?font=inter / ?font=arial supaya tautannya bisa dibagikan;
    // server ikut membacanya, jadi halaman langsung tampil dengan font yang benar.
    $params = ['alt' => 'inter', 'alt2' => 'arial'];
    $initial = array_search(request()->query('font'), $params, true) ?: 'sans';
    $font = $fonts[$initial];

    $typeScale = [
        ['Display', 'text-display font-bold', '48 / 55 · Bold', 'Layanan digital untuk semua'],
        ['Heading 1', 'text-heading-1 font-bold', '36 / 43 · Bold', 'Transformasi digital Indonesia'],
        ['Heading 2', 'text-heading-2 font-bold', '30 / 38 · Bold', 'Informasi dan layanan publik'],
        ['Heading 3', 'text-heading-3 font-bold', '24 / 31 · Bold', 'Akses mudah dan terpercaya'],
        ['Heading 4', 'text-heading-4 font-bold', '20 / 28 · Bold', 'Pelayanan dalam satu tempat'],
        ['Body Large', 'text-body-lg font-normal', '18 / 29 · Regular', 'Kami menghadirkan layanan digital yang inklusif, aman, dan mudah digunakan.'],
        ['Body', 'text-body font-normal', '16 / 24 · Regular', 'Gunakan bahasa yang ringkas agar informasi dapat dipahami oleh seluruh masyarakat.'],
        ['Body Small', 'text-body-sm font-normal', '14 / 20 · Regular', 'Informasi pendukung, deskripsi singkat, dan label komponen.'],
        ['Caption', 'text-caption font-bold tracking-wide uppercase', '12 / 17 · Bold', 'Terakhir diperbarui 18 Juli 2026'],
    ];
    $tailwindScale = [['text-xs', '12px'], ['text-sm', '14px'], ['text-base', '16px'], ['text-lg', '18px'], ['text-xl', '20px'], ['text-2xl', '24px'], ['text-3xl', '30px'], ['text-4xl', '36px'], ['text-5xl', '48px'], ['text-6xl', '60px'], ['text-7xl', '72px'], ['text-8xl', '96px'], ['text-9xl', '128px']];
    $fontWeights = [['font-thin', 100], ['font-extralight', 200], ['font-light', 300], ['font-normal', 400], ['font-medium', 500], ['font-semibold', 600], ['font-bold', 700], ['font-extrabold', 800], ['font-black', 900]];
    $lineHeights = [['leading-none', '100%'], ['leading-normal', '150%'], ['leading-loose', '200%']];
    $textTransforms = [['uppercase', 'text-transform: uppercase'], ['line-through', 'text-decoration: line-through'], ['underline', 'text-decoration: underline']];

    $semantic = <<<'BLADE'
        {{-- Token semantik — hierarki utama halaman --}}
        <h1 class="text-display font-bold">Judul besar</h1>
        <h2 class="text-heading-1 font-bold">Judul halaman</h2>
        <p class="text-body text-gray-500">Paragraf isi konten.</p>
        <span class="text-caption font-bold uppercase">Label kecil</span>
        BLADE;
    // Saat font alternatif dipilih, tunjukkan juga cara memakainya.
    $semanticFor = fn (string $key) => ($key === 'sans' ? '' : "{{-- Pasang {$fonts[$key]['cls']} di pembungkus, semua teks di dalamnya ikut {$fonts[$key]['name']} --}}\n<main class=\"{$fonts[$key]['cls']}\">\n    ...\n</main>\n\n").$semantic;
    $code = fn (string $c) => '<code class="text-xs font-bold text-gray-700">'.e($c).'</code>';

    // Judul dan deskripsi hero ikut berganti di browser; server merender font awalnya.
    $title = new HtmlString('<span x-text="fonts[font].name + \' type system\'">'.e($font['name']).' type system</span>');
    $description = new HtmlString('<span x-text="fonts[font].description">'.e($font['description']).'</span>');
@endphp

<x-docs.layout title="Typography">
    <div
        x-data="{
            font: @js($initial),
            fonts: @js($fonts),
            params: @js($params),
            init() {
                this.$watch('font', (font) => {
                    const url = new URL(location.href)
                    font === 'sans' ? url.searchParams.delete('font') : url.searchParams.set('font', this.params[font])
                    history.replaceState(history.state, '', url)
                })
            },
        }"
    >
        <x-docs.page
            eyebrow="Foundations · Typography"
            :title="$title"
            :description="$description"
            :toc="[
                'typeface' => 'Typeface & skala',
                'skala' => 'Skala ukuran',
                'weight' => 'Font weight',
                'leading' => 'Line height',
                'transform' => 'Transform & decoration',
                'font-alt' => 'Mengganti font',
                'prinsip' => 'Prinsip penggunaan',
            ]"
        >
            <x-slot:actions>
                <x-docs.segmented alpine label="Pratinjau font" model="font" :value="$initial" :options="[
                    ['value' => 'sans', 'label' => 'Lato · utama'],
                    ['value' => 'alt', 'label' => 'Inter · alternatif'],
                    ['value' => 'alt2', 'label' => 'Arial · alternatif 2'],
                ]" />
            </x-slot:actions>

            {{-- Seluruh isi ikut font terpilih supaya perbandingannya utuh. --}}
            <div @class(['space-y-16', $font['cls']]) x-bind:class="{ 'font-sans': font === 'sans', 'font-alt': font === 'alt', 'font-alt-2': font === 'alt2' }">
                <x-docs.section id="typeface" title="Typeface & skala semantik">
                    <x-docs.lead>
                        Token semantik memegang hierarki utama halaman — Display sampai Caption. Pakai ini lebih
                        dulu; skala Tailwind di bawahnya hanya untuk kebutuhan yang tidak tertampung di sini.
                    </x-docs.lead>

                    <article class="ds-card overflow-hidden">
                        <div class="grid gap-8 border-b border-border bg-primary-900 p-6 text-white sm:p-8 lg:grid-cols-[1fr_auto] lg:items-end">
                            <div>
                                <p x-text="fonts[font].role" class="text-xs font-bold tracking-[0.14em] text-primary-300 uppercase">{{ $font['role'] }}</p>
                                <p x-text="fonts[font].name" class="mt-3 text-[clamp(4.5rem,15vw,9rem)] leading-none font-black tracking-tight">{{ $font['name'] }}</p>
                            </div>
                            <div class="max-w-sm lg:text-right">
                                <p class="text-heading-4 font-bold">Aa Bb Cc 123</p>
                                <p class="mt-2 text-sm leading-6 text-primary-200">
                                    ABCDEFGHIJKLMNOPQRSTUVWXYZ
                                    <br>
                                    abcdefghijklmnopqrstuvwxyz
                                    <br>
                                    0123456789 !@#$%&amp;*
                                </p>
                            </div>
                        </div>

                        <div class="divide-y divide-border">
                            @foreach ($typeScale as [$name, $class, $spec, $sample])
                                <div class="grid gap-4 p-5 sm:p-7 lg:grid-cols-[180px_1fr] lg:items-baseline">
                                    <div>
                                        <p class="text-sm font-black text-gray-900">{{ $name }}</p>
                                        <p class="mt-1 text-xs text-gray-400">{{ $spec }}</p>
                                    </div>
                                    <p class="{{ $class }} text-gray-900">{{ $sample }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    {{-- Satu blok per font; hanya yang terpilih yang tampil. --}}
                    @foreach (array_keys($fonts) as $key)
                        <div x-show="font === @js($key)" @if ($key !== $initial) style="display: none" @endif>
                            <x-docs.code :code="$semanticFor($key)" />
                        </div>
                    @endforeach
                </x-docs.section>

                <x-docs.section id="skala" title="Skala ukuran Tailwind">
                    <x-docs.lead>
                        Skala {!! $code('text-xs') !!} hingga {!! $code('text-9xl') !!} untuk kebutuhan di luar
                        token semantik.
                    </x-docs.lead>

                    <article class="ds-card divide-y divide-border overflow-hidden">
                        @foreach ($tailwindScale as [$class, $label])
                            <div class="p-5 sm:p-6">
                                <div class="flex items-baseline gap-2">
                                    <code class="text-sm font-bold text-primary-700">.{{ $class }}</code>
                                    <span class="text-xs text-gray-400">{{ $label }}</span>
                                </div>
                                <div class="ds-scroll-x mt-3 overflow-x-auto">
                                    <p class="{{ $class }} font-normal whitespace-nowrap text-gray-900">The quick brown fox jumps over the lazy dog.</p>
                                </div>
                            </div>
                        @endforeach
                    </article>

                    <x-docs.code>
                        @verbatim
                        {{-- Skala Tailwind — untuk kebutuhan di luar token semantik --}}
                        <p class="text-xs">Teks 12px</p>
                        <p class="text-base">Teks 16px</p>
                        <p class="text-2xl">Teks 24px</p>
                        <p class="text-5xl">Teks 48px</p>
                        @endverbatim
                    </x-docs.code>
                </x-docs.section>

                <x-docs.section id="weight" title="Sembilan tingkat ketebalan">
                    <x-docs.lead>
                        Dari {!! $code('.font-thin') !!} (100) hingga {!! $code('.font-black') !!} (900).
                    </x-docs.lead>

                    <article class="ds-card divide-y divide-border overflow-hidden">
                        @foreach ($fontWeights as [$class, $value])
                            <div class="grid gap-2 p-5 sm:grid-cols-[1fr_auto] sm:items-center sm:p-6">
                                <p class="{{ $class }} text-4xl text-gray-900">Ceplok Design System</p>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 sm:justify-end">
                                    <code class="text-sm font-bold text-primary-700">.{{ $class }}</code>
                                    <code class="text-xs text-gray-400">font-weight: {{ $value }}</code>
                                </div>
                            </div>
                        @endforeach
                    </article>

                    <x-docs.code>
                        @verbatim
                        {{-- Sembilan tingkat ketebalan --}}
                        <p class="font-thin">Thin 100</p>
                        <p class="font-normal">Regular 400</p>
                        <p class="font-semibold">Semi Bold 600</p>
                        <p class="font-black">Black 900</p>
                        @endverbatim
                    </x-docs.code>
                </x-docs.section>

                <x-docs.section id="leading" title="Leading (jarak antarbaris)">
                    <x-docs.lead>Tiga langkah jarak antarbaris untuk kerapatan teks yang berbeda.</x-docs.lead>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($lineHeights as [$class, $value])
                            <article class="ds-card p-5">
                                <div class="flex items-center justify-between gap-2">
                                    <code class="text-sm font-bold text-primary-700">.{{ $class }}</code>
                                    <code class="text-xs text-gray-400">{{ $value }}</code>
                                </div>
                                <p class="{{ $class }} mt-3 text-sm text-gray-900">Layanan digital untuk semua masyarakat Indonesia yang inklusif dan mudah diakses.</p>
                            </article>
                        @endforeach
                    </div>

                    <x-docs.code>
                        @verbatim
                        {{-- Jarak antarbaris --}}
                        <p class="leading-none">Rapat — 100%</p>
                        <p class="leading-normal">Standar — 150%</p>
                        <p class="leading-loose">Renggang — 200%</p>
                        @endverbatim
                    </x-docs.code>
                </x-docs.section>

                <x-docs.section id="transform" title="Transform & decoration">
                    <x-docs.lead>Kapitalisasi, garis bawah, dan coret — dipakai hemat, hanya saat maknanya jelas.</x-docs.lead>

                    <article class="ds-card p-6">
                        <div class="space-y-5">
                            @foreach ($textTransforms as [$class, $description])
                                <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between gap-3">
                                        <code class="text-sm font-bold text-primary-700">.{{ $class }}</code>
                                        <code class="text-[11px] text-gray-400">{{ $description }}</code>
                                    </div>
                                    <p class="{{ $class }} mt-2 text-lg text-gray-900">The quick brown fox jumps over the lazy dog.</p>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    <x-docs.code>
                        @verbatim
                        {{-- Transform & decoration --}}
                        <p class="uppercase">huruf kapital semua</p>
                        <p class="line-through">Teks dicoret</p>
                        <a href="#" class="underline">Tautan bergaris bawah</a>
                        @endverbatim
                    </x-docs.code>
                </x-docs.section>

                <x-docs.section id="font-alt" title="Mengganti font">
                    <x-docs.lead>
                        Lato dipakai lewat {!! $code('font-sans') !!}, Inter lewat {!! $code('font-alt') !!}, dan Arial
                        lewat {!! $code('font-alt-2') !!}. Untuk memakai font lain, timpa variabel
                        {!! $code('--font-alt') !!} atau {!! $code('--font-alt-2') !!} di CSS project.
                    </x-docs.lead>

                    <x-docs.code label="CSS">
                        @verbatim
                        /* Ganti font alternatif di CSS project kamu.
                           Muat file fontnya sendiri, misalnya lewat Google Fonts. */
                        :root {
                            --font-alt: 'Plus Jakarta Sans', sans-serif;   /* bawaan: Inter */
                            --font-alt-2: 'Verdana', sans-serif;           /* bawaan: Arial */
                        }
                        @endverbatim
                    </x-docs.code>
                </x-docs.section>

                <x-docs.section id="prinsip" title="Prinsip penggunaan">
                    <x-docs.principles>
                        <x-docs.principle>Gunakan <strong class="text-gray-900">Bold atau Black</strong> untuk heading dan angka penting.</x-docs.principle>
                        <x-docs.principle>Pertahankan body minimal <strong class="text-gray-900">16px</strong> untuk konten utama.</x-docs.principle>
                        <x-docs.principle>Hindari paragraf panjang dengan huruf kapital karena menurunkan keterbacaan.</x-docs.principle>
                        <x-docs.principle>Batasi panjang baris ideal antara <strong class="text-gray-900">45–75 karakter</strong>.</x-docs.principle>
                    </x-docs.principles>
                </x-docs.section>
            </div>
        </x-docs.page>
    </div>
</x-docs.layout>
