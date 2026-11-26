@php
    use App\Docs\Theme;

    // Demo mode tautan: halaman aktifnya dibaca dari alamat (?hal=), jadi
    // setiap klik benar-benar memuat halaman dengan nomor yang baru.
    $linkPage = max(1, min(10, (int) request()->query('hal', 3)));
    $linkUrl = route('components.pagination').'?hal={page}#tautan';

    $props = [
        ['current-page', 'int', '1', 'Menentukan halaman yang sedang aktif.'],
        ['total-pages', 'int', '1', 'Menentukan jumlah halaman yang tersedia.'],
        ['wire:model', 'atribut', '—', 'Mengikat halaman aktif ke properti Livewire (atau x-model untuk Alpine); setiap klik mengubah properti itu — padanan onPageChange. Pakai .live supaya daftar halamannya ikut dirender ulang.'],
        ['wire:page-change', 'event', '—', 'Dipicu setiap pindah halaman dengan $event.detail.page, mis. wire:page-change="gotoPage($event.detail.page)". Juga bisa x-on:page-change.'],
        ['url', 'string | Closure | null', 'null', 'Membuat setiap halaman menjadi tautan: pola dengan {page} (mis. /pengajuan?page={page}) atau closure fn (int $page) => route(…). Tanpa JavaScript.'],
        ['theme', '"default" | "primary" | "purple"', 'primary', 'Menentukan warna pagination.'],
        ['size', '"base" | "s" | "responsive"', 'base', 'Ukuran kotak: 40px atau 32px. responsive memakai 32px lalu 40px saat container induk ≥ 512px; butuh induk ber-@container.'],
        ['dark-mode', 'bool', 'false', 'Menentukan apakah pagination dirender dalam mode gelap.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Pagination">
    <x-docs.page
        eyebrow="Components · Pagination"
        title="Pagination"
        description="Navigasi untuk berpindah antar halaman pada data atau konten yang terbagi ke dalam beberapa halaman."
        :toc="[
            'states' => 'States',
            'themes' => 'Themes',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'tautan' => 'Mode tautan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="states" title="States">
            <p class="mb-4 text-body-sm text-gray-500 dark:text-gray-400">
                Pagination memiliki state aktif untuk menunjukkan halaman yang sedang dipilih. Tombol
                halaman lainnya dapat digunakan untuk berpindah ke halaman yang berbeda.
            </p>

            <div class="mb-4 grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="First Page">
                    <x-ceplok::pagination :current-page="1" :total-pages="100" theme="primary" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Active Page">
                    <x-ceplok::pagination :current-page="2" :total-pages="100" theme="primary" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                {{-- $page: properti publik komponen Livewire, mis. public int $page = 2; --}}
                <x-ceplok::pagination
                    :⟦current-page⟧="$page"
                    :total-pages="100"
                    wire:model.live="page"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="themes" title="Themes">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Theme digunakan untuk menyesuaikan warna halaman aktif pada Pagination sesuai dengan
                konteks visual aplikasi.
            </p>

            <div class="mb-4 flex flex-col gap-6">
                <x-docs.demo>
                    <div>
                        <x-ceplok::pagination :current-page="1" :total-pages="100" theme="default" :dark-mode="$docsDark" />
                        <p class="mt-2 mb-5 text-sm text-gray-600 dark:text-gray-300">
                            <span class="text-primary-500 dark:text-primary-400">Default</span> menggunakan gray sebagai warna utama pagination.
                        </p>
                    </div>
                    <div>
                        <x-ceplok::pagination :current-page="1" :total-pages="100" theme="primary" :dark-mode="$docsDark" />
                        <p class="mt-2 mb-5 text-sm text-gray-600 dark:text-gray-300">
                            <span class="text-primary-500 dark:text-primary-400">Primary</span> digunakan sebagai warna utama lain pagination.
                        </p>
                    </div>
                    <div>
                        <x-ceplok::pagination :current-page="1" :total-pages="100" theme="purple" :dark-mode="$docsDark" />
                        <p class="mt-2 mb-5 text-sm text-gray-600 dark:text-gray-300">
                            <span class="text-primary-500 dark:text-primary-400">Purple</span> digunakan ketika pagination membutuhkan aksen ungu.
                        </p>
                    </div>
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::pagination
                    :current-page="$page"
                    :total-pages="100"
                    wire:model.live="page"
                    ⟦theme⟧="primary"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-300">dark-mode</span> dapat digunakan untuk mengaktifkan warna gelap secara manual pada Pagination. Komponen secara otomatis menyesuaikan warna latar, teks, batas, dan tema yang aktif.
            </x-slot:lead>

            <div class="mb-4 grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Default" dark>
                    <x-ceplok::pagination :current-page="2" :total-pages="100" theme="default" dark-mode />
                </x-docs.demo>
                <x-docs.demo label="Primary" dark>
                    <x-ceplok::pagination :current-page="2" :total-pages="100" theme="primary" dark-mode />
                </x-docs.demo>
                <x-docs.demo label="Purple" dark>
                    <x-ceplok::pagination :current-page="2" :total-pages="100" theme="purple" dark-mode />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::pagination
                    :current-page="2"
                    :total-pages="100"
                    wire:model.live="page"
                    theme="primary"
                    ⟦dark-mode⟧
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.pagination :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Dengan <span class="text-primary-500 dark:text-primary-400">wire:model.live</span>, klik pada Pagination langsung mengganti properti komponen dan
                daftar datanya dirender ulang. Atribut <span class="text-primary-500 dark:text-primary-400">#[Url]</span> mencatat halamannya di alamat,
                jadi tautannya bisa dibagikan. Bila komponen Anda memakai <span class="text-primary-500 dark:text-primary-400">WithPagination</span>, tangkap
                event-nya: <span class="text-primary-500 dark:text-primary-400">wire:page-change="gotoPage($event.detail.page)"</span>.
            </x-docs.lead>
            <x-docs.demo>
                <livewire:playground.pagination-list :dark="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                {{-- Komponen Livewire --}}
                #[Url(as: 'halaman')]
                public int $page = 1;

                {{-- View-nya --}}
                <x-ceplok::pagination
                    :current-page="$page"
                    :total-pages="$this->totalPages()"
                    ⟦wire:model.live⟧="page"
                    size="s"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="tautan" title="Mode tautan">
            <x-docs.lead>
                Di halaman Blade biasa tanpa Livewire, isi <span class="text-primary-500 dark:text-primary-400">url</span> supaya setiap halaman menjadi tautan
                <span class="text-primary-500 dark:text-primary-400">&lt;a href&gt;</span> — berupa pola dengan <span class="text-primary-500 dark:text-primary-400">{page}</span>, atau closure yang menerima nomor
                halamannya. Tidak butuh JavaScript, dan alamatnya bisa disalin atau dibuka di tab baru.
                Pagination di bawah membaca halamannya dari alamat halaman ini.
            </x-docs.lead>
            <x-docs.demo>
                <x-ceplok::pagination :current-page="$linkPage" :total-pages="10" :url="$linkUrl" :dark-mode="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::pagination
                    :current-page="request()->integer('page', 1)"
                    :total-pages="10"
                    ⟦url⟧="/pengajuan?page={page}"
                    ⟨dark-mode⟩
                />

                {{-- Atau dengan closure --}}
                <x-ceplok::pagination :current-page="$page" :total-pages="10"
                    :⟦url⟧="fn (int $page) => route('pengajuan.index', ['page' => $page])"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Referensi semua prop yang tersedia pada komponen Pagination.
            </p>
            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
