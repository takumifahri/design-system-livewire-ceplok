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
        ['layout', "'auto' | 'list' | 'drawer'", "'auto'", 'auto: daftar biasa (teks 12px) mulai 1024px, di bawahnya tombol yang membuka Drawer dari kanan (teks 14px). list dan drawer memaksa salah satunya.'],
        ['drawer-contained', 'bool', 'false', 'Drawer muncul di dalam pembungkus terdekat yang relative, bukan menutupi seluruh layar. Untuk pratinjau di dalam bingkai, mis. playground.'],
        ['scroll-container', 'string', '—', 'Selector CSS wadah gulir isi halaman bila bukan window, mis. "#panel". Scroll-spy dan klik entri bekerja di dalam wadah ini. Padanan scrollContainer.'],
        ['…atribut', '—', '—', 'Atribut lain diteruskan ke elemen <nav>, termasuk class dan aria-label.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: tiruan halaman memasang
    // data-theme="dark" sehingga token kit di dalamnya ikut gelap, daftar isi
    // memakai `dark-mode`, dan cuplikannya menuliskannya lewat penanda ⟨…⟩.
    // Bagian Dark mode tetap gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = \App\Docs\Theme::dark();

    // Sorotan bagian aktif di tiruan halaman, seperti di docs React.
    $sorotan = $docsDark ? 'border-brand bg-primary-900/40' : 'border-brand bg-primary-50';
@endphp

<x-docs.layout title="Table of Contents">
    <x-docs.page
        eyebrow="Components · Table of Contents"
        title="Table of Contents"
        description="Daftar isi “On this page” untuk halaman panjang. Menyorot bagian yang sedang dibaca secara otomatis."
        :toc="[
            'tampilan-dasar' => 'Tampilan dasar',
            'platform' => 'Platform',
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
                halaman ikut ditandai. Di layar di bawah 1024px daftar ini berubah jadi tombol yang membuka
                Drawer dari kanan; lihat bagian Platform.
            </x-docs.lead>

            {{-- Tiruan halaman dokumentasi: bagian yang aktif di daftar isi ikut ditandai di isinya.
                 Di bawah 640px daftar 208px tidak muat di samping isi, jadi daftar isinya berganti
                 tombol yang membuka Drawer di dalam kartu ini, sama seperti layout auto di layar sempit.
                 Demo tidak boleh berpindah halaman; kliknya hanya memindahkan sorotan. --}}
            <div x-data="{ aktif: 'demo-dasar' }" @if ($docsDark) data-theme="dark" @endif class="relative mb-4 overflow-hidden rounded-2xl border border-border bg-toc-surface shadow-soft">
                <div class="flex gap-8 p-5 sm:p-8">
                    <div class="min-w-0 flex-1">
                        <div aria-hidden="true">
                            <p class="text-[11px] font-bold tracking-[0.14em] text-content-disabled uppercase">Components</p>
                            <p class="mt-1 text-heading-3 font-black text-content">Hero</p>
                            <div class="mt-2 h-2 w-3/4 rounded bg-border"></div>
                        </div>

                        <x-ceplok::table-of-contents
                            class="mt-5 sm:hidden"
                            :items="$contoh"
                            layout="drawer"
                            drawer-contained
                            active-id="demo-dasar"
                            :scroll-spy="false"
                            x-model="aktif"
                            x-on:item-click.prevent="aktif = $event.detail.id"
                            :dark-mode="$docsDark"
                        />

                        <div class="mt-6 space-y-2" aria-hidden="true">
                            @foreach ($contoh as $item)
                                <div
                                    class="rounded-lg border-l-2 px-3 py-2.5 transition-colors duration-300"
                                    x-bind:class="aktif === @js($item['id']) ? @js($sorotan) : 'border-transparent'"
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

                    <x-ceplok::table-of-contents
                        class="sticky top-0 hidden shrink-0 sm:block"
                        :items="$contoh"
                        layout="list"
                        active-id="demo-dasar"
                        :scroll-spy="false"
                        x-model="aktif"
                        x-on:item-click.prevent="aktif = $event.detail.id"
                        :dark-mode="$docsDark"
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
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                Ukuran teks mengikuti platform. Di desktop entri memakai teks 12px dalam daftar 208px. Di
                mobile daftar disimpan di Drawer yang muncul dari kanan, dengan teks 14px dan baris 40px
                supaya mudah disentuh. Dengan <span class="text-primary-500 dark:text-primary-400">layout</span> bawaan
                (<span class="text-primary-500 dark:text-primary-400">auto</span>) peralihannya terjadi sendiri di 1024px.
            </x-docs.lead>
            <x-docs.lead>
                Bila navbar ponsel sudah punya menu sendiri, daftar isi bisa diletakkan di menu itu. Di contoh
                Mobile, ikon kiri navbar membuka menu sidebar, dan ikon kanan membuka menu navbar dengan daftar
                isi yang bisa dilipat di bawah tautannya. Daftar isinya <span class="text-primary-500 dark:text-primary-400">layout="list"</span>
                dengan <span class="text-primary-500 dark:text-primary-400">:title="false"</span>, karena judulnya sudah menjadi tombol lipat.
            </x-docs.lead>

            <x-docs.toc-platform class="mb-4" :items="$contoh" :dark="$docsDark" />

            <x-docs.code>
                @verbatim
                {{-- auto: 12px di desktop, Drawer 14px di mobile --}}
                <x-ceplok::table-of-contents :items="$items"⟨ dark-mode⟩ />

                {{-- selalu daftar / selalu Drawer --}}
                <x-ceplok::table-of-contents :items="$items" ⟦layout⟧="list"⟨ dark-mode⟩ />
                <x-ceplok::table-of-contents :items="$items" ⟦layout⟧="drawer"⟨ dark-mode⟩ />

                {{-- Menu navbar ponsel: tautan navbar, lalu daftar isi yang bisa dilipat --}}
                <x-ceplok::drawer wire:model="menu" position="right" size="s">
                    <nav>…tautan navbar…</nav>
                    <div x-data="{ buka: true }">
                        <button type="button" x-on:click="buka = ! buka">On this page</button>
                        <x-ceplok::table-of-contents
                            x-show="buka"
                            :items="$items"
                            ⟦layout⟧="list"
                            :⟦title⟧="false"
                            x-on:item-click="$wire.menu = false"
                            ⟨dark-mode⟩
                        />
                    </div>
                </x-ceplok::drawer>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Hanya palet warnanya yang berubah: latar jadi <span class="text-primary-500 dark:text-primary-400">gray-800</span>, teks dan garis ikut token semantik.
                Pakai <span class="text-primary-500 dark:text-primary-400">dark-mode</span>, atau letakkan di dalam pembungkus
                <span class="text-primary-500 dark:text-primary-400">data-theme="dark"</span>.
            </x-slot:lead>

            <x-docs.toc-platform class="mb-4" :items="$contoh" dark />

            <x-docs.code>
                @verbatim
                <x-ceplok::table-of-contents ⟦dark-mode⟧ :items="$items" />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <x-docs.section id="scrollable" title="Scrollable">
            <x-docs.lead>
                Bila isi halaman digulir di dalam panel, bukan window, berikan selector panelnya lewat
                <span class="text-primary-500 dark:text-primary-400">scroll-container</span>. Gulir panel di bawah: sorotan ikut berpindah, dan klik entri
                menggulir panelnya.
            </x-docs.lead>

            <div @if ($docsDark) data-theme="dark" @endif class="relative flex flex-col gap-4 overflow-hidden rounded-2xl border border-border bg-surface-subtle p-4 sm:flex-row sm:gap-6 sm:p-6">
                {{-- Di layar sempit daftarnya berupa tombol Drawer di atas panel. --}}
                <x-ceplok::table-of-contents
                    class="bg-transparent! sm:hidden"
                    :items="$bagianGulir"
                    layout="drawer"
                    drawer-contained
                    scroll-container="#toc-gulir-panel"
                    :offset="24"
                    :dark-mode="$docsDark"
                />
                <div id="toc-gulir-panel" class="h-80 min-w-0 flex-1 space-y-8 overflow-y-auto rounded-xl border border-border bg-surface p-6">
                    @foreach ($bagianGulir as $bagian)
                        <section id="{{ $bagian['id'] }}">
                            <h3 class="text-heading-4 font-black text-gray-900 dark:text-white">{{ $bagian['label'] }}</h3>
                            <p class="mt-2 text-body-sm leading-6 text-gray-500 dark:text-gray-400">
                                Contoh isi bagian {{ $bagian['label'] }}. Paragraf ini sengaja dibuat cukup panjang supaya
                                panelnya bisa digulir dan perpindahan sorotan di daftar isi terlihat jelas saat
                                menggulir ke bawah maupun ke atas.
                            </p>
                            <div class="mt-3 h-24 rounded-lg bg-surface-subtle"></div>
                        </section>
                    @endforeach
                </div>
                <x-ceplok::table-of-contents class="hidden shrink-0 sm:block" :items="$bagianGulir" layout="list" scroll-container="#toc-gulir-panel" :offset="24" :dark-mode="$docsDark" />
            </div>

            <h3 class="mt-8 text-sm font-black text-gray-900 dark:text-white">Daftar panjang</h3>
            <p class="mt-1 mb-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Dengan <span class="text-primary-500 dark:text-primary-400">max-height</span>, daftar yang panjang bisa digulir sendiri dan entri aktif tetap
                terlihat. Contoh ini menyorot Bagian 19; klik entri lain dan judul isi halaman ikut berganti.
                Di layar sempit daftarnya menjadi tombol yang membuka Drawer.
            </p>

            {{-- Tiruan halaman: judul isi mengikuti entri aktif di daftar yang digulir. Di bawah
                 640px daftarnya berganti tombol yang membuka Drawer di dalam kartu ini. --}}
            <div
                x-data="{ aktif: 'panjang-18', get judul() { return 'Bagian ' + (Number(this.aktif.split('-')[1]) + 1) } }"
                @if ($docsDark) data-theme="dark" @endif
                class="relative mb-4 overflow-hidden rounded-2xl border border-border bg-toc-surface shadow-soft"
            >
                <div class="flex gap-8 p-5 sm:p-8">
                    <div class="min-w-0 flex-1">
                        <div aria-hidden="true">
                            <p class="text-[11px] font-bold tracking-[0.14em] text-content-disabled uppercase">Panduan</p>
                            <p class="mt-1 text-heading-3 font-black text-content">Dokumentasi panjang</p>
                            <div class="mt-2 h-2 w-3/4 rounded bg-border"></div>
                        </div>

                        <x-ceplok::table-of-contents
                            class="mt-5 sm:hidden"
                            :items="$daftarPanjang"
                            layout="drawer"
                            drawer-contained
                            active-id="panjang-18"
                            :scroll-spy="false"
                            x-model="aktif"
                            x-on:item-click.prevent="aktif = $event.detail.id"
                            :dark-mode="$docsDark"
                        />

                        <div class="mt-6 space-y-4" aria-hidden="true">
                            <div @class(['rounded-lg border-l-2 px-3 py-2.5', $sorotan])>
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
                        class="sticky top-0 hidden shrink-0 sm:block"
                        :items="$daftarPanjang"
                        layout="list"
                        :max-height="256"
                        active-id="panjang-18"
                        :scroll-spy="false"
                        x-model="aktif"
                        x-on:item-click.prevent="aktif = $event.detail.id"
                        :dark-mode="$docsDark"
                    />
                </div>
            </div>

            <x-docs.code>
                @verbatim
                <div id="panel" class="h-80 overflow-y-auto">…</div>
                <x-ceplok::table-of-contents :items="$items" ⟦scroll-container⟧="#panel" :⟦offset⟧="24"⟨ dark-mode⟩ />

                <x-ceplok::table-of-contents :items="$items" ⟦max-height⟧="calc(100vh - 8rem)"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.table-of-contents :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Id bagian yang sedang disorot bisa diikat ke properti komponen lewat <span class="text-primary-500 dark:text-primary-400">wire:model</span>,
                padanan activeId + onActiveChange di React. Untuk menanggapi perpindahannya sendiri, dengarkan
                event <span class="text-primary-500 dark:text-primary-400">active-change</span>; id-nya ada di <span class="text-primary-500 dark:text-primary-400">$event.detail.id</span>.
                Event <span class="text-primary-500 dark:text-primary-400">item-click</span> dikirim sebelum tautan berjalan dan bisa dibatalkan, padanan onItemClick.
            </x-docs.lead>
            <x-docs.code>
                @verbatim
                {{-- Bagian yang sedang dibaca ikut tersimpan di properti $bagian --}}
                <x-ceplok::table-of-contents :items="$items" ⟦wire:model⟧="bagian"⟨ dark-mode⟩ />

                {{-- Menanggapi perpindahan sorotan sendiri --}}
                <x-ceplok::table-of-contents :items="$items" ⟦wire:active-change⟧="catat($event.detail.id)"⟨ dark-mode⟩ />

                {{-- Membatalkan tautan dan memilih bagiannya sendiri --}}
                <x-ceplok::table-of-contents
                    :items="$items"
                    :active-id="$bagian"
                    ⟦wire:item-click.prevent⟧="$set('bagian', $event.detail.id)"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Referensi semua prop dan event yang tersedia pada komponen Table of Contents.
            </p>
            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
