@php
    // Isi contoh diambil apa adanya dari frame Hero di desain.
    $heading = 'Hero Design system Ceplok';
    $subHeading = 'Hero Design system Ceplok';
    $description = 'Design System Ceplok adalah sebuah kumpulan standar desain terpadu yang digunakan untuk memastikan seluruh produk digital di lingkungan Kementerian Komunikasi dan Informatika (Ceplok)';
    $image = '/images/hero-sample.svg';
    $imageLandscape = '/images/hero-sample-landscape.svg';
    $imageOnly = '/images/Placeholder/hero.png';

    // Angka-angka dari panel Layout di desain, berpasangan dengan wujudnya di kode.
    $spacing = [
        ['Lebar bingkai', 'Fixed 1,440px → max-w-[1440px] lalu dipusatkan'],
        ['Padding atas-bawah', '24px → py-6, sama di ketiga tipe'],
        ['Padding kiri-kanan', '0px di desain — isinya ditahan wadah 1290px yang dipusatkan. Di kode jadi jarak tepi bertingkat 20 → 32 → 48 → 76px, supaya di layar sempit teks tidak menempel ke tepi'],
        ['Tinggi tipe horizontal', 'Fixed 762px → min-h-[762px] mulai lebar 1024px'],
        ['Tinggi tipe centered', 'Hug (474px) → tumbuh dari isinya; rongga atas-bawah teks 64px, 96px mulai 768px'],
        ['Jarak teks ↔ gambar', '72px saat bertumpuk, 48px mulai 768px, melebar jadi 96px mulai 1280px'],
        ['Jarak antar elemen teks', 'sub heading 8px, deskripsi 16px, tombol 32px dari elemen di atasnya'],
        ['Lebar gambar potret', 'maks 270px dan dipusatkan saat bertumpuk, 320px mulai 768px, 386px mulai 1024px — tinggi selalu mengikuti rasio aslinya'],
        ['Lebar gambar lanskap', 'selebar ruang saat bertumpuk, 380px mulai 768px, 435px mulai 1024px — dipilih lewat image-orientation="landscape"'],
        ['Susunan mobile', 'teks rata tengah dan tombol selebar penuh; keduanya berbalik ke rata kiri dan lebar isi mulai 768px. Tipe centered tetap rata tengah dengan tombol seukuran isinya'],
    ];

    $responsive = [
        ['Sempit (< 768px)', 'Susunan mobile: bertumpuk satu kolom, teks rata tengah, tombol selebar penuh, lalu gambar dipusatkan dengan lebar maksimum 270px. Jarak tombol ke gambar 72px, judul memakai ukuran heading-1, dan batas tinggi 762px dilepas — di susunan bertumpuk tinggi itu hanya jadi rongga kosong.'],
        ['Lebar (≥ 768px)', 'Susunan jadi dua kolom seperti di desain: teks pindah ke rata kiri, tombol menyusut ke selebar isinya, gambar 320px menempel di tepi luar, dan keduanya rata tengah secara vertikal. Ambangnya sama dengan Footer, jadi kedua komponen berganti susunan di titik yang sama.'],
        ['Sangat lebar (≥ 1024px)', 'Gambar melebar ke 386px, judul naik ke ukuran display (48px), dan tinggi minimum 762px sesuai desain mulai berlaku. Mulai 1280px jarak antarkolom melebar jadi 96px dan jarak tepi jadi 76px — sama dengan jarak isi ke tepi pada bingkai 1440px di desain. Lebar isi dibatasi 1440px lalu dipusatkan.'],
    ];

    $props = [
        ['type', '"horizontal-image-left" | "horizontal-image-right" | "centered"', 'horizontal-image-left', 'Susunan hero, sama dengan properti "Hero Type" di desain. Dua tipe horizontal menaruh gambar di kiri atau di kanan teks; centered melepas gambar, memusatkan teks, dan memakai latar primary-50.'],
        ['centered-content', '"text" | "image"', 'text', 'Isi tipe centered. image menampilkan image atau image-content saja di tengah; sub heading, deskripsi, dan tombol tidak dirender. heading tetap ada sebagai <h1> yang hanya dibacakan pembaca layar. Gambar harus kontras dengan latar hero (primary-50, atau gray-800 saat dark-mode). Diabaikan pada tipe horizontal.'],
        ['platform', '"desktop" | "mobile"', 'desktop', 'Properti "Platform" di desain. desktop membiarkan susunannya mengikuti lebar hero; mobile memaksa bertumpuk satu kolom dan menahan judul di ukuran heading-1 berapa pun lebar ruangnya.'],
        ['heading', 'string | slot', 'null', 'Judul utama — desain: "Hero Text". Dirender sebagai <h1>, jadi satu halaman sebaiknya hanya punya satu Hero ber-heading.'],
        ['sub-heading', 'string | slot', 'null', 'Baris kecil berwarna primary-600 tepat di bawah judul — desain: "Hero sub heading". Hanya tampil bila show-heading bernilai true.'],
        ['description', 'string | slot', 'null', 'Paragraf penjelas — desain: "Hero Desc". Lebarnya dibatasi max-w-2xl agar panjang barisnya tetap nyaman dibaca.'],
        ['show-heading', 'bool', 'true', 'Desain: "Show Heading". Yang dikendalikan adalah baris sub heading; judul utama tidak pernah disembunyikan karena hero tanpa judul membuat halaman kehilangan penanda utamanya.'],
        ['show-button', 'bool', 'true', 'Desain: "Show Button". Bila false, tombol beserta jarak atasnya tidak dirender sama sekali.'],
        ['button-label', 'string', 'Lihat Lebih Lanjut', 'Teks tombol; panah kanan ditambahkan otomatis.'],
        ['button-href', 'string | null', 'null', 'Bila diisi, tombolnya dirender sebagai <a href> — dipakai untuk tautan navigasi. Bila kosong, tombolnya <button>; pasang aksinya lewat button-attributes.'],
        ['button-attributes', 'array', '[]', 'Atribut tambahan untuk tombolnya — padanan onButtonClick, mis. [\'wire:click\' => \'mulai\']. class-nya disambung, tidak menimpa kelas tombol.'],
        ['image', 'string | null', 'null', 'URL gambar pendamping. Tingginya mengikuti rasio asli berkasnya — tidak dikunci aspect-ratio — dan lebarnya 320px saat hero ≥ 768px, melebar ke 386px saat ≥ 1024px. Pada type="centered" hanya dipakai bila centered-content="image".'],
        ['image-orientation', '"portrait" | "landscape"', 'portrait', 'Orientasi kolom gambar — di desain tiap tipe horizontal punya frame potret dan frame lanskap. Hanya mengatur lebar kolomnya (potret 386px, lanskap 435px); tinggi gambar tetap mengikuti rasio asli berkasnya, jadi kirim berkas yang orientasinya memang cocok.'],
        ['image-alt', 'string', "''", 'Teks alternatif gambar. Biarkan kosong bila gambarnya hanya hiasan, supaya tidak dibacakan dua kali bersama judul.'],
        ['image-content', 'slot', 'null', 'Gambar berupa elemen — ilustrasi <svg> inline atau komposisi sendiri. Dipakai hanya bila prop image kosong.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: latar gray-800 (surface-subtle, sama dengan Container) untuk ketiga tipe (termasuk centered), judul putih, sub heading primary-400, deskripsi gray-300. Tombol tetap primary-700. Hero di dalam pembungkus data-theme="dark" juga ikut gelap. Susunan dan ukurannya tidak berubah.'],
        ['…atribut', 'HTML', '—', 'Atribut untuk elemen <section> terluar (class, id, …) — umumnya untuk mengganti warna latar. Elemen ini sekaligus titik ukur @container, jadi hindari memberinya lebar tetap.'],
    ];
@endphp

<x-docs.layout title="Hero">
    <x-docs.page
        title="Hero"
        description="Blok pembuka halaman: judul, sub heading, deskripsi, satu tombol, dan gambar pendamping. Susunannya mengikuti lebar hero itu sendiri, bukan lebar layar."
        :toc="[
            'dasar' => 'Tampilan dasar',
            'variasi' => 'Variasi',
            'platform' => 'platform',
            'teks' => 'heading · sub-heading · description',
            'tombol' => 'show-button · button-label · button-href',
            'gambar' => 'image · image-orientation · image-content',
            'dark-mode' => 'Dark mode',
            'ukuran' => 'Ukuran & jarak',
            'responsif' => 'Perilaku responsif',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="dasar" title="Tampilan dasar">
            <x-docs.lead>
                Susunan bawaan <span class="text-primary-500">horizontal-image-left</span>: gambar di kiri, teks di
                kanan. Isi contoh di bawah diambil apa adanya dari frame Hero di desain
                — <span class="text-primary-500">Hero Text</span>, <span class="text-primary-500">Hero sub heading</span>, <span class="text-primary-500">Hero Desc</span>, dan
                tombol <em>Lihat Lebih Lanjut</em>. Semua bagiannya opsional: yang
                tidak diisi tidak dirender, bukan disembunyikan lewat CSS.
            </x-docs.lead>

            <x-docs.preview>
                <x-ceplok::hero type="horizontal-image-left" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" image-alt="" button-href="#" />
            </x-docs.preview>

            <x-docs.code>
                @verbatim
                <x-ceplok::hero
                    type="horizontal-image-left"
                    heading="Hero Design system Ceplok"
                    sub-heading="Hero Design system Ceplok"
                    description="Design System Ceplok adalah sebuah kumpulan standar desain terpadu yang digunakan untuk memastikan seluruh produk digital di lingkungan Kementerian Komunikasi dan Informatika (Ceplok)"
                    image="/images/hero-sample.svg"
                    button-href="/design-system"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="variasi" title="Variasi">
            <x-docs.lead>
                Ketiga nilai <span class="text-primary-500">type</span> memakai bagian isi yang sama; yang berbeda
                hanya letak gambar dan perataan teksnya. Nama tiap variasi di bawah
                sengaja disamakan dengan nama frame di desain agar mudah dicocokkan.
                Dua tipe horizontal masing-masing punya versi gambar potret dan
                lanskap lewat <span class="text-primary-500">image-orientation</span> — yang berubah hanya lebar kolom
                gambarnya, bukan rasio gambarnya. <span class="text-primary-500">centered</span> memusatkan isinya di
                atas latar <span class="text-primary-500">primary-50</span>. Isinya teks (bawaan) atau satu gambar
                saja lewat <span class="text-primary-500">centered-content="image"</span>.
            </x-docs.lead>

            <div class="space-y-6">
                <x-docs.preview label="Hero Horizontal Image Left — bawaan">
                    <x-ceplok::hero type="horizontal-image-left" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Hero Horizontal Image Right">
                    <x-ceplok::hero type="horizontal-image-right" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Hero Horizontal Image Left — gambar lanskap">
                    <x-ceplok::hero type="horizontal-image-left" image-orientation="landscape" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$imageLandscape" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Hero Horizontal Image Right — gambar lanskap">
                    <x-ceplok::hero type="horizontal-image-right" image-orientation="landscape" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$imageLandscape" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Hero Centered — tanpa gambar, tanpa sub heading">
                    <x-ceplok::hero type="centered" :heading="$heading" :show-heading="false" :description="$description" button-href="#" />
                </x-docs.preview>

                <div>
                    <x-docs.preview label="Hero Centered — image saja, di dalam mockup halaman">
                        <x-docs.page-mockup>
                            <x-ceplok::hero type="centered" centered-content="image" :heading="$heading" :image="$imageOnly" :image-alt="$heading" />
                        </x-docs.page-mockup>
                    </x-docs.preview>

                    <div class="ds-card mt-4 p-5">
                        <h3 class="text-sm font-black text-gray-900">Catatan: gambar harus terlihat jelas di atas latar</h3>
                        <ul class="mt-1.5 list-disc space-y-1 pl-5 text-body-sm leading-6 text-gray-500">
                            <li>Gambar tampil memenuhi hero. Tinggi hero mengikuti ukuran gambar.</li>
                            <li>
                                Bagian gambar yang transparan akan menampilkan warna latar: biru muda
                                (mode terang) atau abu gelap (mode gelap). Pastikan gambar tetap jelas
                                di atas warna itu.
                            </li>
                            <li>
                                Kalau ada tulisan di gambar, pastikan mudah dibaca, dengan kontras
                                minimal 4.5:1.
                            </li>
                            <li>
                                Kalau satu gambar tidak cocok untuk dua mode, pakai gambar berbeda untuk
                                mode terang dan gelap.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <x-docs.code>
                @verbatim
                {{-- Gambar di kiri teks — nilai bawaan type --}}
                <x-ceplok::hero ⟦*type⟧="horizontal-image-left"  image="/images/hero-sample.svg" … />

                {{-- Gambar di kanan teks --}}
                <x-ceplok::hero ⟦*type⟧="horizontal-image-right" image="/images/hero-sample.svg" … />

                {{-- Tanpa gambar, teks terpusat, latar primary-50 --}}
                <x-ceplok::hero ⟦*type⟧="centered"               :show-heading="false" … />

                {{-- Centered, isinya satu gambar saja --}}
                <x-ceplok::hero
                    ⟦*type⟧="centered"
                    ⟦*centered-content⟧="image"
                    heading="Hero Design system Ceplok"   {{-- jadi <h1> tersembunyi --}}
                    image="/images/banner.svg"
                    image-alt="Hero Design system Ceplok"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="platform">
            <x-docs.lead>
                <span class="text-primary-500">desktop</span> — bawaannya — membiarkan susunannya ditentukan lebar
                hero lewat <span class="text-primary-500">@container</span>: dua kolom mulai 768px, bertumpuk di
                bawahnya. <span class="text-primary-500">platform="mobile"</span> memaksa susunan bertumpuk itu
                berapa pun lebar ruangnya, sekaligus menahan judul di ukuran
                <span class="text-primary-500">heading-1</span> supaya tidak melompat ke <span class="text-primary-500">display</span>. Dipakai saat
                hero dirender di kerangka aplikasi mobile — bukan sekadar di jendela
                yang disempitkan, karena untuk kasus itu ambang <span class="text-primary-500">@container</span>
                sudah menanganinya sendiri.
            </x-docs.lead>

            {{-- Dibingkai 390px — lebar ponsel — karena hero mobile yang dipratinjau selebar kotak dokumentasi terbaca melar. --}}
            <x-docs.stage max-width="max-w-[390px]">
                <div class="overflow-hidden rounded-xl shadow-soft">
                    <x-ceplok::hero type="horizontal-image-left" platform="mobile" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" button-href="#" />
                </div>
            </x-docs.stage>

            <x-docs.code>
                @verbatim
                <x-ceplok::hero ⟦*platform⟧="mobile" … />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="teks" title="heading · sub-heading · description">
            <x-docs.lead>
                <span class="text-primary-500">heading</span> dirender sebagai <span class="text-primary-500">&lt;h1&gt;</span>, jadi satu halaman
                sebaiknya hanya memuat satu Hero ber-<span class="text-primary-500">heading</span>; hero kedua di
                halaman yang sama lebih baik dikosongkan judulnya. <span class="text-primary-500">sub-heading</span>
                adalah baris kecil berwarna <span class="text-primary-500">primary-600</span> di bawahnya, dan
                <span class="text-primary-500">:show-heading="false"</span> — namanya mengikuti properti
                <span class="text-primary-500">Show Heading</span> di desain — melepas baris itu. Judul utamanya
                sendiri tidak pernah bisa disembunyikan: hero tanpa judul membuat
                halaman kehilangan penanda utamanya bagi pembaca layar dan mesin
                pencari.
            </x-docs.lead>
            <x-docs.lead>
                <span class="text-primary-500">description</span> ditahan <span class="text-primary-500">max-w-2xl</span> (672px) agar barisnya
                tidak terlalu panjang untuk dibaca; di kolom yang lebih sempit — dua
                kolom pada tipe horizontal — batas itu tidak pernah aktif. Ketiganya
                bisa diisi lewat slot (<span class="text-primary-500">&lt;x-slot:heading&gt;</span>), jadi sebagian katanya boleh diberi warna
                lain atau disisipi tautan.
            </x-docs.lead>

            <x-docs.preview>
                <x-ceplok::hero type="horizontal-image-right" :sub-heading="$subHeading" :show-heading="false" :image="$image" :show-button="false">
                    <x-slot:heading>
                        Hero <span class="text-primary-700">Design system</span> Ceplok
                    </x-slot:heading>
                    <x-slot:description>
                        {{ $description }}. Rinciannya ada di
                        <a href="{{ route('foundations.index') }}" class="font-bold text-primary-700 underline">halaman Foundations</a>.
                    </x-slot:description>
                </x-ceplok::hero>
            </x-docs.preview>

            <x-docs.code>
                @verbatim
                <x-ceplok::hero :⟦*show-heading⟧="false">   {{-- baris sub-heading tidak dirender --}}
                    <x-slot:⟦*heading⟧>
                        Hero <span class="text-primary-700">Design system</span> Ceplok
                    </x-slot:heading>
                    <x-slot:⟦*description⟧>
                        Design System Ceplok … <a href="/foundations">halaman Foundations</a>.
                    </x-slot:description>
                </x-ceplok::hero>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="tombol" title="show-button · button-label · button-href">
            <x-docs.lead>
                Satu tombol saja — hero dengan dua tombol bersaing membuat pembaca
                ragu mana yang utama. <span class="text-primary-500">button-label</span> bawaannya
                <span class="text-primary-500">"Lihat Lebih Lanjut"</span> dan panah kanannya ditambahkan otomatis.
                Isi <span class="text-primary-500">button-href</span> bila tombolnya berpindah halaman — komponennya
                jadi merender <span class="text-primary-500">&lt;a href&gt;</span> sehingga alamatnya bisa disalin dan
                dibuka di tab baru; kalau kosong, tombolnya <span class="text-primary-500">&lt;button&gt;</span> yang aksinya
                dipasang lewat <span class="text-primary-500">button-attributes</span>, mis. <span class="text-primary-500">wire:click</span>.
                <span class="text-primary-500">:show-button="false"</span> melepas tombol beserta jarak atasnya.
            </x-docs.lead>

            <div class="space-y-6">
                <x-docs.preview label="button-label diganti">
                    <x-ceplok::hero type="horizontal-image-left" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" button-label="Mulai Pakai Design System" :button-href="route('components.index')" />
                </x-docs.preview>

                <x-docs.preview label="Tanpa tombol">
                    <x-ceplok::hero type="horizontal-image-left" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" :show-button="false" />
                </x-docs.preview>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::hero
                    ⟦*button-label⟧="Mulai Pakai Design System"
                    ⟦*button-href⟧="/components"
                />

                {{-- Tanpa href: tombol <button> yang memanggil aksi Livewire --}}
                <x-ceplok::hero :⟦*button-attributes⟧="['wire:click' => 'mulai']" … />

                <x-ceplok::hero :⟦*show-button⟧="false" … />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="gambar" title="image · image-orientation · image-content">
            <x-docs.lead>
                <span class="text-primary-500">image</span> menerima URL berkas dan dirender
                <strong class="text-gray-900">tanpa kunci rasio</strong>:
                tingginya mengikuti rasio asli berkasnya. Ini disengaja — mengunci
                <span class="text-primary-500">aspect-ratio</span> di sini membuat ilustrasi potret ikut dipipihkan
                dan teks di dalamnya jadi gepeng. Karena itu
                <span class="text-primary-500">image-orientation</span> pun tidak menyentuh rasio gambarnya sama
                sekali; yang diaturnya hanya lebar kolom — potret <span class="text-primary-500">386px</span>,
                lanskap <span class="text-primary-500">435px</span> — jadi kirim berkas yang orientasinya memang
                cocok.
            </x-docs.lead>
            <x-docs.lead>
                <span class="text-primary-500">image-alt</span> diisi hanya bila gambarnya membawa informasi; kalau
                cuma hiasan, biarkan kosong supaya tidak dibacakan dua kali bersama
                judul. Bila pendampingnya bukan berkas gambar — ilustrasi
                <span class="text-primary-500">&lt;svg&gt;</span> inline, kartu statistik, komposisi sendiri — pakai slot
                <span class="text-primary-500">image-content</span>; slot itu hanya berlaku saat <span class="text-primary-500">image</span> kosong,
                dan bila keduanya diisi <span class="text-primary-500">image</span> yang menang. Pada
                <span class="text-primary-500">type="centered"</span> gambar hanya dipakai bila
                <span class="text-primary-500">centered-content="image"</span>.
            </x-docs.lead>

            <x-docs.preview label="image-content — ilustrasi inline, bukan berkas">
                <x-ceplok::hero type="horizontal-image-left" :heading="$heading" :sub-heading="$subHeading" :description="$description" button-href="#">
                    <x-slot:image-content>
                        <div class="flex aspect-3/4 w-full items-center justify-center rounded-xl border-2 border-dashed border-primary-300 bg-primary-50">
                            <span class="text-sm font-black text-primary-700">Ilustrasi inline</span>
                        </div>
                    </x-slot:image-content>
                </x-ceplok::hero>
            </x-docs.preview>

            <x-docs.code>
                @verbatim
                <x-ceplok::hero
                    ⟦*image⟧="/images/hero-sample-landscape.svg"
                    ⟦*image-orientation⟧="landscape"
                    ⟦*image-alt⟧="Sampul panduan Designing Interfaces"
                />

                {{-- Pendamping berupa elemen — hanya dipakai bila image kosong --}}
                <x-ceplok::hero …>
                    <x-slot:⟦*image-content⟧>
                        <x-ilustrasi class="w-full" />
                    </x-slot:image-content>
                </x-ceplok::hero>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Isi <span class="text-primary-500">dark-mode</span> untuk halaman berlatar gelap. Ketiga tipe memakai
                latar <span class="text-primary-500">gray-800</span> (token surface-subtle, sama dengan Container), termasuk <span class="text-primary-500">centered</span> yang di tampilan
                terang berlatar <span class="text-primary-500">primary-50</span>. Judul jadi putih, sub heading
                <span class="text-primary-500">primary-400</span>, dan deskripsi <span class="text-primary-500">gray-300</span>. Tombolnya tetap
                <span class="text-primary-500">primary-700</span>. Susunan, ukuran, dan perilaku responsifnya sama
                persis dengan tampilan terang, termasuk gambar di kiri atau kanan.
            </x-docs.lead>

            <div class="space-y-6">
                <x-docs.preview label="Image Left — gelap" dark>
                    <x-ceplok::hero dark-mode type="horizontal-image-left" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Image Right — gelap" dark>
                    <x-ceplok::hero dark-mode type="horizontal-image-right" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Image Left — gambar lanskap, gelap" dark>
                    <x-ceplok::hero dark-mode type="horizontal-image-left" image-orientation="landscape" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$imageLandscape" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Image Right — gambar lanskap, gelap" dark>
                    <x-ceplok::hero dark-mode type="horizontal-image-right" image-orientation="landscape" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$imageLandscape" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Centered — gelap" dark>
                    <x-ceplok::hero dark-mode type="centered" :heading="$heading" :show-heading="false" :description="$description" button-href="#" />
                </x-docs.preview>

                <x-docs.preview label="Centered image saja — gelap, di dalam mockup halaman" dark>
                    <x-docs.page-mockup dark>
                        <x-ceplok::hero dark-mode type="centered" centered-content="image" :heading="$heading" :image="$imageOnly" :image-alt="$heading" />
                    </x-docs.page-mockup>
                </x-docs.preview>
            </div>

            <div class="mt-6 grid gap-6 sm:grid-cols-2">
                <x-docs.stage max-width="max-w-[390px]" dark>
                    <x-docs.hero-frame dark>
                        <x-ceplok::hero dark-mode platform="mobile" :heading="$heading" :sub-heading="$subHeading" :description="$description" :image="$image" button-href="#" />
                    </x-docs.hero-frame>
                </x-docs.stage>
                <x-docs.stage max-width="max-w-[390px]" dark>
                    <x-docs.hero-frame dark>
                        <x-ceplok::hero dark-mode type="centered" platform="mobile" :heading="$heading" :show-heading="false" :description="$description" button-href="#" />
                    </x-docs.hero-frame>
                </x-docs.stage>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::hero
                    ⟦*dark-mode⟧
                    type="horizontal-image-right"
                    heading="Hero Design system Ceplok"
                    image="/images/hero-sample.svg"
                    …
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="ukuran" title="Ukuran & jarak">
            <x-docs.lead>
                Diambil dari panel <em>Layout</em> di desain. Satu angka sengaja tidak
                diturunkan apa adanya: <span class="text-primary-500">padding kiri-kanan 0</span>. Di desain isinya
                ditahan wadah selebar 1290px yang dipusatkan, dan wadah itu kehilangan
                artinya begitu layarnya lebih sempit dari 1290px — teks akan menempel
                ke tepi. Jadi jarak tepi yang sama (76px pada bingkai 1440px) dipasang
                sebagai padding bertingkat.
            </x-docs.lead>

            <div class="ds-card overflow-hidden">
                <dl class="divide-y divide-border">
                    @foreach ($spacing as [$term, $value])
                        <div class="grid gap-1 px-5 py-3 sm:grid-cols-[13rem_1fr] sm:gap-4">
                            <dt class="text-sm font-black text-gray-900">{{ $term }}</dt>
                            <dd class="text-body-sm leading-6 text-gray-600">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </x-docs.section>

        <x-docs.section id="responsif" title="Perilaku responsif">
            <x-docs.lead>
                Ambang di bawah ini diukur dari
                <strong class="text-gray-900">lebar hero itu sendiri</strong>,
                bukan lebar layar — elemen <span class="text-primary-500">&lt;section&gt;</span> dipasangi
                <span class="text-primary-500">@container</span>. Jadi hero yang ditaruh di kolom sempit pada layar
                desktop tetap tampil bertumpuk seperti di ponsel. Prop
                <span class="text-primary-500">platform="mobile"</span> memaksa susunan bertumpuk itu tanpa menunggu
                ambang lebarnya.
            </x-docs.lead>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($responsive as [$title, $text])
                    <article class="ds-card p-5">
                        <h3 class="text-sm font-black text-gray-900">{{ $title }}</h3>
                        <p class="mt-1.5 text-body-sm leading-6 text-gray-500">{{ $text }}</p>
                    </article>
                @endforeach
            </div>

            <p class="mt-4 max-w-2xl text-body-sm leading-6 text-gray-500">
                Yang tidak berubah di ukuran mana pun: jarak atas-bawah 24px, judul
                <span class="text-primary-500">font-black</span>, sub heading <span class="text-primary-500">primary-600</span>, deskripsi ditahan
                <span class="text-primary-500">max-w-2xl</span>, dan teks selalu ditulis lebih dulu di DOM — letak
                gambar diatur arah barisnya, sehingga <span class="text-primary-500">&lt;h1&gt;</span> tetap jadi hal
                pertama yang dibacakan pembaca layar pada kedua tipe horizontal.
            </p>
        </x-docs.section>

        <livewire:playground.hero />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Rangkuman seluruh prop, termasuk yang tidak diberi bagian sendiri di
                atas. Semuanya opsional — <span class="text-primary-500">&lt;x-ceplok::hero /&gt;</span> tanpa prop tetap
                merender kerangkanya beserta tombol bawaannya. Atribut HTML lain diteruskan ke elemen
                <span class="text-primary-500">&lt;section&gt;</span> terluar.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="52rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
