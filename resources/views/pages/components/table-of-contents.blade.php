@php
    // Contoh isi daftar, mengikuti daftar isi halaman Hero di desain.
    $contoh = [
        ['id' => 'demo-dasar', 'label' => 'Tampilan dasar'],
        ['id' => 'demo-variasi', 'label' => 'Variasi'],
        ['id' => 'demo-platform', 'label' => 'platform'],
        ['id' => 'demo-heading', 'label' => 'heading · sub-heading · description'],
        ['id' => 'demo-button', 'label' => 'show-button · button-label · button-href'],
        ['id' => 'demo-dark', 'label' => 'Dark mode'],
        ['id' => 'demo-props', 'label' => 'Properties'],
    ];

    // Bagian isi untuk demo wadah gulir.
    $bagianGulir = collect(['Pendahuluan', 'Persyaratan', 'Pemasangan', 'Konfigurasi', 'Tema gelap', 'Aksesibilitas', 'Tanya jawab'])
        ->map(fn (string $label, int $i) => ['id' => "gulir-{$i}", 'label' => $label])
        ->all();

    // Daftar panjang untuk demo max-height.
    $daftarPanjang = array_map(fn (int $i) => ['id' => "panjang-{$i}", 'label' => 'Bagian '.($i + 1)], range(0, 23));

    $props = [
        ['items', 'array', '[]', "Daftar bagian: ['id' => …, 'label' => …, 'href' => …]. id sama dengan id elemen bagian yang dituju; href opsional."],
        ['title', 'string', '"On this page"', 'Judul di atas daftar. Isi :title="false" untuk menyembunyikan (padanan title={null} di React).'],
        ['active-id', 'string', '—', 'Mode terkontrol: entri yang disorot. Tanpa prop ini scroll-spy yang menentukan.'],
        ['wire:model', 'string', '—', 'Mengikat id entri yang disorot ke properti Livewire. x-model juga bisa.'],
        ['active-change', 'event', '—', 'Dikirim saat scroll-spy atau klik memindahkan sorotan, dengan $event.detail.id. Padanan onActiveChange.'],
        ['scroll-spy', 'bool', 'true', 'Menyorot otomatis bagian yang sedang dibaca.'],
        ['offset', 'int', '96', 'Jarak (px) dari tepi atas layar tempat bagian dianggap mulai dibaca, mis. tinggi navbar.'],
        ['get-href', 'string | Closure', '"#{id}"', 'Membentuk alamat tiap entri: pola dengan {id}, mis. "#/panduan#{id}", atau fn ($id) => …. Padanan getHref.'],
        ['item-click', 'event', '—', 'Dikirim saat entri diklik, sebelum tautan berjalan, dengan $event.detail.id. x-on:item-click.prevent membatalkan tautannya. Padanan onItemClick.'],
        ['dark-mode', 'bool', 'false', 'Sama dengan data-theme="dark". Latar jadi gray-800.'],
        ['max-height', 'int | string', '—', 'Tinggi maksimum daftar; angka berarti px, mis. 256 atau "calc(100vh - 8rem)". Daftar yang lebih panjang bisa digulir dan mengikuti entri aktif.'],
        ['anchor', 'bool', 'true', 'Entri berupa tautan <a href> yang melompat ke bagiannya, dengan scroll-spy. false menjadikan entri <button>: klik hanya memindahkan sorotan, tanpa berpindah atau menggulir.'],
        ['scroll-container', 'string', '—', 'Selector CSS wadah gulir isi halaman bila bukan window, mis. "#panel". Scroll-spy dan klik entri bekerja di dalam wadah ini. Padanan scrollContainer.'],
        ['…atribut', '—', '—', 'Atribut lain diteruskan ke elemen <nav>, termasuk class dan aria-label.'],
    ];
@endphp

<x-docs.layout title="Table of Contents">
    <x-docs.page
        eyebrow="Components · Table of Contents"
        title="Table of Contents"
        description="Daftar isi “On this page” untuk halaman panjang. Menyorot bagian yang sedang dibaca secara otomatis."
        :toc="[
            'tampilan-dasar' => 'Tampilan dasar',
            'dark-mode' => 'Dark mode',
            'scrollable' => 'Scrollable',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="tampilan-dasar" title="Tampilan dasar">
            <x-docs.lead>
                Daftar isi duduk di kanan isi halaman, lebar 208px dengan teks 12px. Entri aktif ditandai
                garis brand 2px. Klik entri di contoh ini: sorotannya pindah, dan bagian yang sesuai di
                halaman ikut ditandai.
            </x-docs.lead>
            <x-docs.lead>
                Di kit React, layar di bawah 1024px memakai tombol yang membuka Drawer dari kanan. Kit
                Livewire baru menyediakan daftarnya, karena Drawer belum di-port. Di layar sempit, letakkan
                daftar di atas isi halaman, seperti contoh ini di ponsel, atau sembunyikan.
            </x-docs.lead>

            {{-- Tiruan halaman dokumentasi: bagian yang aktif di daftar isi ikut ditandai di isinya. --}}
            <div x-data="{ aktif: 'demo-dasar' }" class="mb-4 overflow-hidden rounded-2xl border border-border bg-toc-surface shadow-soft">
                <div class="flex flex-col-reverse gap-6 p-5 sm:flex-row sm:gap-8 sm:p-8">
                    <div class="min-w-0 flex-1" aria-hidden="true">
                        <p class="text-[11px] font-bold tracking-[0.14em] text-content-disabled uppercase">Components</p>
                        <p class="mt-1 text-heading-3 font-black text-content">Hero</p>
                        <div class="mt-2 h-2 w-3/4 rounded bg-border"></div>

                        <div class="mt-6 space-y-2">
                            @foreach ($contoh as $item)
                                <div
                                    class="rounded-lg border-l-2 px-3 py-2.5 transition-colors duration-300"
                                    x-bind:class="aktif === @js($item['id']) ? 'border-brand bg-primary-50' : 'border-transparent'"
                                >
                                    <p
                                        class="truncate text-sm font-bold transition-colors duration-300"
                                        x-bind:class="aktif === @js($item['id']) ? 'text-brand' : 'text-content'"
                                    >{{ $item['label'] }}</p>
                                    <div class="mt-2 space-y-1.5">
                                        <div class="h-1.5 w-full rounded bg-border"></div>
                                        <div class="h-1.5 w-4/6 rounded bg-border"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Demo tidak boleh berpindah halaman; kliknya hanya memindahkan sorotan. --}}
                    <x-ceplok::table-of-contents
                        class="shrink-0 sm:sticky sm:top-0"
                        :items="$contoh"
                        active-id="demo-dasar"
                        :scroll-spy="false"
                        x-model="aktif"
                        x-on:item-click.prevent="aktif = $event.detail.id"
                    />
                </div>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::table-of-contents
                    class="sticky top-10"
                    :⟦items⟧="[
                        ['id' => 'dasar', 'label' => 'Tampilan dasar'],
                        ['id' => 'variasi', 'label' => 'Variasi'],
                    ]"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Hanya palet warnanya yang berubah: latar jadi <span class="text-primary-500">gray-800</span>, teks dan garis ikut token semantik.
                Pakai <span class="text-primary-500">dark-mode</span>, atau letakkan di dalam pembungkus
                <span class="text-primary-500">data-theme="dark"</span>.
            </x-docs.lead>

            <div class="mb-4 grid gap-4 md:grid-cols-2">
                @foreach (['light' => 'Light', 'dark' => 'Dark'] as $theme => $label)
                    <article>
                        <h3 class="mb-3 text-sm font-black text-gray-900">{{ $label }}</h3>
                        <div data-theme="{{ $theme }}" class="rounded-2xl border border-border bg-surface-subtle p-6">
                            <x-ceplok::table-of-contents :items="$contoh" :anchor="false" :dark-mode="$theme === 'dark'" />
                        </div>
                    </article>
                @endforeach
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::table-of-contents ⟦dark-mode⟧ :items="$items" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="scrollable" title="Scrollable">
            <x-docs.lead>
                Bila isi halaman digulir di dalam panel, bukan window, berikan selector panelnya lewat
                <span class="text-primary-500">scroll-container</span>. Gulir panel di bawah: sorotan ikut berpindah, dan klik entri
                menggulir panelnya.
            </x-docs.lead>

            <div class="flex flex-col gap-4 overflow-hidden rounded-2xl border border-border bg-surface-subtle p-4 sm:flex-row sm:gap-6 sm:p-6">
                <div id="toc-gulir-panel" class="order-last h-80 min-w-0 flex-1 space-y-8 overflow-y-auto rounded-xl border border-border bg-white p-6 sm:order-none">
                    @foreach ($bagianGulir as $bagian)
                        <section id="{{ $bagian['id'] }}">
                            <h3 class="text-heading-4 font-black text-gray-900">{{ $bagian['label'] }}</h3>
                            <p class="mt-2 text-body-sm leading-6 text-gray-500">
                                Contoh isi bagian {{ $bagian['label'] }}. Paragraf ini sengaja dibuat cukup panjang supaya
                                panelnya bisa digulir dan perpindahan sorotan di daftar isi terlihat jelas saat
                                menggulir ke bawah maupun ke atas.
                            </p>
                            <div class="mt-3 h-24 rounded-lg bg-gray-50"></div>
                        </section>
                    @endforeach
                </div>
                <x-ceplok::table-of-contents class="shrink-0" :items="$bagianGulir" scroll-container="#toc-gulir-panel" :offset="24" />
            </div>

            <h3 class="mt-8 text-sm font-black text-gray-900">Daftar panjang</h3>
            <p class="mt-1 mb-3 max-w-2xl text-body-sm text-gray-500">
                Dengan <span class="text-primary-500">max-height</span>, daftar yang panjang bisa digulir sendiri dan entri aktif tetap
                terlihat. Contoh ini menyorot Bagian 19; klik entri lain dan judul isi halaman ikut berganti.
            </p>

            {{-- Tiruan halaman desktop: judul isi mengikuti entri aktif di daftar yang digulir. --}}
            <div
                x-data="{ aktif: 'panjang-18', get judul() { return 'Bagian ' + (Number(this.aktif.split('-')[1]) + 1) } }"
                class="mb-4 overflow-hidden rounded-2xl border border-border bg-toc-surface shadow-soft"
            >
                <div class="flex items-center justify-between border-b border-border px-5 py-3 sm:px-8" aria-hidden="true">
                    <div class="h-3 w-24 rounded bg-border-strong"></div>
                    <div class="hidden gap-4 sm:flex">
                        <div class="h-2 w-12 rounded bg-border"></div>
                        <div class="h-2 w-12 rounded bg-border"></div>
                        <div class="h-2 w-12 rounded bg-border"></div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-6 p-5 sm:flex-row sm:gap-8 sm:p-8">
                    <div class="min-w-0 flex-1" aria-hidden="true">
                        <p class="text-[11px] font-bold tracking-[0.14em] text-content-disabled uppercase">Panduan</p>
                        <p class="mt-1 text-heading-3 font-black text-content">Dokumentasi panjang</p>
                        <div class="mt-2 h-2 w-3/4 rounded bg-border"></div>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-lg border-l-2 border-brand bg-primary-50 px-3 py-2.5">
                                <p class="truncate text-sm font-bold text-brand" x-text="judul">Bagian 19</p>
                                <div class="mt-2 space-y-1.5">
                                    <div class="h-1.5 w-full rounded bg-border"></div>
                                    <div class="h-1.5 w-5/6 rounded bg-border"></div>
                                    <div class="h-1.5 w-4/6 rounded bg-border"></div>
                                </div>
                            </div>
                            <div class="h-28 rounded-lg bg-border"></div>
                            <div class="space-y-1.5 px-3">
                                <div class="h-1.5 w-full rounded bg-border"></div>
                                <div class="h-1.5 w-11/12 rounded bg-border"></div>
                                <div class="h-1.5 w-3/6 rounded bg-border"></div>
                            </div>
                        </div>
                    </div>

                    <x-ceplok::table-of-contents
                        class="shrink-0 sm:sticky sm:top-0"
                        :items="$daftarPanjang"
                        :max-height="256"
                        active-id="panjang-18"
                        :scroll-spy="false"
                        x-model="aktif"
                        x-on:item-click.prevent="aktif = $event.detail.id"
                    />
                </div>
            </div>

            <x-docs.code>
                @verbatim
                <div id="panel" class="h-80 overflow-y-auto">…</div>
                <x-ceplok::table-of-contents :items="$items" ⟦scroll-container⟧="#panel" :⟦offset⟧="24" />

                <x-ceplok::table-of-contents :items="$items" ⟦max-height⟧="calc(100vh - 8rem)" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.table-of-contents />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Id bagian yang sedang disorot bisa diikat ke properti komponen lewat <span class="text-primary-500">wire:model</span>,
                padanan activeId + onActiveChange di React. Untuk menanggapi perpindahannya sendiri, dengarkan
                event <span class="text-primary-500">active-change</span>; id-nya ada di <span class="text-primary-500">$event.detail.id</span>.
                Event <span class="text-primary-500">item-click</span> dikirim sebelum tautan berjalan dan bisa dibatalkan, padanan onItemClick.
            </x-docs.lead>
            <x-docs.code>
                @verbatim
                {{-- Bagian yang sedang dibaca ikut tersimpan di properti $bagian --}}
                <x-ceplok::table-of-contents :items="$items" ⟦wire:model⟧="bagian" />

                {{-- Menanggapi perpindahan sorotan sendiri --}}
                <x-ceplok::table-of-contents :items="$items" ⟦wire:active-change⟧="catat($event.detail.id)" />

                {{-- Membatalkan tautan dan memilih bagiannya sendiri --}}
                <x-ceplok::table-of-contents
                    :items="$items"
                    :active-id="$bagian"
                    ⟦wire:item-click.prevent⟧="$set('bagian', $event.detail.id)"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500">
                Referensi semua prop dan event yang tersedia pada komponen Table of Contents.
            </p>
            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
