@php
    use App\Docs\Navigation;

    // Ikon sosial yang sama dengan footer situs, urutannya mengikuti kartu Footer di docs React.
    $socials = collect(Navigation::socials())->keyBy('label');
    $footerSocials = [
        ['label' => 'Facebook', 'url' => '#', 'icon' => $socials['Facebook']['icon']],
        ['label' => 'Twitter', 'url' => '#', 'icon' => $socials['X']['icon']],
        ['label' => 'Instagram', 'url' => '#', 'icon' => $socials['Instagram']['icon']],
    ];

    // Ditulis di blok PHP ini karena di luarnya Blade ikut mengompilasi teks
    // tag komponen kit sebagai komponen, termasuk di dalam nilai atribut.
    $description = 'Komponen Blade siap pakai dari paket ceplok-ui/design-kit-livewire. Dipakai lewat tag <x-ceplok::nama> setelah paket terpasang — tanpa import, langsung di view mana pun.';
@endphp

<x-docs.layout title="Components">
    <x-docs.hero
        eyebrow="Components · Overview"
        title="Components"
        :description="$description"
    />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        {{-- grid-cols-1 (minmax(0,1fr)): tanpa itu kolom di bawah sm melebar mengikuti pratinjau terlebar. --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-docs.overview-card route="components.container" name="Container" desc="Pembungkus lebar konten: 380px di mobile (rounded), maksimum 1126px di desktop." wide>
                <div class="rounded-xl bg-surface-subtle p-5">
                    <div class="mx-auto max-w-55 rounded-lg border-2 border-dashed border-primary-300 bg-white px-4 py-6 text-center">
                        <span class="text-xs font-black text-primary-700">1126px · 380px</span>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.button" name="Button" desc="Aksi utama dengan variant filled atau outline, enam theme warna, dan lima ukuran.">
                <div class="flex flex-wrap items-center gap-2 rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::button variant="outline">Secondary</x-ceplok::button>
                    <x-ceplok::button variant="filled">Primary</x-ceplok::button>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.badge" name="Badge" desc="Label status ringkas dengan lima variant warna semantik.">
                <div class="flex flex-wrap items-center gap-2 rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::badge variant="success" :icon="svg('ceplok-o-check-circle')">Aktif</x-ceplok::badge>
                    <x-ceplok::badge variant="warning" :icon="svg('ceplok-o-hourglass')">Menunggu</x-ceplok::badge>
                    <x-ceplok::badge variant="danger" :icon="svg('ceplok-o-close-circle')">Ditolak</x-ceplok::badge>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.alert" name="Alert" desc="Pesan status di dalam alur halaman dengan lima variant warna dan elemen yang sepenuhnya opsional.">
                <div class="rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::alert variant="success" heading="Ini adalah Alert" :dismissible="false">
                        Ini merupakan Design system Ceplok berupa component alert.
                    </x-ceplok::alert>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.avatar" name="Avatar" desc="Lingkaran identitas berisi foto profil, atau inisial namanya bila fotonya tidak ada.">
                <div class="flex items-center justify-center gap-4 rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::avatar src="/images/avatar-sample.svg" name="Putri Handayani" alt="" size="large" />
                    <x-ceplok::avatar initials="PH" />
                    <x-ceplok::avatar initials="PH" size="small" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.spinner" name="Spinner" desc="Indikator proses berputar dalam ukuran default dan large.">
                <div class="flex items-center justify-center rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::spinner aria-label="Memuat preview" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.clipboard" name="Clipboard" desc="Nilai hanya-baca yang dapat disalin melalui tombol aksi.">
                <div class="rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::clipboard value="INV-2026-001" aria-label="Nomor referensi" />
                </div>
            </x-docs.overview-card>

            {{-- Skeleton seperti di docs React: Popover asli merender <button>, sedangkan kartu ini sudah berupa <a>. --}}
            <x-docs.overview-card route="components.popover" name="Popover" desc="Menampilkan informasi kontekstual secara ringkas di dekat elemen yang memicunya.">
                <div class="@container flex items-center justify-center rounded-xl bg-surface-subtle p-5" aria-hidden="true">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="inline-flex size-8 shrink-0 items-center justify-center text-gray-500">
                            <x-ceplok::icon class="size-5">{{ svg('ceplok-o-info-circle') }}</x-ceplok::icon>
                        </span>
                        <div class="relative w-56 min-w-0 [filter:drop-shadow(0_1px_2px_rgb(0_0_0/0.08))]">
                            <span class="pointer-events-none absolute top-1/2 left-[-8px] z-30 size-4 -translate-y-1/2 -rotate-90">
                                <span class="absolute top-[3.25px] left-[3px] size-2.5 rotate-45 border-t border-l border-border bg-surface"></span>
                            </span>
                            <div class="relative z-10 overflow-hidden rounded-md bg-surface">
                                <div class="border-b border-border bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-900">Informasi</div>
                                <div class="bg-surface px-3 py-2 text-xs font-medium text-gray-500">
                                    {{-- Dua baris tetap bila kotaknya muat (≥ 268px); bila tidak, teksnya dibungkus. --}}
                                    <span class="@min-[17rem]:whitespace-nowrap">Informasi tambahan yang relevan</span>
                                    <br class="hidden @min-[17rem]:inline">
                                    <span class="@min-[17rem]:whitespace-nowrap">untuk membantu memahami konteks.</span>
                                </div>
                                <span aria-hidden="true" class="pointer-events-none absolute inset-0 z-20 rounded-md border border-border"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.dropdown" name="Dropdown" desc="Kumpulan aksi atau pilihan tambahan yang dibuka dari sebuah trigger.">
                {{-- Gambaran statis: panel yang terbuka diperlihatkan langsung di bawah pemicunya. --}}
                <div class="rounded-xl bg-surface-subtle p-5">
                    <div class="mx-auto flex w-fit max-w-full flex-col items-center gap-2">
                        <div class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary-700 px-4 text-base font-medium text-white">
                            Dropdown button
                            <svg aria-hidden="true" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </div>
                        <div class="w-full rounded-lg bg-surface py-1 shadow-md" aria-hidden="true">
                            <div class="px-4 py-2 text-sm font-medium text-gray-700">Profil</div>
                            <div class="px-4 py-2 text-sm font-medium text-gray-700">Pengaturan</div>
                            <div class="my-1 border-t border-border"></div>
                            <div class="px-4 py-2 text-sm font-medium text-red-600">Keluar</div>
                        </div>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.modal" name="Modal" desc="Dialog terkontrol untuk informasi atau tindakan yang harus diselesaikan sebelum kembali ke halaman utama.">
                <div class="rounded-xl bg-gray-900/40 p-5">
                    <div class="mx-auto max-w-[240px] overflow-hidden rounded-lg border border-border bg-white shadow-xl">
                        <div class="flex items-center justify-between border-b border-border px-4 py-3">
                            <div class="h-2.5 w-24 rounded bg-gray-300"></div>
                            <div class="size-3 rounded bg-gray-200"></div>
                        </div>
                        <div class="space-y-2 px-4 py-5">
                            <div class="h-1.5 w-full rounded bg-gray-200"></div>
                            <div class="h-1.5 w-2/3 rounded bg-gray-200"></div>
                        </div>
                        <div class="border-t border-border px-4 py-3">
                            <div class="h-6 w-20 rounded-lg bg-primary-700"></div>
                        </div>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.toast" name="Toast" desc="Notifikasi sekilas yang melayang di atas konten, dengan heading dan aksi opsional.">
                {{--
                    Kerangka halaman di belakangnya penting: Toast bedanya dengan Alert
                    justru karena ia melayang di atas konten. mt-auto menurunkan Toast
                    ke sudut bawah kiri, memakai sisa tinggi kartu.
                --}}
                <div class="flex flex-col rounded-xl bg-surface-subtle p-5">
                    <div class="space-y-3 opacity-60" aria-hidden="true">
                        <div class="h-2 w-2/5 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-5/6 rounded bg-gray-200"></div>
                        <div class="mt-6 h-2 w-1/3 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-4/6 rounded bg-gray-200"></div>
                    </div>
                    <div class="mt-auto max-w-80 pt-8">
                        <x-ceplok::toast variant="success">Sukses Membuat Data!</x-ceplok::toast>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.card" name="Card" desc="Wadah informasi dengan gambar, judul, deskripsi, dan aksi yang semuanya opsional.">
                <div class="rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::card image="/images/card-sample.svg" image-alt="" title="Judul Kartu" description="Deskripsi singkat yang menjelaskan isi kartu dalam satu atau dua baris." />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.breadcrumb" name="Breadcrumb" desc="Jejak lokasi halaman dengan pemisah chevron, dua ukuran teks, dan latar opsional.">
                {{--
                    Ukuran dipilih dari lebar kotak ini: ukuran base butuh 242px, di bawahnya
                    sm (12px). Padding sampingnya diciutkan di kartu tersempit: ponsel di
                    bawah 360px, dan 640–767px saat grid baru dua kolom.
                --}}
                <div class="@container flex items-center rounded-xl bg-surface-subtle p-5 max-[359px]:px-3 sm:max-md:px-3">
                    <div class="@min-[15.25rem]:hidden"><x-ceplok::breadcrumb size="sm" :items="[['label' => 'Beranda'], ['label' => 'Komponen'], ['label' => 'Breadcrumb']]" /></div>
                    <div class="hidden @min-[15.25rem]:block"><x-ceplok::breadcrumb :items="[['label' => 'Beranda'], ['label' => 'Komponen'], ['label' => 'Breadcrumb']]" /></div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.pagination" name="Pagination" desc="Navigasi antar halaman daftar data, dengan tema warna mengikuti aplikasi.">
                <div class="flex justify-center rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::pagination :current-page="2" :total-pages="5" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.hero" name="Hero" desc="Pembuka halaman: judul, sub heading, deskripsi, satu tombol, dan gambar di kiri atau kanan." wide>
                <div class="overflow-hidden rounded-xl border border-border bg-white">
                    <x-ceplok::hero heading="Judul Halaman" sub-heading="Sub heading" description="Paragraf pembuka yang menjelaskan isi halaman secara singkat." button-label="Selengkapnya" image="/images/hero-sample.svg" image-alt="" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.footer" name="Footer" desc="Penutup halaman responsif dengan logo, menu, hak cipta, dan tautan media sosial." wide>
                <div class="overflow-hidden rounded-xl">
                    <x-ceplok::footer
                        logo="/images/footer-logo.svg"
                        logo-alt="Ceplok Design System"
                        :menus="[['label' => 'Menu 1'], ['label' => 'Menu 2'], ['label' => 'Menu 3'], ['label' => 'Menu 4']]"
                        :copyright="'© '.date('Y').' CEPLOK Design System'"
                        :socials="$footerSocials"
                    />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.pagination" name="Pagination" desc="Navigasi antar halaman daftar data, dengan tema warna mengikuti aplikasi.">
                {{--
                    Lebar kartu berganti mengikuti jumlah kolom dan sidebar, jadi ukurannya
                    dipilih dari lebar kotak ini (container query), bukan lebar layar:
                    tujuh kotak 40px butuh 282px, di bawahnya pakai size s (kotak 32px) dengan
                    empat halaman, supaya muat di kartu tersempit (±230px).
                --}}
                <div class="@container flex justify-center rounded-xl bg-surface-subtle p-5 max-[359px]:px-3 sm:max-md:px-3">
                    <div class="@min-[17.75rem]:hidden"><x-ceplok::pagination size="s" :current-page="2" :total-pages="4" /></div>
                    <div class="hidden @min-[17.75rem]:block"><x-ceplok::pagination :current-page="2" :total-pages="5" /></div>
                </div>
            </x-docs.overview-card>
            <x-docs.overview-card route="components.table" name="Table" desc="Tabel data dengan pencarian, filter kolom, pengurutan, pagination, dan aksi per baris." wide>
                {{--
                    Skeleton, bukan Table asli, sama seperti di docs React. Kotak cari,
                    sort, aksi, dan pagination Table asli merender <button>, sedangkan
                    kartu ini sendiri sudah berupa <a>.

                    Lima kolom (nomor, pemohon, layanan, status, aksi) hanya mulai sm;
                    di bawahnya kolom layanan dan aksi disembunyikan supaya tetap muat.
                --}}
                <div class="rounded-xl bg-surface-subtle p-5" aria-hidden="true">
                    <div class="overflow-hidden rounded-lg border border-border bg-white shadow-sm">
                        {{-- Toolbar: kotak cari, tombol filter, aksi di kanan --}}
                        <div class="flex items-center gap-3 p-4">
                            <div class="h-7 w-40 max-w-full rounded-md border border-gray-200 bg-gray-50 sm:w-56"></div>
                            <div class="h-7 w-20 shrink-0 rounded-md border border-gray-300"></div>
                            <div class="ml-auto hidden h-7 w-24 rounded-md bg-primary-700 sm:block"></div>
                        </div>
                        {{-- Header --}}
                        {{-- Kolom minmax(0,…) dan bar max-w-full: di kartu sempit (320px) bar ikut menyempit, tidak terdorong keluar. --}}
                        <div class="grid grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)_minmax(0,1fr)] items-center gap-4 border-y border-gray-200 bg-gray-50 px-4 py-3 sm:grid-cols-[1fr_1.4fr_1.4fr_1fr_0.8fr]">
                            <div class="h-1.5 w-10 max-w-full rounded bg-gray-300"></div>
                            <div class="h-1.5 w-14 max-w-full rounded bg-gray-300"></div>
                            <div class="hidden h-1.5 w-14 rounded bg-gray-300 sm:block"></div>
                            <div class="h-1.5 w-10 max-w-full rounded bg-gray-300"></div>
                            <div class="hidden h-1.5 w-8 rounded bg-gray-300 sm:block"></div>
                        </div>
                        {{-- Baris: nomor, nama tebal, layanan, pill status, tombol aksi --}}
                        @foreach (['bg-green-100', 'bg-primary-100', 'bg-yellow-100'] as $pill)
                            <div @class([
                                'grid grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)_minmax(0,1fr)] items-center gap-4 px-4 py-3.5 sm:grid-cols-[1fr_1.4fr_1.4fr_1fr_0.8fr]',
                                'border-b border-gray-200' => ! $loop->last,
                            ])>
                                <div class="h-1.5 w-12 max-w-full rounded bg-gray-200"></div>
                                <div class="h-2 w-24 max-w-full rounded bg-gray-300"></div>
                                <div class="hidden h-1.5 w-28 max-w-full rounded bg-gray-200 sm:block"></div>
                                <div class="h-4 w-14 max-w-full rounded-full {{ $pill }}"></div>
                                <div class="hidden gap-1.5 sm:flex">
                                    <div class="size-4 rounded bg-primary-700"></div>
                                    <div class="size-4 rounded border border-gray-300"></div>
                                </div>
                            </div>
                        @endforeach
                        {{-- Ringkasan dan pagination --}}
                        <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3">
                            <div class="h-1.5 w-28 rounded bg-gray-200"></div>
                            <div class="flex gap-1">
                                <div class="size-5 rounded bg-gray-200"></div>
                                <div class="size-5 rounded bg-primary-700"></div>
                                <div class="size-5 rounded bg-gray-200"></div>
                                <div class="size-5 rounded bg-gray-200"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.table-of-contents" name="Table of Contents" desc="Daftar isi “On this page” yang menyorot bagian yang sedang dibaca.">
                {{-- Isi halamannya skeleton, seperti di overview React; daftar isinya komponen asli. --}}
                <div class="relative flex h-66 gap-6 overflow-hidden rounded-xl bg-surface-subtle p-5">
                    <div class="min-w-0 flex-1 space-y-3 rounded-lg bg-white p-5 shadow-sm" aria-hidden="true">
                        <div class="h-2 w-2/5 rounded bg-primary-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-5/6 rounded bg-gray-200"></div>
                        <div class="mt-6 h-2 w-1/3 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-4/6 rounded bg-gray-200"></div>
                        <div class="mt-6 h-2 w-1/4 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-5/6 rounded bg-gray-200"></div>
                    </div>
                    <x-ceplok::table-of-contents
                        class="w-36! shrink-0 bg-transparent! pt-1"
                        layout="list"
                        :anchor="false"
                        :items="[
                            ['id' => 'ov-dasar', 'label' => 'Tampilan dasar'],
                            ['id' => 'ov-variasi', 'label' => 'Variasi'],
                            ['id' => 'ov-dark', 'label' => 'Dark mode'],
                            ['id' => 'ov-playground', 'label' => 'Playground'],
                            ['id' => 'ov-props', 'label' => 'Properties'],
                        ]"
                    />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.drawer" name="Drawer" desc="Panel kontekstual di tepi layar (Side Sheet / Off-canvas) untuk form, filter, atau detail data.">
                <div class="relative flex h-[264px] flex-col overflow-hidden rounded-xl bg-surface-subtle p-5" aria-hidden="true">
                    <div class="space-y-3 opacity-60">
                        <div class="h-2 w-2/5 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-5/6 rounded bg-gray-200"></div>
                        <div class="mt-6 h-2 w-1/3 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-4/6 rounded bg-gray-200"></div>
                        <div class="mt-6 h-2 w-2/5 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                    </div>
                    <div class="absolute inset-0 bg-gray-900/40"></div>
                    <div class="absolute inset-y-0 right-0 flex w-48 flex-col border-l border-gray-200 bg-white shadow-2xl">
                        <div class="px-4 pt-5 pb-3 text-xs font-semibold tracking-wide text-gray-500">MENU</div>
                        <div class="space-y-1 p-3 text-sm">
                            <div class="rounded-lg bg-primary-50 px-3 py-2 text-primary-700">Beranda</div>
                            <div class="px-3 py-2 text-gray-900">Layanan</div>
                            <div class="px-3 py-2 text-gray-900">Laporan</div>
                        </div>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.sidebar" name="Sidebar" desc="Navigasi samping dengan grup menu, submenu, profil akun, dan mode ringkas.">
                <div class="relative flex h-[264px] flex-col overflow-hidden rounded-xl bg-surface-subtle p-5" aria-hidden="true">
                    <div class="ml-56 hidden space-y-3 opacity-60 sm:block">
                        <div class="h-2 w-2/5 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-5/6 rounded bg-gray-200"></div>
                        <div class="mt-6 h-2 w-1/3 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-4/6 rounded bg-gray-200"></div>
                    </div>
                    <div class="absolute inset-y-0 left-0 w-56 border-r border-gray-200 bg-white p-5 shadow-sm">
                        <div class="mb-8 flex items-center gap-2">
                            <img src="/images/ceplok.svg" alt="" aria-hidden="true" class="size-8 shrink-0 object-contain">
                            <span class="text-sm font-black tracking-tight text-gray-900">CEPLOK</span>
                        </div>
                        <div class="space-y-1 text-sm">
                            <div class="rounded-lg bg-gray-100 px-3 py-2.5 text-gray-900">Beranda</div>
                            <div class="px-3 py-2.5 text-gray-900">Layanan</div>
                            <div class="px-3 py-2.5 text-gray-900">Laporan</div>
                            <div class="px-3 py-2.5 text-gray-900">Pengaturan</div>
                        </div>
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.list-view" name="List View" desc="Kartu daftar berkas dan dokumen dengan wadah ikon, metadata ringkas, dan aksi cepat.">
                <div class="flex flex-col justify-center gap-2 rounded-xl bg-surface-subtle p-3">
                    <x-ceplok::list-view size="s" title="Project brief" :metadata="['PDF', '2.4 MB']" action-label="Open" />
                    <x-ceplok::list-view size="s" title="Project brief" :metadata="['PDF', '2.4 MB']" action-label="Open" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.timeline" name="Timeline View" desc="Riwayat kejadian berurutan dengan avatar, waktu, dan status di tiap langkah.">
                <div class="rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::timeline platform="mobile" size="small" :items="[
                        ['name' => 'User 01', 'role' => 'Role / Department', 'time' => '2026-01-01 09:00'],
                        ['name' => 'User 02', 'role' => 'Role / Department', 'time' => '2026-01-01 09:05', 'status' => ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true]],
                    ]" />
                </div>
            </x-docs.overview-card>
        </div>
    </div>
</x-docs.layout>
