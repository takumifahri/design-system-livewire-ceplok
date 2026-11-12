@php
$itemProps = [
['variant', '"simple" | "article"', 'simple', 'Jenis tampilan: item berkas sederhana atau kartu artikel/diskusi.'],
['title', 'string | Htmlable', 'required', 'Judul utama item.'],
['subject', 'string | Htmlable', 'null', 'Nama subjek atau penulis pada varian article.'],
['category', 'string | Htmlable', 'null', 'Kategori pada varian article.'],
['timestamp', 'string | Htmlable', 'null', 'Tanggal atau waktu publikasi pada varian article.'],
['pinned', 'bool', 'true', 'Menampilkan penanda pin pada varian article.'],
['views', 'string | int | Htmlable', 'null', 'Jumlah tayangan pada varian article.'],
['comments', 'string | int | Htmlable', 'null', 'Jumlah komentar pada varian article.'],
['likes', 'string | int | Htmlable', 'null', 'Jumlah suka pada varian article.'],
['description', 'string | Htmlable', 'null', 'Keterangan tambahan di bawah judul item simple.'],
['metadata', 'array', '[]', 'Metadata ringkas, mis. PDF, ukuran berkas, atau tanggal pembaruan.'],
['icon', 'string | Htmlable | false', 'null', 'Ikon kiri; false untuk menyembunyikan wadah ikon.'],
['platform', '"desktop" | "mobile"', 'desktop', 'Platform item simple. Mobile memakai layout ringkas.'],
['size', '"s" | "m" | "l" | "xl"', 'm', 'Ukuran kartu. XL tersedia untuk varian article.'],
['decoration', 'bool', 'false', 'Garis aksen vertikal; selalu ada pada varian article.'],
['badge', 'slot', 'null', 'Label status di bawah metadata pada varian simple.'],
['action-label', 'string | Htmlable', 'null', 'Label aksi cepat di sisi kanan item simple.'],
['action-href', 'string', 'null', 'Tujuan tautan tombol aksi.'],
['action-disabled', 'bool', 'false', 'Pada simple desktop, menyembunyikan tombol dan memindahkan URL/event aksinya ke seluruh kartu.'],
['action-wire-click', 'string', 'null', 'Method Livewire yang dipanggil saat tombol aksi diklik.'],
['href', 'string', 'null', 'Menjadikan seluruh kartu sebagai tautan.'],
['dark-mode', 'bool', 'false', 'Mengaktifkan tema gelap secara eksplisit.'],
];

$containerProps = [
['items', 'array', 'null', 'Array data untuk merender item secara otomatis.'],
['gap', '"none" | "sm" | "md" | "lg"', 'md', 'Jarak vertikal antar item.'],
['platform', '"desktop" | "mobile"', 'desktop', 'Platform bawaan yang diteruskan ke setiap item data-driven.'],
['size', '"s" | "m" | "l" | "xl"', 'm', 'Ukuran bawaan yang diteruskan ke setiap item data-driven.'],
['decoration', 'bool', 'null', 'Garis aksen bawaan yang diteruskan ke setiap item data-driven.'],
['dark-mode', 'bool', 'false', 'Menyetel tema gelap untuk kontainer dan item data-driven.'],
['as', 'string', 'div', 'Elemen HTML pembungkus List View.'],
];
@endphp

<x-docs.layout title="List View">
    <x-docs.page eyebrow="Components · List View" title="List View"
        description="Komponen kartu daftar vertikal untuk menampilkan ringkasan berkas, lampiran, dokumen, atau item data lengkap dengan ikon, judul, metadata, dan aksi cepat."
        :toc="[
            'variant' => 'Variant',
            'article' => 'List View Article',
            'decoration' => 'Decoration',
            'badge' => 'Badge',
            'darkmode' => 'Darkmode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]">
        <x-docs.section id="variant" title="Variant">
            <p class="mb-6 text-body-sm text-gray-500">
                Varian <span class="text-primary-500">desktop</span> tersedia dalam ukuran S, M, dan L dengan aksi di
                sisi kanan. Varian
                <span class="text-primary-500">mobile</span> menggunakan satu layout ringkas untuk perangkat genggam.
            </p>

            <div class="space-y-8">
                <div>
                    <h3 class="mb-4 text-base font-bold text-gray-900">1. Desktop</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="mb-1 text-sm font-bold text-gray-900">Small (S)</h4>
                            <p class="mb-3 text-body-sm text-gray-500">Ukuran ringkas dengan wadah ikon 32 × 32 px dan
                                tombol aksi XS.</p>
                            <x-docs.demo min-width="410px">
                                <div class="w-full max-w-[410px]">
                                    <x-ceplok::list-view size="s" title="Project brief"
                                        :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" />
                                </div>
                            </x-docs.demo>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-bold text-gray-900">Medium (M) · Default</h4>
                            <p class="mb-3 text-body-sm text-gray-500">Ukuran standar dengan wadah ikon 40 × 40 px dan
                                tombol aksi S.</p>
                            <x-docs.demo min-width="514px">
                                <div class="w-full max-w-[514px]">
                                    <x-ceplok::list-view title="Project brief"
                                        :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" />
                                </div>
                            </x-docs.demo>
                        </div>
                        <div>
                            <h4 class="mb-1 text-sm font-bold text-gray-900">Large (L)</h4>
                            <p class="mb-3 text-body-sm text-gray-500">Ukuran lapang dengan wadah ikon 48 × 48 px dan
                                tombol aksi Base.</p>
                            <x-docs.demo min-width="600px">
                                <div class="w-full max-w-[600px]">
                                    <x-ceplok::list-view size="l" title="Project brief"
                                        :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" />
                                </div>
                            </x-docs.demo>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="mb-2 text-base font-bold text-gray-900">2. Mobile</h3>
                    <p class="mb-3 text-body-sm text-gray-500">Mobile menggunakan satu ukuran standar dengan layout satu
                        baris yang ringkas.</p>
                    <x-docs.demo min-width="348px">
                        <div class="w-full max-w-[348px]">
                            <x-ceplok::list-view platform="mobile" title="Project brief"
                                :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" />
                        </div>
                    </x-docs.demo>
                </div>
            </div>

            <x-docs.code>@verbatim
                {{-- Desktop S (410 px) --}}
                <x-ceplok::list-view ⟦size⟧="s" title="Project brief" :metadata="['PDF', '2.4 MB']"
                    action-label="Open" />

                {{-- Desktop M · default (514 px) --}}
                <x-ceplok::list-view title="Project brief" :metadata="['PDF', '2.4 MB']" action-label="Open" />

                {{-- Desktop L (600 px) --}}
                <x-ceplok::list-view ⟦size⟧="l" title="Project brief" :metadata="['PDF', '2.4 MB']"
                    action-label="Open" />

                {{-- Mobile · ukuran standar (348 px) --}}
                <x-ceplok::list-view ⟦platform⟧="mobile" title="Project brief" :metadata="['PDF', '2.4 MB']" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="article" title="List View Article">
            <p class="mb-6 max-w-2xl text-body-sm text-gray-500">
                Varian <span class="text-primary-500">variant="article"</span> untuk artikel, diskusi, atau utas topik.
                Garis aksen selalu aktif; header memuat <span class="text-primary-500">subject</span>, metadata, dan pin,
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
                    <h4 class="mb-2 text-sm font-bold text-gray-900">{{ $articleVariant['label'] }}</h4>
                    <p class="mb-3 text-body-sm text-gray-500">{{ $articleVariant['description'] }}</p>
                    <x-docs.demo :min-width="match ($size) { 's' => '410px', 'm' => '514px', 'l' => '728px', 'xl' => '1028px' }">
                        <div class="w-full {{ $articleVariant['width'] }}">
                            <x-ceplok::list-view variant="article" :size="$size" subject="Subject Name" category="Category"
                                timestamp="12 Okt 2026 09:59 WIB"
                                :title="$size === 'xl' ? 'Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi. Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi.' : 'Antusiasme dan Tindaklanjut Webinar Deep Talk untuk meningkatkan kekuatan organisasi'"
                                :views="609" :comments="3" :likes="16" />
                        </div>
                    </x-docs.demo>
                </div>
                @endforeach
            </div>
            <x-docs.code>@verbatim
                {{-- Article S (410 px) --}}
                <x-ceplok::list-view variant="article" ⟦size⟧="s" subject="Subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16" />

                {{-- Article M · default (514 px) --}}
                <x-ceplok::list-view variant="article" subject="Subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16" />

                {{-- Article L (728 px) --}}
                <x-ceplok::list-view variant="article" ⟦size⟧="l" subject="Subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16" />

                {{-- Article XL (1028 px) --}}
                <x-ceplok::list-view variant="article" ⟦size⟧="xl" subject="Subject Name" category="Category"
                    timestamp="12 Okt 2026 09:59 WIB" title="Antusiasme dan Tindaklanjut Webinar Deep Talk" :views="609"
                    :comments="3" :likes="16" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="decoration" title="Decoration">
            <p class="mb-6 text-body-sm text-gray-500">Prop <span class="text-primary-500">decoration</span> menambahkan
                garis aksen vertikal pada sisi kiri kartu sebagai penanda visual.</p>
            <div class="space-y-5">
                <x-docs.demo label="Desktop" min-width="514px">
                    <div class="w-full max-w-[514px]">
                        <x-ceplok::list-view decoration title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile" min-width="348px">
                    <div class="w-full max-w-[348px]">
                        <x-ceplok::list-view platform="mobile" decoration title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>@verbatim
                {{-- Tanpa decoration · default --}}
                <x-ceplok::list-view title="Project brief" :metadata="['PDF', '2.4 MB']" action-label="Open" />

                {{-- Dengan decoration --}}
                <x-ceplok::list-view ⟦decoration⟧ title="Project brief" :metadata="['PDF', '2.4 MB']"
                    action-label="Open" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="badge" title="Badge">
            <p class="mb-6 text-body-sm text-gray-500">Slot <span class="text-primary-500">badge</span> menempatkan
                label status di bawah metadata dan menyesuaikan tinggi kartu secara otomatis.</p>
            <div class="space-y-5">
                <x-docs.demo label="Desktop" min-width="514px">
                    <div class="w-full max-w-[514px]">
                        <x-ceplok::list-view title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']" action-label="Open">
                            <x-slot:badge>
                                <x-ceplok::badge variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                                </x-ceplok::badge>
                            </x-slot:badge>
                        </x-ceplok::list-view>
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile" min-width="348px">
                    <div class="w-full max-w-[348px]">
                        <x-ceplok::list-view platform="mobile" title="Project brief"
                            :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']">
                            <x-slot:badge>
                                <x-ceplok::badge variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                                </x-ceplok::badge>
                            </x-slot:badge>
                        </x-ceplok::list-view>
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>@verbatim
                {{-- Desktop · Dengan badge --}}
                <x-ceplok::list-view title="Project brief" :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']"
                    action-label="Open">
                    <x-slot:⟦badge⟧>
                        <x-ceplok::badge variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                        </x-ceplok::badge>
                        </x-slot:badge>
                </x-ceplok::list-view>

                {{-- Mobile · Dengan badge --}}
                <x-ceplok::list-view ⟦platform⟧="mobile" title="Project brief"
                    :metadata="['PDF', '2.4 MB', 'Updated Oct 10, 2026']">
                    <x-slot:⟦badge⟧>
                        <x-ceplok::badge variant="success" size="sm" :icon="svg('ceplok-s-clock')">Badge
                        </x-ceplok::badge>
                        </x-slot:badge>
                </x-ceplok::list-view>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="darkmode" title="Darkmode">
            <p class="mb-6 text-body-sm text-gray-500">Prop <span class="text-primary-500">dark-mode</span> menyesuaikan
                surface, teks, ikon, badge, dan border untuk latar gelap.</p>
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
        </x-docs.section>

        <livewire:playground.list-view />
        <livewire:playground.list-view-livewire />

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500">Referensi prop untuk item dan kontainer List View.</p>
            <div class="space-y-8">
                <div>
                    <h3 class="mb-3 text-sm font-bold text-gray-900">List View Item</h3>
                    <x-docs.props-table :rows="$itemProps" min-width="46rem" />
                </div>
                <div>
                    <h3 class="mb-3 text-sm font-bold text-gray-900">List View Container</h3>
                    <x-docs.props-table :rows="$containerProps" min-width="46rem" />
                </div>
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
