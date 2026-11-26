@php
use App\Docs\Theme;

$docsDark = Theme::dark();

$itemProps = [
['variant', '"simple" | "article"', 'simple', "Jenis tampilan kartu: 'simple' (daftar berkas) atau 'article' (kartu diskusi/artikel)."],
['title', 'string | Htmlable', 'required', 'Judul utama berkas atau item dokumen.'],
['subject', 'string | Htmlable', 'null', 'Nama subjek / penulis di kiri atas (varian article).'],
['category', 'string | Htmlable', 'null', 'Kategori topik di dalam blok metadata rata kiri pada sisi kanan header (varian article).'],
['timestamp', 'string | Htmlable', 'null', 'Tanggal / waktu publikasi di bawah kategori, rata kiri dalam blok metadata header (varian article).'],
['pinned', 'bool', 'true', 'Menampilkan lencana pin bulat berorientasi ke kanan di kanan atas (varian article).'],
['pin-attributes', 'array', '[]', "Atribut untuk lencana pin, mis. ['wire:click' => 'sematkan'], yang menjadikannya tombol; padanan onPin (varian article)."],
['views', 'string | int | Htmlable', 'null', 'Jumlah tayangan / views di kanan bawah (varian article).'],
['comments', 'string | int | Htmlable', 'null', 'Jumlah komentar di kanan bawah (varian article).'],
['likes', 'string | int | Htmlable', 'null', 'Jumlah suka / likes di kanan bawah (varian article).'],
['description', 'string | Htmlable', 'null', 'Keterangan atau teks deskripsi tambahan di bawah judul.'],
['metadata', 'array', '[]', "Daftar metadata ringkas yang otomatis digabung dengan tanda pemisah titik tengah (·). Contoh: ['PDF', '2.4 MB', 'Updated Oct 10, 2026']."],
['icon', 'string | Htmlable | false', 'ikon file-lines', 'Elemen visual di sisi kiri. Bernilai false jika tidak ingin menampilkan wadah ikon sama sekali.'],
['platform', '"desktop" | "mobile"', 'desktop', "Platform tampilan kartu: 'desktop' (lebar 410–600 px) atau 'mobile' (ringkas 348 px)."],
['decoration', 'bool', 'false', 'Menampilkan garis dekorasi aksen vertikal warna primary pada sisi kiri kartu (hanya varian simple; selalu aktif pada article).'],
['badge', 'string | slot', 'null', 'Badge atau elemen label status tambahan yang ditampilkan di bawah metadata.'],
['action-label', 'string | Htmlable', 'null', 'Label untuk tombol aksi cepat di sisi kanan (otomatis merender Button filled bawaan).'],
['action-wire-click', 'string', 'null', 'Method Livewire tombol aksi; padanan onAction. Dengan action-disabled dan tanpa href/klik item, aksinya dialihkan ke seluruh item.'],
['action-click', 'string', 'null', 'Ekspresi Alpine tombol aksi (x-on:click); padanan onAction.'],
['action-attributes', 'array', '[]', 'Atribut tambahan untuk tombol aksi.'],
['action-href', 'string', 'null', 'Alamat tombol aksi. Dengan action-disabled dan tanpa href/klik item, tautan dialihkan ke seluruh item.'],
['action-disabled', 'bool', 'false', 'Menyembunyikan tombol aksi bawaan pada varian simple. Jika action-href atau aksi tersedia dan item belum memiliki href/klik, aksinya dialihkan ke seluruh item; href/klik eksplisit tetap diprioritaskan.'],
['interactive-feedback', 'bool', 'true', 'Menampilkan feedback border, shadow, dan scale pada item simple yang interaktif. Tidak mengubah clickability item.'],
['action-button-size', '"xs" | "s" | "base" | "l" | "xl"', 'null', 'Ukuran tombol aksi bawaan. Mengikuti prop size kartu bila tidak ditentukan.'],
['size', '"s" | "m" | "l" | "xl" | "sm" | "default" | "lg"', 'm / default', 'Kerapatan ukuran visual item: S (ringkas), M (standar), atau L (lapang). XL hanya untuk varian article.'],
['href', 'string', 'null', 'Alamat tautan jika seluruh kartu item bertindak sebagai link interaktif.'],
['target / rel', 'string', 'null', "Atribut tautan untuk href; target _blank tanpa rel otomatis memakai rel='noreferrer noopener'."],
['dark-mode', 'bool', 'false', 'Menyetel kartu item ke tema gelap secara eksplisit.'],
['content', 'Htmlable | string | slot', 'null', 'Isi tambahan di dalam kolom teks: di bawah badge (simple) atau di bawah judul (article); padanan children. Pada mode satu item lewat slot.'],
['as', 'string', 'article / a / button', 'Elemen HTML kartu (kunci items), mis. li di dalam kontainer as="ul"; padanan as.'],
['attributes', 'array', '[]', "Atribut pada kartu (kunci items), mis. ['wire:click' => 'pilih']; padanan onClick. Item simple yang bisa diklik tanpa href dirender sebagai <button>, kecuali tombol aksinya tampil."],
];

$containerProps = [
['items', 'array', 'null', 'Array data item jika ingin merender daftar berkas secara otomatis. Kuncinya sama dengan prop item dalam camelCase.'],
['gap', '"none" | "sm" | "md" | "lg"', 'md', 'Jarak vertikal antar item dalam daftar berkas.'],
['platform', '"desktop" | "mobile"', 'desktop', 'Platform bawaan yang diwariskan ke semua item di dalamnya.'],
['decoration', 'bool', 'false', 'Garis dekorasi aksen bawaan yang diwariskan ke semua item di dalamnya.'],
['size', '"s" | "m" | "l" | "sm" | "default" | "lg"', 'm / default', 'Ukuran bawaan yang diwariskan ke semua item di dalamnya.'],
['dark-mode', 'bool', 'false', 'Menyetel seluruh daftar ke tema gelap.'],
['slot', 'slot', '—', 'Isi bebas saat items dan title tidak diisi; bila title diisi, slot menjadi isi tambahan item (content).'],
['as', 'string', 'div', 'Elemen HTML pembungkus kontainer.'],
];

// Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
// dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
// gelap di kedua mode (lihat App\Docs\Theme).
$docsDark = \App\Docs\Theme::dark();
@endphp

<x-docs.layout title="List View">
    <x-docs.page eyebrow="Components · List View" title="List View"
        description="Komponen kartu daftar vertikal untuk menampilkan ringkasan berkas, lampiran, dokumen, atau item data lengkap dengan ikon, judul, metadata, dan aksi cepat."
        :toc="[
            'variant' => 'Variant',
            'article' => 'List View Article',
            'decoration' => 'Decoration',
            'badge' => 'Badge',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]">
        <x-docs.section id="variant" title="Variant">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Varian terbagi untuk platform <span class="text-primary-500 dark:text-primary-400">desktop</span> yang menyediakan 3 pilihan ukuran
                <span class="text-primary-500 dark:text-primary-400">(S, M, L)</span> serta tombol aksi di sisi kanan, dan
                <span class="text-primary-500 dark:text-primary-400">mobile</span> yang ringkas dan terstandardisasi untuk layar perangkat genggam.
            </p>

            <div class="space-y-8">
                <div>
                    <h3 class="mb-4 text-base font-bold text-gray-900 dark:text-white">1. Desktop</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="mb-1 text-sm font-bold text-gray-900 dark:text-white">Small (S)</h4>
                            <p class="mb-3 text-body-sm text-gray-500 dark:text-gray-400">Ukuran ringkas (410 px) dengan wadah ikon 32×32 px dan
                                tombol aksi ukuran XS.</p>
                            <x-docs.demo min-width="410px">
                                <div class="w-full max-w-[410px]">
                                    <x-ceplok::list-view size="s" title="Project brief"
                                        :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" :dark-mode="$docsDark" />
                                </div>
                            </x-docs.demo>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-bold text-gray-900 dark:text-white">Medium (M) · Default</h4>
                            <p class="mb-3 text-body-sm text-gray-500 dark:text-gray-400">Ukuran standar bawaan (514 px) dengan wadah ikon 40×40 px dan
                                tombol aksi ukuran S.</p>
                            <x-docs.demo min-width="514px">
                                <div class="w-full max-w-[514px]">
                                    <x-ceplok::list-view title="Project brief"
                                        :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" :dark-mode="$docsDark" />
                                </div>
                            </x-docs.demo>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-bold text-gray-900 dark:text-white">Large (L)</h4>
                            <p class="mb-3 text-body-sm text-gray-500 dark:text-gray-400">Ukuran lapang (600 px) dengan wadah ikon 48×48 px dan
                                tombol aksi ukuran Base.</p>
                            <x-docs.demo min-width="600px">
                                <div class="w-full max-w-[600px]">
                                    <x-ceplok::list-view size="l" title="Project brief"
                                        :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" :dark-mode="$docsDark" />
                                </div>
                            </x-docs.demo>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="mb-2 text-base font-bold text-gray-900 dark:text-white">2. Mobile</h3>
                    <p class="mb-3 text-body-sm text-gray-500 dark:text-gray-400">Platform mobile hanya memiliki 1 ukuran standar (lebar 348 px,
                        tinggi 58 px). Wadah ikon 40×40 px dengan layout satu baris ringkas.</p>
                    <x-docs.demo min-width="348px">
                        <div class="w-full max-w-[348px]">
                            <x-ceplok::list-view platform="mobile" title="Project brief"
                                :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" :dark-mode="$docsDark" />
                        </div>
                    </x-docs.demo>
                </div>
            </div>

            <x-docs.code>@verbatim
                {{-- Desktop S (410 px) --}}
                <x-ceplok::list-view ⟦size⟧="s" title="Project brief" :metadata="['PDF', '2.4 MB']"
                    action-label="Open"⟨ dark-mode⟩ />

                {{-- Desktop M · default (514 px) --}}
                <x-ceplok::list-view title="Project brief" :metadata="['PDF', '2.4 MB']" action-label="Open"⟨ dark-mode⟩ />

                {{-- Desktop L (600 px) --}}
                <x-ceplok::list-view ⟦size⟧="l" title="Project brief" :metadata="['PDF', '2.4 MB']"
                    action-label="Open"⟨ dark-mode⟩ />

                {{-- Mobile · ukuran standar (348 px) --}}
                <x-ceplok::list-view ⟦platform⟧="mobile" title="Project brief" :metadata="['PDF', '2.4 MB']"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="article" title="List View Article">
            <p class="mb-6 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Varian <span class="text-primary-500 dark:text-primary-400">variant="article"</span> untuk artikel, diskusi, atau utas topik.
                Garis aksen selalu aktif; header memuat <span class="text-primary-500 dark:text-primary-400">subject</span>, metadata, dan pin,
                sementara judul semibold serta metrik berada di bawah.
            </p>
            <div class="space-y-6">
                @foreach ([
                    ['size' => 's', 'width' => 'max-w-[410px]', 'label' => 'Small (S)', 'description' => 'Ukuran ringkas (410 px) dengan judul semibold 14px dan metrik interaksi 12px.'],
                    ['size' => 'm', 'width' => 'max-w-[514px]', 'label' => 'Medium (M) · Default', 'description' => 'Ukuran standar bawaan (514 px) dengan judul semibold 16px dan proporsi yang seimbang.'],
                    ['size' => 'l', 'width' => 'max-w-[728px]', 'label' => 'Large (L)', 'description' => 'Ukuran lapang (728 px) dengan judul semibold 18px untuk keterbacaan yang lebih tinggi.'],
                    ['size' => 'xl', 'width' => 'max-w-[1028px]', 'label' => 'Extra Large (XL)', 'description' => 'Ukuran ekstra besar (1028 px) dengan judul semibold 20px dan bantalan lebih luas, ideal untuk judul panjang atau artikel sorotan utama.'],
                ] as $articleVariant)
                    @php($size = $articleVariant['size'])
                <div>
                    <h4 class="mb-2 text-sm font-bold text-gray-900 dark:text-white">{{ $articleVariant['label'] }}</h4>
                    <p class="mb-3 text-body-sm text-gray-500 dark:text-gray-400">{{ $articleVariant['description'] }}</p>
                    <x-docs.demo :min-width="match ($size) { 's' => '410px', 'm' => '514px', 'l' => '728px', 'xl' => '1028px' }">
                        <div class="w-full {{ $articleVariant['width'] }}">
                            <x-ceplok::list-view variant="article" :size="$size" subject="subject Name" category="Category"
                                timestamp="12 Okt 2026 09:59 WIB"
                                :title="$size === 'xl' ? 'Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi. Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi.' : 'Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi'"
                                :views="609" :comments="3" :likes="16" :dark-mode="$docsDark" />
                        </div>
                    </x-docs.demo>
                </div>
                @endforeach
            </div>
            <x-docs.code>@verbatim
                {{-- Article S (410 px) --}}
                <x-ceplok::list-view variant="article" ⟦size⟧="s" subject="subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16"⟨ dark-mode⟩ />

                {{-- Article M · default (514 px) --}}
                <x-ceplok::list-view variant="article" subject="subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16"⟨ dark-mode⟩ />

                {{-- Article L (728 px) --}}
                <x-ceplok::list-view variant="article" ⟦size⟧="l" subject="subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16"⟨ dark-mode⟩ />

                {{-- Article XL (1028 px) --}}
                <x-ceplok::list-view variant="article" ⟦size⟧="xl" subject="subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="decoration" title="Decoration">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">Prop <span class="text-primary-500 dark:text-primary-400">decoration</span> menyematkan garis aksen vertikal warna primary pada sisi kiri
                kartu untuk memberikan aksentuasi visual atau penanda status berkas.</p>
            <div class="space-y-5">
                <x-docs.demo label="Desktop (Medium)" min-width="514px">
                    <div class="w-full max-w-[514px]">
                        <x-ceplok::list-view decoration title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile" min-width="348px">
                    <div class="w-full max-w-[348px]">
                        <x-ceplok::list-view platform="mobile" decoration title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>@verbatim
                {{-- Tanpa decoration · default --}}
                <x-ceplok::list-view title="Project brief" :metadata="['PDF', '2.4 MB']" action-label="Open"⟨ dark-mode⟩ />

                {{-- Dengan decoration --}}
                <x-ceplok::list-view ⟦decoration⟧ title="Project brief" :metadata="['PDF', '2.4 MB']"
                    action-label="Open"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="badge" title="Badge">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">Prop <span class="text-primary-500 dark:text-primary-400">badge</span> menyisipkan elemen label status di bawah baris metadata dokumen.
                Tata letak vertikal kartu dan wadah ikon otomatis menyesuaikan proporsinya agar tetap seimbang.</p>
            <div class="space-y-5">
                <x-docs.demo label="Desktop (Medium)" min-width="514px">
                    <div class="w-full max-w-[514px]">
                        <x-ceplok::list-view title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" :dark-mode="$docsDark">
                            <x-slot:badge>
                                <x-ceplok::badge variant="success" size="sm" :dark-mode="$docsDark" :icon="svg('ceplok-s-clock')">Badge
                                </x-ceplok::badge>
                            </x-slot:badge>
                        </x-ceplok::list-view>
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile" min-width="348px">
                    <div class="w-full max-w-[348px]">
                        <x-ceplok::list-view platform="mobile" title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" :dark-mode="$docsDark">
                            <x-slot:badge>
                                <x-ceplok::badge variant="success" size="sm" :dark-mode="$docsDark" :icon="svg('ceplok-s-clock')">Badge
                                </x-ceplok::badge>
                            </x-slot:badge>
                        </x-ceplok::list-view>
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>@verbatim
                {{-- Desktop · Dengan badge --}}
                <x-ceplok::list-view title="Project brief" :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']"
                    action-label="Open"⟨ dark-mode⟩>
                    <x-slot:⟦badge⟧>
                        <x-ceplok::badge variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                        </x-ceplok::badge>
                        </x-slot:badge>
                </x-ceplok::list-view>

                {{-- Mobile · Dengan badge --}}
                <x-ceplok::list-view ⟦platform⟧="mobile" title="Project brief"
                    :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']"⟨ dark-mode⟩>
                    <x-slot:⟦badge⟧>
                        <x-ceplok::badge variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                        </x-ceplok::badge>
                        </x-slot:badge>
                </x-ceplok::list-view>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengaktifkan tema gelap. Article memakai surface <span class="text-primary-500 dark:text-primary-400">gray-700</span>, teks bertingkat <span class="text-primary-500 dark:text-primary-400">gray-100</span> hingga <span class="text-primary-500 dark:text-primary-400">gray-300</span>,
                serta pin transparan beraksen primary.</x-slot:lead>
            <div class="space-y-5">
                <x-docs.demo dark label="Desktop" min-width="514px">
                    <div class="w-full max-w-[514px]">
                        <x-ceplok::list-view dark-mode decoration title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open">
                            <x-slot:badge>
                                <x-ceplok::badge dark-mode variant="success" size="sm" :icon="svg('ceplok-s-clock')">
                                    Badge</x-ceplok::badge>
                            </x-slot:badge>
                        </x-ceplok::list-view>
                    </div>
                </x-docs.demo>
                <x-docs.demo dark label="Mobile" min-width="348px">
                    <div class="w-full max-w-[348px]">
                        <x-ceplok::list-view dark-mode platform="mobile" decoration title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']">
                            <x-slot:badge>
                                <x-ceplok::badge dark-mode variant="success" size="sm" :icon="svg('ceplok-s-clock')">
                                    Badge</x-ceplok::badge>
                            </x-slot:badge>
                        </x-ceplok::list-view>
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>@verbatim
                {{-- Desktop · dark mode, decoration, dan badge --}}
                <x-ceplok::list-view ⟦dark-mode⟧ ⟦decoration⟧ title="Project brief" :metadata="['PDF', '2.4 MB']"
                    action-label="Open">
                    <x-slot:⟦badge⟧>
                        <x-ceplok::badge dark-mode variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                        </x-ceplok::badge>
                        </x-slot:badge>
                </x-ceplok::list-view>

                {{-- Mobile · dark mode, decoration, dan badge --}}
                <x-ceplok::list-view ⟦platform⟧="mobile" ⟦dark-mode⟧ ⟦decoration⟧ title="Project brief"
                    :metadata="['PDF', '2.4 MB']">
                    <x-slot:⟦badge⟧>
                        <x-ceplok::badge dark-mode variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                        </x-ceplok::badge>
                        </x-slot:badge>
                </x-ceplok::list-view>
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.list-view :dark="$docsDark" />
        <livewire:playground.list-view-livewire :dark="$docsDark" />

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">Referensi lengkap prop yang didukung oleh item List View dan kontainer pembungkusnya.</p>
            <div class="space-y-8">
                <div>
                    <h3 class="mb-3 text-sm font-bold text-gray-900 dark:text-white">List View Item Props</h3>
                    <x-docs.props-table :rows="$itemProps" min-width="46rem" />
                </div>
                <div>
                    <h3 class="mb-3 text-sm font-bold text-gray-900 dark:text-white">List View (Container) Props</h3>
                    <x-docs.props-table :rows="$containerProps" min-width="46rem" />
                </div>
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
