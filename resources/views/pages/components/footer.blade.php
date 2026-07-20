@php
    $allMenus = [
        ['label' => 'Menu 1', 'url' => '#'],
        ['label' => 'Menu 2', 'url' => '#'],
        ['label' => 'Menu 3', 'url' => '#'],
        ['label' => 'Menu 4', 'url' => '#'],
        ['label' => 'Menu 5', 'url' => '#'],
    ];

    $socials = [
        ['label' => 'Instagram', 'url' => '#', 'icon' => 'icons/instagram.svg'],
        ['label' => 'X', 'url' => '#', 'icon' => 'icons/x.svg'],
        ['label' => 'Facebook', 'url' => '#', 'icon' => 'icons/facebook.svg'],
    ];

    $props = [
        ['logo', 'string|null', 'null', 'URL gambar logo. Kalau kosong, isi lewat slot default.'],
        ['logoAlt', 'string', 'Logo', 'Teks alternatif untuk logo.'],
        ['menus', 'array', '[]', 'Daftar tautan: [[\'label\' => .., \'url\' => ..], ..]'],
        ['copyright', 'string|null', 'null', 'Teks hak cipta di baris bawah.'],
        ['socials', 'array', '[]', 'Ikon sosial. icon = path SVG relatif terhadap public/'],
    ];
@endphp

<x-docs-layout section="components" title="Footer">
    <x-doc-hero eyebrow="Components" title="Footer" description="Penutup halaman berisi logo, navigasi ringkas, hak cipta, dan tautan media sosial. Tata letaknya mengikuti lebar footer itu sendiri, bukan lebar layar." />

    <div class="mx-auto max-w-5xl space-y-10 px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <section x-data="{ view: 'desktop', menuCount: 4 }">
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Preview</h2>

            {{-- Area preview --}}
            <div class="rounded-2xl border border-border bg-surface-subtle p-4 sm:p-6">
                <div class="mx-auto overflow-hidden rounded-xl shadow-soft transition-[max-width] duration-300 ease-out"
                     :class="view === 'mobile' ? 'max-w-[390px]' : 'max-w-full'">
                    @foreach ([2, 3, 4, 5] as $count)
                        <div x-show="menuCount === {{ $count }}" x-cloak>
                            <x-dk::footer
                                :logo="asset('images/komdigi-logo.svg')"
                                logo-alt="Komdigi — Kementerian Komunikasi dan Digital"
                                :menus="array_slice($allMenus, 0, $count)"
                                copyright="© 2025 Kementerian Komunikasi dan Digital"
                                :socials="$socials"
                            />
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Kontrol --}}
            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Tampilan</span>
                    <div class="inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih lebar tampilan">
                        @foreach ([['mobile', 'Mobile', 'M7 3h10a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Zm4 15h2'], ['desktop', 'Desktop', 'M3 5h18v11H3V5Zm6 15h6m-3-4v4']] as [$value, $label, $icon])
                            <button type="button" @click="view = '{{ $value }}'"
                                    class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="view === '{{ $value }}' ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="view === '{{ $value }}'">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Jumlah menu</span>
                    <div class="inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih jumlah menu">
                        @foreach ([2, 3, 4, 5] as $count)
                            <button type="button" @click="menuCount = {{ $count }}"
                                    class="w-9 rounded-md py-1.5 text-sm font-bold transition-colors"
                                    :class="menuCount === {{ $count }} ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="menuCount === {{ $count }}">{{ $count }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <p class="mt-3 text-body-sm text-gray-500">
                Tata letak berubah otomatis: bertumpuk saat sempit, satu baris saat lebar.
                Karena komponen memakai <em>container query</em>, preview di atas benar-benar berganti susunan, bukan sekadar mengecil.
            </p>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Perilaku responsif</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ([
                    ['Sempit (&lt; 768px)', 'Logo, menu, hak cipta, dan ikon sosial tersusun bertumpuk. Menu membungkus ke baris berikutnya bila ruang kurang.'],
                    ['Lebar (≥ 768px)', 'Logo di kiri dengan menu di tengah pada satu baris. Hak cipta di kiri, ikon sosial di kanan.'],
                ] as [$title, $desc])
                    <article class="ds-card p-5">
                        <h3 class="text-sm font-black text-gray-900">{!! $title !!}</h3>
                        <p class="mt-1.5 text-body-sm leading-6 text-gray-500">{{ $desc }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Penggunaan</h2>
            <div class="ds-card overflow-hidden">
                <pre class="ds-scroll-x overflow-x-auto bg-gray-900 p-5 text-xs leading-6 text-gray-300"><code>&lt;x-dk::footer
    :logo="asset('images/komdigi-logo.svg')"
    logo-alt="Komdigi"
    :menus="[
        ['label' =&gt; 'Menu 1', 'url' =&gt; '/menu-1'],
        ['label' =&gt; 'Menu 2', 'url' =&gt; '/menu-2'],
    ]"
    copyright="© 2025 Kementerian Komunikasi dan Digital"
    :socials="[
        ['label' =&gt; 'Instagram', 'url' =&gt; 'https://instagram.com/komdigi', 'icon' =&gt; '<span class="text-primary-300">icons/instagram.svg</span>'],
        ['label' =&gt; 'X',         'url' =&gt; 'https://x.com/komdigi',         'icon' =&gt; '<span class="text-primary-300">icons/x.svg</span>'],
        ['label' =&gt; 'Facebook',  'url' =&gt; '#',                            'icon' =&gt; '<span class="text-primary-300">icons/facebook.svg</span>'],
    ]"
/&gt;</code></pre>
            </div>
            <p class="mt-3 text-body-sm text-gray-500">Berkas SVG disisipkan inline, sehingga warnanya mengikuti <code class="text-xs font-bold text-gray-700">currentColor</code> dan bisa berubah saat hover.</p>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Properties</h2>
            <div class="ds-card overflow-hidden">
                <div class="ds-scroll-x overflow-x-auto">
                    <table class="w-full min-w-[40rem] text-left text-sm">
                        <thead class="border-b border-border bg-surface-subtle text-xs font-black tracking-wide text-gray-500 uppercase">
                            <tr>
                                <th class="px-5 py-3">Prop</th>
                                <th class="px-5 py-3">Tipe</th>
                                <th class="px-5 py-3">Default</th>
                                <th class="px-5 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($props as [$name, $type, $default, $desc])
                                <tr>
                                    <td class="px-5 py-3"><code class="text-xs font-bold text-primary-700">{{ $name }}</code></td>
                                    <td class="px-5 py-3 text-gray-500">{{ $type }}</td>
                                    <td class="px-5 py-3"><code class="text-xs text-gray-500">{{ $default }}</code></td>
                                    <td class="px-5 py-3 text-gray-600">{{ $desc }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-docs-layout>
