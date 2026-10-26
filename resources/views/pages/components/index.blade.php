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
        <div class="grid gap-5 sm:grid-cols-2">
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
                    <x-ceplok::avatar src="/images/avatar-sample.svg" alt="" size="large" />
                    <x-ceplok::avatar initials="PH" />
                    <x-ceplok::avatar initials="PH" size="small" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="components.spinner" name="Spinner" desc="Indikator proses berputar dalam ukuran default dan large.">
                <div class="flex items-center justify-center rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::spinner aria-label="Memuat preview" />
                </div>
            </x-docs.overview-card>

            {{-- Skeleton seperti di docs React: Popover asli merender <button>, sedangkan kartu ini sudah berupa <a>. --}}
            <x-docs.overview-card route="components.popover" name="Popover" desc="Menampilkan informasi kontekstual secara ringkas di dekat elemen yang memicunya.">
                <div class="flex items-center justify-center rounded-xl bg-surface-subtle p-5" aria-hidden="true">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex size-8 shrink-0 items-center justify-center text-gray-500">
                            <x-ceplok::icon class="size-5">{{ svg('ceplok-o-info-circle') }}</x-ceplok::icon>
                        </span>
                        <div class="relative w-56 shrink-0 [filter:drop-shadow(0_1px_2px_rgb(0_0_0/0.08))]">
                            <span class="pointer-events-none absolute top-1/2 left-[-8px] z-30 size-4 -translate-y-1/2 -rotate-90">
                                <span class="absolute top-[3.25px] left-[3px] size-2.5 rotate-45 border-t border-l border-border bg-surface"></span>
                            </span>
                            <div class="relative z-10 overflow-hidden rounded-md bg-surface">
                                <div class="border-b border-border bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-900">Informasi</div>
                                <div class="bg-surface px-3 py-2 text-xs font-medium text-gray-500">
                                    <span class="whitespace-nowrap">Informasi tambahan yang relevan</span>
                                    <br>
                                    <span class="whitespace-nowrap">untuk membantu memahami konteks.</span>
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
                <div class="flex items-center rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::breadcrumb :items="[['label' => 'Beranda'], ['label' => 'Komponen'], ['label' => 'Breadcrumb']]" />
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
                <div class="flex justify-center rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::pagination :current-page="2" :total-pages="5" />
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
                        <div class="grid grid-cols-[1fr_1.4fr_1fr] items-center gap-4 border-y border-gray-200 bg-gray-50 px-4 py-3 sm:grid-cols-[1fr_1.4fr_1.4fr_1fr_0.8fr]">
                            <div class="h-1.5 w-10 rounded bg-gray-300"></div>
                            <div class="h-1.5 w-14 rounded bg-gray-300"></div>
                            <div class="hidden h-1.5 w-14 rounded bg-gray-300 sm:block"></div>
                            <div class="h-1.5 w-10 rounded bg-gray-300"></div>
                            <div class="hidden h-1.5 w-8 rounded bg-gray-300 sm:block"></div>
                        </div>
                        {{-- Baris: nomor, nama tebal, layanan, pill status, tombol aksi --}}
                        @foreach (['bg-green-100', 'bg-primary-100', 'bg-yellow-100'] as $pill)
                            <div @class([
                                'grid grid-cols-[1fr_1.4fr_1fr] items-center gap-4 px-4 py-3.5 sm:grid-cols-[1fr_1.4fr_1.4fr_1fr_0.8fr]',
                                'border-b border-gray-200' => ! $loop->last,
                            ])>
                                <div class="h-1.5 w-12 rounded bg-gray-200"></div>
                                <div class="h-2 w-24 max-w-full rounded bg-gray-300"></div>
                                <div class="hidden h-1.5 w-28 max-w-full rounded bg-gray-200 sm:block"></div>
                                <div class="h-4 w-14 rounded-full {{ $pill }}"></div>
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
        </div>
    </div>
</x-docs.layout>
