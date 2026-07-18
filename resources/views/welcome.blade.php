@php
    $palettes = [
        'primary' => [
            'name' => 'Biru — Primary',
            'description' => 'Identitas utama aplikasi Komdigi',
            'colors' => [900 => '#233876', 800 => '#1E429F', 700 => '#1A56DB', 600 => '#1C64F2', 500 => '#3F83F8', 400 => '#76A9FA', 300 => '#A4CAFE', 200 => '#C3DDFD', 100 => '#E1EFFE', 50 => '#EBF5FF'],
        ],
        'gray' => [
            'name' => 'Abu-abu — Neutral',
            'description' => 'Teks, border, dan permukaan antarmuka',
            'colors' => [900 => '#111827', 800 => '#1F2937', 700 => '#374151', 600 => '#4B5563', 500 => '#6B7280', 400 => '#9CA3AF', 300 => '#D1D5DB', 200 => '#E5E7EB', 100 => '#F3F4F6', 50 => '#F9FAFB'],
        ],
        'danger' => [
            'name' => 'Merah — Danger',
            'description' => 'Error dan tindakan destruktif',
            'colors' => [900 => '#771D1D', 800 => '#9B1C1C', 700 => '#C81E1E', 600 => '#E02424', 500 => '#F05252', 400 => '#F98080', 300 => '#F8B4B4', 200 => '#FBD5D5', 100 => '#FDE8E8', 50 => '#FDF2F2'],
        ],
        'orange' => [
            'name' => 'Orange — Alert',
            'description' => 'Peringatan yang memerlukan perhatian',
            'colors' => [900 => '#771D1D', 800 => '#8A2C0D', 700 => '#B43403', 600 => '#D03801', 500 => '#FF5A1F', 400 => '#FF8A4C', 300 => '#FDBA8C', 200 => '#FCD9BD', 100 => '#FEECDC', 50 => '#FFF8F1'],
        ],
        'warning' => [
            'name' => 'Kuning — Warning',
            'description' => 'Border dan aksen pada peringatan',
            'colors' => [900 => '#633112', 800 => '#723B13', 700 => '#8E4B10', 600 => '#9F580A', 500 => '#C27803', 400 => '#E3A008', 300 => '#FACA15', 200 => '#FCE96A', 100 => '#FDF6B2', 50 => '#FDFDEA'],
        ],
        'success' => [
            'name' => 'Hijau — Success',
            'description' => 'Status proses berhasil dan aplikasi Digika',
            'colors' => [900 => '#014737', 800 => '#03543F', 700 => '#046C4E', 600 => '#057A55', 500 => '#0E9F6E', 400 => '#31C48D', 300 => '#84E1BC', 200 => '#BCF0DA', 100 => '#DEF7EC', 50 => '#F3FAF7'],
        ],
        'purple' => [
            'name' => 'Ungu — Simaya',
            'description' => 'Identitas dan aksen produk Simaya',
            'colors' => [900 => '#4A1D96', 800 => '#5521B5', 700 => '#6C2BD9', 600 => '#7E3AF2', 500 => '#9061F9', 400 => '#AC94FA', 300 => '#CABFFD', 200 => '#DCD7FE', 100 => '#EDEBFE', 50 => '#F6F5FF'],
        ],
    ];

    $typeScale = [
        ['name' => 'Display', 'class' => 'text-display font-bold', 'spec' => '48 / 55 · Bold', 'sample' => 'Layanan digital untuk semua'],
        ['name' => 'Heading 1', 'class' => 'text-heading-1 font-bold', 'spec' => '36 / 43 · Bold', 'sample' => 'Transformasi digital Indonesia'],
        ['name' => 'Heading 2', 'class' => 'text-heading-2 font-bold', 'spec' => '30 / 38 · Bold', 'sample' => 'Informasi dan layanan publik'],
        ['name' => 'Heading 3', 'class' => 'text-heading-3 font-bold', 'spec' => '24 / 31 · Bold', 'sample' => 'Akses mudah dan terpercaya'],
        ['name' => 'Heading 4', 'class' => 'text-heading-4 font-bold', 'spec' => '20 / 28 · Bold', 'sample' => 'Pelayanan dalam satu tempat'],
        ['name' => 'Body Large', 'class' => 'text-body-lg font-normal', 'spec' => '18 / 29 · Regular', 'sample' => 'Kami menghadirkan layanan digital yang inklusif, aman, dan mudah digunakan.'],
        ['name' => 'Body', 'class' => 'text-body font-normal', 'spec' => '16 / 24 · Regular', 'sample' => 'Gunakan bahasa yang ringkas agar informasi dapat dipahami oleh seluruh masyarakat.'],
        ['name' => 'Body Small', 'class' => 'text-body-sm font-normal', 'spec' => '14 / 20 · Regular', 'sample' => 'Informasi pendukung, deskripsi singkat, dan label komponen.'],
        ['name' => 'Caption', 'class' => 'text-caption font-bold tracking-wide uppercase', 'spec' => '12 / 17 · Bold', 'sample' => 'Terakhir diperbarui 18 Juli 2026'],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Fondasi color token dan typography Komdigi Design System.">

        <title>Foundations — Komdigi Design System</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body x-data="designSystem" class="min-h-screen">
        <div class="min-h-screen lg:grid lg:grid-cols-[264px_1fr]">
            <aside class="border-b border-border bg-white lg:fixed lg:inset-y-0 lg:w-66 lg:border-r lg:border-b-0">
                <div class="flex h-17 items-center justify-between px-5 lg:h-20 lg:px-7">
                    <a href="#top" class="flex items-center gap-3" aria-label="Komdigi Design System">
                        <span class="grid size-9 grid-cols-2 gap-0.5 rounded-lg bg-primary-700 p-2 shadow-sm">
                            <span class="rounded-sm bg-white"></span><span class="rounded-sm bg-primary-300"></span>
                            <span class="rounded-sm bg-primary-300"></span><span class="rounded-sm bg-white"></span>
                        </span>
                        <span>
                            <span class="block text-sm font-black tracking-[0.18em] text-gray-900">KOMDIGI</span>
                            <span class="block text-[10px] font-bold tracking-wide text-gray-500 uppercase">Design System</span>
                        </span>
                    </a>

                    <button @click="mobileMenu = !mobileMenu" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100 lg:hidden" :aria-expanded="mobileMenu" aria-label="Buka navigasi">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </button>
                </div>

                <nav :class="mobileMenu ? 'block' : 'hidden'" class="border-t border-border px-4 py-4 lg:block lg:border-0 lg:px-5 lg:py-5" aria-label="Navigasi foundations">
                    <p class="mb-2 px-3 text-[11px] font-black tracking-[0.14em] text-gray-400 uppercase">Foundations</p>
                    <button @click="showSection('colors')" class="ds-nav-link w-full" :class="section === 'colors' && 'is-active'">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3.5c3.8 3.2 6.5 6 6.5 9.5a6.5 6.5 0 1 1-13 0c0-3.5 2.7-6.3 6.5-9.5Z"/></svg>
                        Color tokens
                    </button>
                    <button @click="showSection('typography')" class="ds-nav-link mt-1 w-full" :class="section === 'typography' && 'is-active'">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 6V4h14v2M12 4v16m-4 0h8"/></svg>
                        Typography
                    </button>

                    <div class="mt-8 hidden rounded-xl bg-gray-50 p-4 lg:block">
                        <p class="text-xs font-bold text-gray-900">Foundation v1.0</p>
                        <p class="mt-1 text-xs leading-5 text-gray-500">Laravel · Alpine.js<br>Tailwind CSS v4</p>
                    </div>
                </nav>
            </aside>

            <main id="top" class="lg:col-start-2">
                <header class="border-b border-border bg-white/90 px-5 py-10 backdrop-blur sm:px-8 lg:px-12 lg:py-14 xl:px-16">
                    <div class="mx-auto max-w-6xl">
                        <p class="ds-eyebrow">Komdigi Design System · Foundations</p>
                        <div class="mt-3 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                            <div>
                                <h1 class="text-heading-1 font-black tracking-tight text-gray-900">Color Token & Typography</h1>
                                <p class="mt-3 max-w-2xl text-body text-gray-500">Satu sumber visual yang konsisten untuk membangun layanan digital Komdigi yang jelas, inklusif, dan mudah dikenali.</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-bold text-gray-500">
                                <span class="size-2 rounded-full bg-success-500"></span>
                                Ready to use
                                <span class="text-gray-300">•</span>
                                v1.0.0
                            </div>
                        </div>
                    </div>
                </header>

                <div class="mx-auto max-w-6xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-16">
                    <section x-show="section === 'colors'" x-cloak aria-labelledby="color-heading">
                        <div class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                            <div>
                                <p class="ds-eyebrow">01 · Color</p>
                                <h2 id="color-heading" class="mt-2 text-heading-2 font-black text-gray-900">Primitive color tokens</h2>
                                <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Klik warna untuk menyalin nilai HEX. Gunakan skala primitive untuk membentuk semantic token, bukan langsung untuk menentukan makna.</p>
                            </div>
                            <span class="w-fit rounded-full border border-primary-200 bg-primary-50 px-3 py-1.5 text-xs font-bold text-primary-700">7 palette · 70 tokens</span>
                        </div>

                        <div class="space-y-5">
                            @foreach ($palettes as $key => $palette)
                                <article class="ds-card overflow-hidden p-4 sm:p-6">
                                    <div class="mb-4 flex flex-col justify-between gap-1 sm:flex-row sm:items-end">
                                        <div>
                                            <h3 class="text-lg font-black text-gray-900">{{ $palette['name'] }}</h3>
                                            <p class="mt-0.5 text-sm text-gray-500">{{ $palette['description'] }}</p>
                                        </div>
                                        <code class="text-xs font-bold text-gray-400">colors.{{ $key }}</code>
                                    </div>

                                    <div class="grid grid-cols-2 overflow-hidden rounded-xl sm:grid-cols-5 lg:grid-cols-10">
                                        @foreach ($palette['colors'] as $shade => $hex)
                                            <button
                                                @click="copyToken('{{ $hex }}', '{{ $key }}-{{ $shade }}')"
                                                class="group relative flex min-h-24 flex-col justify-between p-3 text-left transition-transform hover:z-10 hover:-translate-y-1 focus:z-10 sm:min-h-28"
                                                style="background-color: {{ $hex }}"
                                                aria-label="Salin {{ $key }} {{ $shade }}, {{ $hex }}"
                                            >
                                                <span class="text-sm font-black {{ $shade >= 500 ? 'text-white' : 'text-gray-900' }}">{{ $shade }}</span>
                                                <span class="text-[10px] font-bold tracking-wide {{ $shade >= 500 ? 'text-white/80' : 'text-gray-700' }}">{{ $hex }}</span>
                                                <span x-show="copied === '{{ $key }}-{{ $shade }}'" x-transition class="absolute inset-0 grid place-items-center bg-gray-900/80 text-xs font-bold text-white">Tersalin!</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-12">
                            <p class="ds-eyebrow">Semantic layer</p>
                            <h2 class="mt-2 text-heading-3 font-black text-gray-900">Tokens berdasarkan fungsi</h2>
                            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                @foreach ([
                                    ['Brand', 'Aksi dan identitas utama', '#1C64F2', 'bg-brand', 'text-white'],
                                    ['Error', 'Error dan aksi destruktif', '#E02424', 'bg-feedback-error', 'text-white'],
                                    ['Warning', 'Status perlu perhatian', '#FACA15', 'bg-feedback-warning', 'text-gray-900'],
                                    ['Success', 'Proses berhasil', '#057A55', 'bg-feedback-success', 'text-white'],
                                ] as [$name, $description, $hex, $class, $textClass])
                                    <div class="ds-card p-4">
                                        <div class="{{ $class }} {{ $textClass }} grid size-11 place-items-center rounded-xl">
                                            <span class="size-3 rounded-full border-2 border-current"></span>
                                        </div>
                                        <h3 class="mt-4 text-sm font-black text-gray-900">{{ $name }}</h3>
                                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ $description }}</p>
                                        <code class="mt-3 block text-[11px] font-bold text-gray-400">{{ $hex }}</code>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-12 overflow-hidden rounded-2xl bg-gray-900 shadow-soft">
                            <div class="flex items-center justify-between border-b border-white/10 px-5 py-3">
                                <span class="text-xs font-bold text-gray-300">Contoh penggunaan</span>
                                <span class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Blade + Tailwind</span>
                            </div>
                            <pre class="overflow-x-auto p-5 text-sm leading-7 text-gray-300"><code>&lt;button class=&quot;<span class="text-primary-300">bg-brand hover:bg-brand-hover</span> text-white&quot;&gt;
    Simpan perubahan
&lt;/button&gt;

&lt;p class=&quot;<span class="text-success-300">text-feedback-success</span>&quot;&gt;Data berhasil disimpan&lt;/p&gt;</code></pre>
                        </div>
                    </section>

                    <section x-show="section === 'typography'" x-cloak aria-labelledby="typography-heading">
                        <div class="mb-8">
                            <p class="ds-eyebrow">02 · Typography</p>
                            <h2 id="typography-heading" class="mt-2 text-heading-2 font-black text-gray-900">Lato type system</h2>
                            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">Lato dipilih karena terbuka, humanis, dan tetap terbaca baik pada layar kecil. Skala berikut menjaga hierarki tetap konsisten di seluruh produk.</p>
                        </div>

                        <article class="ds-card overflow-hidden">
                            <div class="grid gap-8 border-b border-border bg-primary-900 p-6 text-white sm:p-8 lg:grid-cols-[1fr_auto] lg:items-end">
                                <div>
                                    <p class="text-xs font-bold tracking-[0.14em] text-primary-300 uppercase">Primary typeface</p>
                                    <p class="mt-3 text-[clamp(4.5rem,15vw,9rem)] leading-none font-black tracking-tight">Lato</p>
                                </div>
                                <div class="max-w-sm lg:text-right">
                                    <p class="text-heading-4 font-bold">Aa Bb Cc 123</p>
                                    <p class="mt-2 text-sm leading-6 text-primary-200">ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>abcdefghijklmnopqrstuvwxyz<br>0123456789 !@#$%&amp;*</p>
                                </div>
                            </div>

                            <div class="divide-y divide-border">
                                @foreach ($typeScale as $type)
                                    <div class="grid gap-4 p-5 sm:p-7 lg:grid-cols-[180px_1fr] lg:items-baseline">
                                        <div>
                                            <p class="text-sm font-black text-gray-900">{{ $type['name'] }}</p>
                                            <p class="mt-1 text-xs text-gray-400">{{ $type['spec'] }}</p>
                                        </div>
                                        <p class="{{ $type['class'] }} text-gray-900">{{ $type['sample'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </article>

                        <div class="mt-8 grid gap-5 lg:grid-cols-2">
                            <article class="ds-card p-6">
                                <p class="ds-eyebrow">Font weights</p>
                                <div class="mt-5 space-y-5">
                                    @foreach ([300 => 'Light', 400 => 'Regular', 700 => 'Bold', 900 => 'Black'] as $weight => $label)
                                        <div class="flex items-baseline justify-between gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                            <span class="text-xl text-gray-900" style="font-weight: {{ $weight }}">{{ $label }}</span>
                                            <code class="text-xs text-gray-400">{{ $weight }}</code>
                                        </div>
                                    @endforeach
                                </div>
                            </article>

                            <article class="ds-card p-6">
                                <p class="ds-eyebrow">Prinsip penggunaan</p>
                                <ul class="mt-5 space-y-4 text-sm leading-6 text-gray-600">
                                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Gunakan <strong class="text-gray-900">Bold atau Black</strong> untuk heading dan angka penting.</span></li>
                                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Pertahankan body minimal <strong class="text-gray-900">16px</strong> untuk konten utama.</span></li>
                                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Hindari paragraf panjang dengan huruf kapital karena menurunkan keterbacaan.</span></li>
                                    <li class="flex gap-3"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-primary-600"></span><span>Batasi panjang baris ideal antara <strong class="text-gray-900">45–75 karakter</strong>.</span></li>
                                </ul>
                            </article>
                        </div>
                    </section>
                </div>

                <footer class="border-t border-border bg-white px-5 py-6 text-center text-xs text-gray-400 sm:px-8 lg:px-12">
                    Komdigi Design System · Foundations · Dibangun dengan Laravel, Alpine.js, dan Tailwind CSS
                </footer>
            </main>
        </div>
    </body>
</html>
