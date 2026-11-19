@php
    use App\Docs\Theme;

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-700'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    // Placeholder dan daftar kategori yang dipakai contoh-contoh di halaman ini.
    $placeholder = 'Search Civitas, Organisasi…';
    $kategori = [
        ['value' => 'civitas', 'label' => 'Civitas'],
        ['value' => 'organisasi', 'label' => 'Organisasi'],
        ['value' => 'berita', 'label' => 'Berita'],
        ['value' => 'layanan', 'label' => 'Layanan'],
    ];

    $props = [
        ['label', 'string | slot', 'null', 'Teks label di atas field.'],
        ['helper-text', 'string | slot', 'null', 'Caption di bawah field.'],
        ['button-label', 'string', 'Cari', 'Label tombol; pada varian kategori jadi label aksesibilitas tombol ikon.'],
        ['wire:search', 'event', '—', 'Dipicu saat tombol ditekan atau Enter di dalam field, dengan detail.value dan detail.category; dengan trigger change atau auto juga setiap isian dan kategori berubah. Padanan onSearch; juga bisa x-on:search.'],
        ['trigger', '"enter" | "change" | "auto"', 'enter', 'Kapan event search dikirim: enter hanya lewat tombol Enter dan tombol Cari; change dan auto juga setiap isian berubah (setelah jeda debounce) dan setiap kategori diganti.'],
        ['debounce', 'int', '300', 'Jeda dalam milidetik sebelum event search dikirim saat isian berubah, pada trigger change dan auto.'],
        ['platform', '"default" | "mobile" | "compact"', 'default', 'Tinggi kotak, ukuran ikon, teks, dan tombol: 54px, 50px, atau 37px. compact untuk navbar. Tidak berlaku pada varian kategori.'],
        ['show-button', 'bool', 'true', 'Menampilkan tombol cari di kanan. Bawaannya false pada platform compact.'],
        ['application', '"default" | "purple"', 'default', 'Warna tombol cari dan garis saat field difokus.'],
        ['categories', 'array', 'null', 'Bila diisi, komponen berpindah ke varian dengan kategori.'],
        ['category-placeholder', 'string', 'Kategori', 'Teks dropdown saat kategori belum dipilih.'],
        ['category', 'string', 'null', 'Kategori terpilih di awal.'],
        ['category-attributes', 'array', '[]', 'Atribut untuk <select> kategorinya, mis. [\'wire:model.live\' => \'kategori\'].'],
        ['wire:category-change', 'event', '—', 'Dipicu saat kategori berganti, dengan detail.value; padanan onCategoryChange.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: warna abu-abu gelap dengan teks terang.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <input> standar diteruskan (name, wire:model, placeholder, …). class dan wire:key ke pembungkus terluar.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Search Form">
    <x-docs.page
        eyebrow="Form"
        title="Search Form"
        description="Kolom pencarian dengan tombol cari. Tersedia sebagai satu kotak polos atau tiga ruas menyatu dengan dropdown kategori di kiri, memakai warna, radius, dan jarak dari Foundations."
        :toc="[
            'search-form' => 'Search Form',
            'platform' => 'Platform',
            'kategori' => 'Dengan kategori',
            'application' => 'Application',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="search-form" title="Search Form">
            <x-docs.lead>
                Ikon kaca pembesar di kiri, isian di tengah, lalu tombol berlabel di kanan. Pakai bentuk
                ini saat pencarian berdiri sendiri di atas sebuah daftar atau tabel: tombolnya membuat
                jelas bahwa hasil baru berganti setelah ditekan, bukan sambil diketik.
            </x-docs.lead>
            <x-docs.demo label="Default">
                <x-ceplok::search :placeholder="$placeholder" :dark-mode="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::search
                    placeholder="Search Civitas, Organisasi…"
                    ⟦wire:search⟧="cari($event.detail.value)"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                Dua ukuran, sama seperti Input Field. <span class="text-primary-500 dark:text-primary-400">default</span> setinggi 54px dengan ikon 18px dan teks
                16px; <span class="text-primary-500 dark:text-primary-400">mobile</span> setinggi 50px dengan ikon 14px dan teks 14px. Tombolnya ikut mengecil
                dari 38px ke 34px.
            </x-docs.lead>
            <div class="grid gap-5">
                <x-docs.demo label="Default — 54px">
                    <x-ceplok::search :placeholder="$placeholder" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Mobile — 50px">
                    <div class="max-w-87">
                        <x-ceplok::search platform="mobile" :placeholder="$placeholder" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::search ⟦platform⟧="mobile" placeholder="Search Civitas, Organisasi…"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="kategori" title="Dengan kategori">
            <x-docs.lead>
                Begitu prop <span class="text-primary-500 dark:text-primary-400">categories</span> diisi, komponen berpindah bentuk: dropdown kategori di kiri,
                isian di tengah, dan tombol ikon di kanan. Variannya dibuat implisit karena varian kategori
                tanpa daftar kategori tidak punya arti.
            </x-docs.lead>
            <div class="grid gap-5">
                <x-docs.demo label="Default — 882px">
                    <div class="max-w-220.5">
                        <x-ceplok::search :categories="$kategori" :placeholder="$placeholder" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile — 382px">
                    <div class="max-w-95.5">
                        <x-ceplok::search :categories="$kategori" :placeholder="$placeholder" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::search
                    :⟦categories⟧="[['value' => 'civitas', 'label' => 'Civitas']]"
                    placeholder="Search Civitas, Organisasi…"
                    wire:search="cari($event.detail.value, $event.detail.category)"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Kedua ukuran di atas isinya sama persis: tinggi 39px, ruas kategori 113px, dan tombol
                44px. Yang berbeda cuma isian di tengah — ia memanjang mengikuti wadahnya, dari 382px di
                ponsel sampai 882px di layar lebar. Karena itu prop <span class="text-primary-500 dark:text-primary-400">platform</span> tidak dipakai bentuk
                ini; cukup atur lebar wadahnya.
            </p>
            <p class="mt-2 text-body-sm text-gray-500 dark:text-gray-400">
                Daftar kategorinya panel Dropdown, sama dengan daftar pilihan Regular Select Form. Nilai
                kategorinya dibawa elemen <span class="text-primary-500 dark:text-primary-400">&lt;select&gt;</span> di belakang tombolnya, dan itulah yang
                dikirim event <span class="text-primary-500 dark:text-primary-400">category-change</span> serta <span class="text-primary-500 dark:text-primary-400">detail.category</span> pada event search.
            </p>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna tombol cari — dan garis kotak saat difokus — mengikuti aplikasi yang memakainya,
                dengan token warna yang sama seperti pada Input Field dan Text Area. Pilih sekali di tingkat
                halaman, lalu biarkan sama untuk semua form di aplikasi itu.
            </x-docs.lead>
            <div class="grid gap-5">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <x-ceplok::search :application="$a['value']" :placeholder="$placeholder" :helper-text="'bg-'.$a['token']" :dark-mode="$docsDark" />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::search ⟦application⟧="purple" placeholder="Search Civitas, Organisasi…"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> dapat digunakan untuk mengaktifkan warna gelap secara manual pada Search Form. Komponen secara otomatis menyesuaikan warna latar, teks, placeholder, dan batas.
            </x-slot:lead>
            <div class="grid gap-5">
                <x-docs.demo label="Default" dark>
                    <x-ceplok::search :placeholder="$placeholder" dark-mode />
                </x-docs.demo>
                <x-docs.demo label="Dengan Kategori" dark>
                    <div class="max-w-220.5">
                        <x-ceplok::search :categories="$kategori" :placeholder="$placeholder" dark-mode />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::search ⟦dark-mode⟧ placeholder="Search Civitas, Organisasi…" />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.search :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Event <span class="text-primary-500 dark:text-primary-400">search</span> dikirim dari elemen input-nya, jadi cukup pasang <span class="text-primary-500 dark:text-primary-400">wire:search</span> pada tag
                komponennya. Dengan <span class="text-primary-500 dark:text-primary-400">trigger="auto"</span> event itu juga dikirim setiap isian berubah, setelah jeda
                <span class="text-primary-500 dark:text-primary-400">debounce</span> — cocok untuk daftar yang disaring sambil diketik. Coba ketik nama layanan di bawah.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.search-live :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::search
                    placeholder="Cari layanan…"
                    ⟦trigger⟧="auto"
                    :⟦debounce⟧="250"
                    ⟦wire:search⟧="cari($event.detail.value)"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="text-primary-500 dark:text-primary-400">&lt;input&gt;</span> standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="52rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
