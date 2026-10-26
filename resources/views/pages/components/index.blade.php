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

            <x-docs.overview-card route="components.timeline" name="Timeline View" desc="Riwayat kejadian berurutan dengan avatar, waktu, dan status di tiap langkah. Desainnya belum final.">
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
