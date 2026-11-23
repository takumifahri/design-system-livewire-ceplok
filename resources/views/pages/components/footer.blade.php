@php
    $menus = array_map(fn (int $i) => ['label' => "Menu {$i}", 'url' => '#'], range(1, 8));
    $socials = [
        ['label' => 'Instagram', 'url' => 'https://www.instagram.com/ceplok/', 'icon' => 'brand-instagram'],
        ['label' => 'X', 'url' => 'https://x.com/ceplok', 'icon' => 'brand-x'],
        ['label' => 'Facebook', 'url' => '#', 'icon' => 'brand-facebook'],
    ];
    $logo = '/images/footer-logo.svg';
    $copyright = '© 2025 Ceplok Design System';

    $responsive = [
        ['Sempit (< 576px)', 'Semua bagian bertumpuk satu kolom: logo, menu, garis pemisah, hak cipta, lalu ikon sosial. Menu tetap mengalir menyamping dan membungkus ke baris berikutnya sesuai ruang. Padding kiri-kanan 24px.'],
        ['Sedang (576–767px)', 'Hak cipta dan ikon sosial naik jadi satu baris — hak cipta di kiri, ikon di kanan. Logo dan menu masih bertumpuk karena butuh ruang lebih lega sebelum bisa sebaris.'],
        ['Lebar (≥ 768px)', 'Logo di kiri, menu mengisi sisa ruang di tengah dan tersusun berkolom: maksimal 5 per baris, sisanya turun dan sejajar kolom di atasnya. Padding kiri-kanan melebar jadi 48px dan logo sedikit membesar.'],
    ];

    $props = [
        ['logo', 'string | null', 'null', 'URL berkas gambar logo, dirender sebagai <img> setinggi 48px (60px saat footer ≥ 768px) dengan lebar mengikuti rasio aslinya. Bila diisi, logo-content diabaikan.'],
        ['logo-alt', 'string', 'Logo', 'Teks alternatif gambar logo; hanya berlaku bersama prop logo. Isi dengan nama instansinya, bukan kata "logo", agar wajar dibacakan pembaca layar.'],
        ['logo-content', 'slot', 'null', 'Logo berupa elemen — misal logo teks atau <svg> inline. Dipakai hanya bila prop logo kosong, dan warnanya diatur sendiri supaya kontras di atas latar gelap.'],
        ['menus', 'array', '[]', "Tautan navigasi: [['label' => …, 'url' => …], …]. url boleh dikosongkan dan otomatis jadi \"#\". Satu baris memuat maksimal 5 menu; sisanya turun ke baris berikutnya. Bila kosong, blok navigasi tidak dirender sama sekali."],
        ['copyright', 'string | slot', 'null', 'Isi baris bawah sisi kiri, umumnya teks hak cipta. Lewat slot boleh berupa markup, misal disisipi tautan kebijakan privasi. Bila kosong, barisnya tidak dirender.'],
        ['socials', 'array', '[]', "Ikon media sosial: [['label' => …, 'url' => …, 'icon' => …], …]. icon berupa nama ikon (mis. brand-instagram) atau markup <svg> yang mewarisi currentColor sehingga ikut berubah saat hover; label jadi aria-label; url selain \"#\" dibuka di tab baru dengan rel=\"noopener noreferrer\"."],
        ['fluid', 'bool', 'false', 'Bila true, isi footer melebar penuh mengikuti lebar footer — batas 1280px (max-w-7xl) dan pemusatannya dilepas, menyisakan padding kiri-kanan saja. Dipakai untuk footer situs yang menempel di tepi layar.'],
        ['…atribut', 'HTML', '—', 'Atribut untuk elemen <footer> terluar (class, id, …) — untuk mengganti warna latar atau menyetel jarak. Elemen ini sekaligus titik ukur @container, jadi hindari memberinya lebar tetap.'],
    ];
@endphp

<x-docs.layout title="Footer">
    <x-docs.page
        title="Footer"
        description="Penutup halaman berisi logo, navigasi ringkas, hak cipta, dan tautan media sosial. Tata letaknya mengikuti lebar footer itu sendiri, bukan lebar layar."
        :toc="[
            'dasar' => 'Tampilan dasar',
            'logo' => 'logo · logo-alt · logo-content',
            'menus' => 'menus',
            'baris-bawah' => 'copyright · socials',
            'fluid' => 'fluid',
            'responsif' => 'Perilaku responsif',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="dasar" title="Tampilan dasar">
            <x-docs.lead>
                Satu blok utuh berlatar <span class="text-primary-500 dark:text-primary-400">gray-800</span>: baris atas berisi logo dan menu, lalu garis tipis,
                lalu baris bawah berisi hak cipta dan ikon sosial. Semua bagiannya opsional — yang tidak
                diisi tidak dirender, bukan disembunyikan lewat CSS.
            </x-docs.lead>

            <x-docs.preview>
                <x-ceplok::footer :logo="$logo" logo-alt="Ceplok Design System" :menus="array_slice($menus, 0, 4)" :copyright="$copyright" :socials="$socials" />
            </x-docs.preview>

            <x-docs.code>
                @verbatim
                <x-ceplok::footer
                    logo="/images/footer-logo.svg"
                    logo-alt="Ceplok Design System"
                    :menus="[['label' => 'Menu 1', 'url' => '/menu-1'], …]"
                    copyright="© 2025 Ceplok Design System"
                    :socials="$socials"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="logo" title="logo · logo-alt · logo-content">
            <x-docs.lead>
                <span class="text-primary-500 dark:text-primary-400">logo</span> menerima URL berkas gambar dan dirender sebagai <span class="text-primary-500 dark:text-primary-400">&lt;img&gt;</span> setinggi 48px —
                60px saat footer selebar ≥ 768px — dengan lebar mengikuti rasio aslinya. <span class="text-primary-500 dark:text-primary-400">logo-alt</span> jadi
                teks alternatifnya; isi dengan nama instansi, bukan kata "logo", supaya wajar dibacakan
                pembaca layar.
            </x-docs.lead>
            <x-docs.lead>
                Bila logonya bukan berkas gambar — logo teks, <span class="text-primary-500 dark:text-primary-400">&lt;svg&gt;</span> inline, atau gabungan keduanya
                — pakai slot <span class="text-primary-500 dark:text-primary-400">logo-content</span>. Slot itu hanya berlaku saat <span class="text-primary-500 dark:text-primary-400">logo</span> kosong: kalau dua-duanya
                diisi, <span class="text-primary-500 dark:text-primary-400">logo</span> yang menang. Karena latarnya gelap, warnanya harus ditentukan sendiri.
            </x-docs.lead>

            <div class="space-y-6">
                <x-docs.preview label="logo — berkas gambar">
                    <x-ceplok::footer :logo="$logo" logo-alt="Ceplok Design System" :menus="array_slice($menus, 0, 4)" :copyright="$copyright" />
                </x-docs.preview>

                <x-docs.preview label="logo-content — logo teks">
                    <x-ceplok::footer :menus="array_slice($menus, 0, 4)" :copyright="$copyright">
                        <x-slot:logo-content>
                            <span class="text-xl font-black text-white">CEPLOK</span>
                        </x-slot:logo-content>
                    </x-ceplok::footer>
                </x-docs.preview>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::footer
                    ⟦*logo⟧="/images/footer-logo.svg"
                    ⟦*logo-alt⟧="Ceplok Design System"
                />

                {{-- Bukan berkas gambar — hanya dipakai bila logo kosong --}}
                <x-ceplok::footer …>
                    <x-slot:⟦*logo-content⟧>
                        <span class="text-xl font-black text-white">CEPLOK</span>
                    </x-slot:logo-content>
                </x-ceplok::footer>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="menus" title="menus">
            <x-docs.lead>
                Daftar tautan navigasi. <span class="text-primary-500 dark:text-primary-400">url</span> boleh dikosongkan dan otomatis jadi <span class="text-primary-500 dark:text-primary-400">"#"</span>. Satu baris
                memuat paling banyak lima menu; menu keenam dan seterusnya turun ke
                baris berikutnya dan tetap sejajar kolom di atasnya. Bila <span class="text-primary-500 dark:text-primary-400">menus</span> kosong, blok navigasinya
                tidak dirender sama sekali.
            </x-docs.lead>

            <div class="space-y-6">
                <x-docs.preview label="Lima menu — masih satu baris">
                    <x-ceplok::footer :logo="$logo" logo-alt="CEPLOK" :menus="array_slice($menus, 0, 5)" :copyright="$copyright" />
                </x-docs.preview>

                <x-docs.preview label="Delapan menu — tiga sisanya turun">
                    <x-ceplok::footer :logo="$logo" logo-alt="CEPLOK" :menus="$menus" :copyright="$copyright" />
                </x-docs.preview>
            </div>

            <x-docs.code>
                @verbatim
                {{-- Lima menu pertama satu baris, sisanya turun sendiri --}}
                <x-ceplok::footer
                    :⟦*menus⟧="[
                        ['label' => 'Menu 1', 'url' => '/menu-1'],
                        ['label' => 'Menu 2', 'url' => '/menu-2'],
                        ['label' => 'Menu 3', 'url' => '/menu-3'],
                        ['label' => 'Menu 4', 'url' => '/menu-4'],
                        ['label' => 'Menu 5', 'url' => '/menu-5'],
                        ['label' => 'Menu 6', 'url' => '/menu-6'],
                        ['label' => 'Menu 7', 'url' => '/menu-7'],
                        ['label' => 'Menu 8', 'url' => '/menu-8'],
                    ]"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="baris-bawah" title="copyright · socials">
            <x-docs.lead>
                Keduanya mengisi baris bawah — <span class="text-primary-500 dark:text-primary-400">copyright</span> di kiri, <span class="text-primary-500 dark:text-primary-400">socials</span> di ujung kanan.
                <span class="text-primary-500 dark:text-primary-400">copyright</span> juga bisa diisi lewat slot, jadi tidak harus teks: boleh disisipi tautan
                kebijakan privasi atau nomor versi. Bila kosong, barisnya tidak dirender dan ikon sosial
                bergeser ke kiri.
            </x-docs.lead>
            <x-docs.lead>
                Pada <span class="text-primary-500 dark:text-primary-400">socials</span>, <span class="text-primary-500 dark:text-primary-400">icon</span> berupa nama ikon — mis. ikon brand bawaan kit
                <span class="text-primary-500 dark:text-primary-400">brand-instagram</span> — atau markup <span class="text-primary-500 dark:text-primary-400">&lt;svg&gt;</span> sendiri. Warnanya mengikuti
                <span class="text-primary-500 dark:text-primary-400">currentColor</span> sehingga ikut berubah saat hover. <span class="text-primary-500 dark:text-primary-400">label</span> menjadi
                <span class="text-primary-500 dark:text-primary-400">aria-label</span> tautannya, dan url selain <span class="text-primary-500 dark:text-primary-400">"#"</span> dibuka di tab baru dengan
                <span class="text-primary-500 dark:text-primary-400">rel="noopener noreferrer"</span>.
            </x-docs.lead>

            <x-docs.preview>
                <x-ceplok::footer :logo="$logo" logo-alt="CEPLOK" :menus="array_slice($menus, 0, 4)" :socials="$socials">
                    <x-slot:copyright>
                        {{ $copyright }} · <a href="#" class="underline underline-offset-2 hover:text-white">Kebijakan privasi</a>
                    </x-slot:copyright>
                </x-ceplok::footer>
            </x-docs.preview>

            <x-docs.code>
                @verbatim
                <x-ceplok::footer
                    :⟦*socials⟧="[
                        ['label' => 'Instagram', 'url' => 'https://instagram.com/ceplok', 'icon' => 'brand-instagram'],
                        ['label' => 'X',         'url' => 'https://x.com/ceplok',         'icon' => 'brand-x'],
                        ['label' => 'Facebook',  'url' => '#',                            'icon' => 'brand-facebook'],
                    ]"
                >
                    <x-slot:⟦*copyright⟧>
                        © 2025 Ceplok Design System · <a href="/privasi">Kebijakan privasi</a>
                    </x-slot:copyright>
                </x-ceplok::footer>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="fluid" title="fluid">
            <x-docs.lead>
                Bawaannya isi footer dibatasi <span class="text-primary-500 dark:text-primary-400">max-w-7xl</span> (1280px) lalu dipusatkan, jadi di layar lebar
                logo dan menu berhenti sebelum tepi. <span class="text-primary-500 dark:text-primary-400">fluid</span> melepas batas itu: isinya melebar penuh
                mengikuti lebar footer, menyisakan padding kiri-kanan saja. Latar dan tinggi footernya sendiri
                tidak berubah — yang bergeser hanya letak isinya.
            </x-docs.lead>

            <x-docs.preview label="fluid — isi menempel ke tepi footer">
                <x-ceplok::footer fluid :logo="$logo" logo-alt="CEPLOK" :menus="array_slice($menus, 0, 4)" :copyright="$copyright" :socials="$socials" />
            </x-docs.preview>

            <x-docs.code>
                @verbatim
                <x-ceplok::footer ⟦*fluid⟧ … />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="responsif" title="Perilaku responsif">
            <x-docs.lead>
                Ambang di bawah ini diukur dari
                <strong class="text-gray-900 dark:text-white">lebar footer itu sendiri</strong>, bukan lebar layar —
                elemen <span class="text-primary-500 dark:text-primary-400">&lt;footer&gt;</span> dipasangi <span class="text-primary-500 dark:text-primary-400">@container</span>. Jadi footer yang ditaruh di kolom
                sempit pada layar desktop tetap tampil bertumpuk seperti di ponsel, dan sebaliknya. Cara
                mengeceknya: ganti kontrol <em>Tampilan</em> di Playground, atau perkecil jendela.
            </x-docs.lead>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($responsive as [$title, $text])
                    <article class="ds-card p-5">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white">{{ $title }}</h3>
                        <p class="mt-1.5 text-body-sm leading-6 text-gray-500 dark:text-gray-400">{{ $text }}</p>
                    </article>
                @endforeach
            </div>

            <p class="mt-4 max-w-2xl text-body-sm leading-6 text-gray-500 dark:text-gray-400">
                Yang tidak berubah di ukuran mana pun: latar <span class="text-primary-500 dark:text-primary-400">bg-gray-800</span>, jarak atas-bawah 64px, lebar
                isi dibatasi <span class="text-primary-500 dark:text-primary-400">max-w-7xl</span> lalu dipusatkan — kecuali bila <span class="text-primary-500 dark:text-primary-400">fluid</span> diisi — dan garis
                pemisah tipis di atas baris hak cipta.
            </p>
        </x-docs.section>

        <livewire:playground.footer />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Rangkuman seluruh prop, termasuk yang tidak diberi bagian sendiri di atas. Semuanya opsional —
                <span class="text-primary-500 dark:text-primary-400">&lt;x-ceplok::footer /&gt;</span> tanpa prop tetap merender kerangkanya. Atribut HTML lain
                diteruskan ke elemen <span class="text-primary-500 dark:text-primary-400">&lt;footer&gt;</span>.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="48rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
