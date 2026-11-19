<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Portal Dashboard — beranda portal pegawai, padanan PortalDashboard di docs
 * React. Seperti di sana, isinya hanya komponen kit (<x-ceplok::…>) dan
 * token warnanya; tirai transisi dan tombol pulangnya diurus
 * <x-docs.app-frame>. Bagian yang belum punya komponen di kit dirakit dari
 * token: kalender bulanan (Datepicker hanya punya bentuk field + panel),
 * kartu sapaan dan hiasannya, ubin Aplikasi Intranet, serta kartu unduhan
 * KOSMO.
 *
 * Bedanya dengan React, keadaannya hidup di komponen Livewire ini: sumber
 * anggaran (wire:model.live pada Select), pencarian (wire:model dan event
 * search), Sidebar ringkas (event collapse), Sidebar di dalam Drawer pada layar sempit,
 * bulan kalender, daftar Aplikasi Intranet yang terbuka, dan pesan Toast.
 *
 * Mode gelapnya dipasang seperti di aplikasi consumer: `data-theme="dark"` di
 * akar, `dark-mode` di setiap komponen kit yang punya prop itu, dan varian
 * `in-data-[theme=dark]:` untuk bagian yang dirakit sendiri. Setiap Button kit
 * menerima state yang sama agar memakai palet gelap sesuai theme dan variant.
 */
new class extends Component
{
    /** Ditulis kapital, seperti di desain. */
    public const NAMA = 'RAFLI ARANDHANA';

    /**
     * "Hari ini" dikunci ke 13 Oktober 2026, supaya kalender, presensi, dan
     * tanggal data anggaran selalu tampil seperti desainnya.
     */
    public const HARI_INI = '2026-10-13';

    public const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public const HARI = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

    public const MENU = [
        'dashboard' => ['label' => 'Dashboard', 'ikon' => 'ceplok-o-home'],
        'kms' => ['label' => 'KMS', 'ikon' => 'ceplok-o-lightbulb'],
        'pengumuman' => ['label' => 'Pengumuman', 'ikon' => 'ceplok-o-bullhorn'],
        'forum' => ['label' => 'Forum', 'ikon' => 'ceplok-o-messages'],
        'berita' => ['label' => 'Berita', 'ikon' => 'ceplok-o-newspaper'],
        'event' => ['label' => 'Event', 'ikon' => 'ceplok-o-calendar-month'],
    ];

    public const WIKI = [
        ['judul' => 'Integrasi dan Template Standar', 'tanggal' => '09 Okt 2026', 'penulis' => 'Rezy Tijo Waskito', 'ruang' => 'Repository Kode Sumber'],
        ['judul' => 'SonarQube — Analisis Kode dan Kualitas', 'tanggal' => '09 Okt 2026', 'penulis' => 'Rezy Tijo Waskito', 'ruang' => 'Repository Kode Sumber'],
    ];

    public const ANGGARAN = [
        'monsakti' => ['label' => 'Realisasi Kementerian (MONSAKTI)', 'pagu' => 'Rp. 10.214.835.286.000', 'realisasi' => 'Rp. 6.117.956.903.552', 'persentase' => '59.89%'],
        'sakti' => ['label' => 'Realisasi Satuan Kerja (SAKTI)', 'pagu' => 'Rp. 1.482.650.000.000', 'realisasi' => 'Rp. 902.715.448.120', 'persentase' => '60.89%'],
    ];

    /**
     * Ubin Aplikasi Intranet. Logo aplikasinya di desain berupa gambar yang
     * belum ada di repo; sementara ini diganti ikon kit berwarna token, naik ke
     * tangga 400 di mode gelap. Ganti dengan <img> begitu berkas logonya tersedia.
     *
     * Mula-mula hanya APLIKASI_RINGKAS ubin pertama yang tampil, dua baris di xl
     * seperti desainnya. Tombol panah membuka sisanya ($semuaAplikasi); enam
     * ubin terakhir rekaan, untuk memperagakan daftar yang terbuka.
     */
    public const APLIKASI = [
        ['nama' => 'Kinerja Harian', 'ikon' => 'ceplok-o-chart-mixed', 'warna' => 'text-primary-700 in-data-[theme=dark]:text-primary-400'],
        ['nama' => 'e-Mail', 'ikon' => 'ceplok-o-envelope', 'warna' => 'text-orange-600 in-data-[theme=dark]:text-orange-400'],
        ['nama' => 'Satu Data Komdigi', 'ikon' => 'ceplok-o-chart-pie', 'warna' => 'text-primary-700 in-data-[theme=dark]:text-primary-400'],
        ['nama' => 'E-Presensi', 'ikon' => 'ceplok-o-fingerprint', 'warna' => 'text-gray-700 in-data-[theme=dark]:text-gray-300'],
        ['nama' => 'sidara', 'ikon' => 'ceplok-o-paper-plane', 'warna' => 'text-primary-700 in-data-[theme=dark]:text-primary-400'],
        ['nama' => 'LPSE', 'ikon' => 'ceplok-o-store', 'warna' => 'text-gray-700 in-data-[theme=dark]:text-gray-300'],
        ['nama' => 'Simaya+', 'ikon' => 'ceplok-o-inbox-full', 'warna' => 'text-purple-700 in-data-[theme=dark]:text-purple-400'],
        ['nama' => 'KCloud', 'ikon' => 'ceplok-o-cloud-arrow-up', 'warna' => 'text-gray-700 in-data-[theme=dark]:text-gray-300'],
        ['nama' => 'E-Arsip', 'ikon' => 'ceplok-o-archive', 'warna' => 'text-primary-700 in-data-[theme=dark]:text-primary-400'],
        ['nama' => 'Pinjam Ruangan', 'ikon' => 'ceplok-o-calendar-plus', 'warna' => 'text-red-600 in-data-[theme=dark]:text-red-400'],
        ['nama' => 'Simpatik', 'ikon' => 'ceplok-o-users-group', 'warna' => 'text-primary-700 in-data-[theme=dark]:text-primary-400'],
        ['nama' => 'JDIH', 'ikon' => 'ceplok-o-scale-balanced', 'warna' => 'text-green-700 in-data-[theme=dark]:text-green-400'],
        ['nama' => 'Helpdesk TI', 'ikon' => 'ceplok-o-user-headset', 'warna' => 'text-primary-700 in-data-[theme=dark]:text-primary-400'],
        ['nama' => 'Perpustakaan Digital', 'ikon' => 'ceplok-o-book-open-reader', 'warna' => 'text-orange-600 in-data-[theme=dark]:text-orange-400'],
        ['nama' => 'Kendaraan Dinas', 'ikon' => 'ceplok-o-truck', 'warna' => 'text-gray-700 in-data-[theme=dark]:text-gray-300'],
        ['nama' => 'Peta Kantor', 'ikon' => 'ceplok-o-map-pin', 'warna' => 'text-red-600 in-data-[theme=dark]:text-red-400'],
        ['nama' => 'Penghargaan', 'ikon' => 'ceplok-o-award', 'warna' => 'text-yellow-700 in-data-[theme=dark]:text-yellow-400'],
        ['nama' => 'Tiket Layanan', 'ikon' => 'ceplok-o-ticket', 'warna' => 'text-purple-700 in-data-[theme=dark]:text-purple-400'],
    ];

    /** Jumlah ubin sebelum daftarnya dibuka: dua baris di xl. */
    public const APLIKASI_RINGKAS = 12;

    /**
     * Isi Forum Terbaru dan Sticky Post. Nama dan judulnya contoh rekaan,
     * dengan tanggal sebelum "hari ini".
     */
    public const FORUM = [
        ['penulis' => 'Ayu Lestari', 'kategori' => 'Diskusi Bebas', 'waktu' => '12 Okt 2026 13:08 WIB', 'judul' => 'Sorotan Kegiatan Pegawai | September 2026', 'dilihat' => 353, 'komentar' => 0, 'suka' => 10],
        ['penulis' => 'Bima Pratama', 'kategori' => 'Kearsipan', 'waktu' => '09 Okt 2026 08:04 WIB', 'judul' => 'Tata cara penyerahan arsip inaktif ke unit kearsipan', 'dilihat' => 1689, 'komentar' => 1, 'suka' => 2],
        ['penulis' => 'Citra Maharani', 'kategori' => 'Bantuan Teknis', 'waktu' => '07 Okt 2026 13:04 WIB', 'judul' => 'Jadwal pemeliharaan jaringan kantor akhir pekan ini', 'dilihat' => 838, 'komentar' => 2, 'suka' => 3],
        ['penulis' => 'Dimas Saputra', 'kategori' => 'Bantuan Teknis', 'waktu' => '06 Okt 2026 10:55 WIB', 'judul' => 'Pendingin ruangan di Lantai 6 Gedung B kurang dingin', 'dilihat' => 430, 'komentar' => 1, 'suka' => 5],
        ['penulis' => 'Eka Wulandari', 'kategori' => 'Kepegawaian', 'waktu' => '02 Okt 2026 09:59 WIB', 'judul' => 'Tindak lanjut webinar penguatan budaya organisasi', 'dilihat' => 774, 'komentar' => 3, 'suka' => 15],
    ];

    public const STICKY = [
        ['judul' => 'Panduan Akses Wi-Fi Kantor', 'tanggal' => '01 Sep 2026 10:45 WIB', 'penulis' => 'Fajar Nugroho'],
        ['judul' => 'Panduan Akses Jaringan Internal', 'tanggal' => '05 Mar 2026 10:19 WIB', 'penulis' => 'Ayu Lestari'],
        ['judul' => 'Pedoman Desain Flyer Portal', 'tanggal' => '15 Jan 2026 17:45 WIB', 'penulis' => 'Gita Permata'],
        ['judul' => 'Langkah Mengubah Kata Sandi Portal Intranet', 'tanggal' => '02 Jul 2025 22:45 WIB', 'penulis' => 'Gita Permata'],
    ];

    /** Toko aplikasi untuk kartu unduhan KOSMO. */
    public const TOKO = [
        'android' => ['sistem' => 'Android', 'toko' => 'Google Play', 'ajakan' => 'Tersedia di', 'tautan' => 's.komdigi.go.id/kosmo-android', 'ikon' => 'ceplok-o-play'],
        'ios' => ['sistem' => 'iOS', 'toko' => 'App Store', 'ajakan' => 'Unduh di', 'tautan' => 's.komdigi.go.id/kosmo-ios', 'ikon' => 'ceplok-s-apple'],
    ];

    /**
     * Tampilan gelap seluruh dasbor, diisi halamannya dari mode gelap docs.
     * Tombol tema di header hanya mengabarkan pilihan baru lewat event
     * `dark-mode-change`; bingkai docs yang menyimpan dan menerapkannya.
     */
    public bool $darkMode = false;

    public string $kata = '';

    public string $kategori = '';

    public string $sumber = 'monsakti';

    public bool $ringkas = false;

    public bool $menuSeluler = false;

    /** Seluruh ubin Aplikasi Intranet tampil, dibuka tombol panah di bawah kartunya. */
    public bool $semuaAplikasi = false;

    /** Bulan kalender, digeser dari bulan "hari ini". */
    public int $geserBulan = 0;

    /**
     * Pesan demo di Toast kanan atas, yang hilang sendiri setelah 4 detik.
     * Teksnya disimpan terpisah dari $umpanTampil supaya tidak lenyap sebelum
     * Toast selesai memudar, dan $umpanKali membuat pesan yang sama pun
     * memulai ulang hitungannya.
     */
    public string $umpan = '';

    public bool $umpanTampil = false;

    public int $umpanKali = 0;

    public function kabari(string $teks): void
    {
        $this->umpan = $teks;
        $this->umpanTampil = true;
        $this->umpanKali++;
    }

    /** Hanya Dashboard yang dirancang; menu lain memberi tahu lewat Toast alih-alih membuka halaman kosong. */
    public function menuDemo(string $id): void
    {
        $this->menuSeluler = false;
        $this->kabari('Halaman '.(self::MENU[$id]['label'] ?? $id).' belum ada di contoh ini.');
    }

    /*
     * Ubin aplikasi, forum, Sticky Post, dan kartu KOSMO belum punya halaman,
     * jadi klik-nya memberi tahu lewat Toast. Pesannya dirakit di sini dari
     * nomor atau id-nya, seperti menuDemo().
     */
    public function bukaAplikasi(int $nomor): void
    {
        $this->kabari('Aplikasi '.(self::APLIKASI[$nomor]['nama'] ?? 'itu').' belum ada di contoh ini.');
    }

    public function bukaForum(int $nomor): void
    {
        $this->kabari('Detail forum "'.(self::FORUM[$nomor]['judul'] ?? $nomor).'" belum ada di contoh ini.');
    }

    public function bukaSticky(int $nomor): void
    {
        $this->kabari('Detail "'.(self::STICKY[$nomor]['judul'] ?? $nomor).'" belum ada di contoh ini.');
    }

    public function unduhKosmo(string $id): void
    {
        $this->kabari('Tautan KOSMO di '.(self::TOKO[$id]['toko'] ?? $id).' belum ada di contoh ini.');
    }

    /** Pendengar event `search` Search: `detail.value` dan `detail.category`. */
    public function cari(?string $teks = '', ?string $kategori = ''): void
    {
        $kunci = trim((string) $teks);
        $area = $this->kategoriPilihan[(string) $kategori] ?? 'semua menu';

        $this->kabari($kunci === '' ? 'Ketik kata kunci dulu untuk mencari.' : "Pencarian demo: \"{$kunci}\" di {$area}.");
    }

    public function bulanSebelumnya(): void
    {
        $this->geserBulan--;
    }

    public function bulanBerikutnya(): void
    {
        $this->geserBulan++;
    }

    /** Kategori Search: semua menu kecuali Dashboard. */
    #[Computed]
    public function kategoriPilihan(): array
    {
        return array_map(fn (array $menu) => $menu['label'], array_diff_key(self::MENU, ['dashboard' => true]));
    }

    #[Computed]
    public function anggaran(): array
    {
        return self::ANGGARAN[$this->sumber] ?? self::ANGGARAN['monsakti'];
    }

    #[Computed]
    public function sumberAnggaran(): array
    {
        return array_map(fn (array $anggaran) => $anggaran['label'], self::ANGGARAN);
    }

    /** Enam minggu penuh, dari Minggu sebelum tanggal 1 bulan yang ditampilkan. */
    #[Computed]
    public function kalender(): array
    {
        $hariIni = CarbonImmutable::parse(self::HARI_INI);
        $bulan = $hariIni->startOfMonth()->addMonthsNoOverflow($this->geserBulan);
        $awal = $bulan->subDays($bulan->dayOfWeek);

        $minggu = [];

        for ($baris = 0; $baris < 6; $baris++) {
            for ($kolom = 0; $kolom < 7; $kolom++) {
                $tanggal = $awal->addDays($baris * 7 + $kolom);

                $minggu[$baris][] = [
                    'tanggal' => $tanggal->day,
                    'diLuar' => $tanggal->month !== $bulan->month,
                    'hariIni' => $tanggal->isSameDay($hariIni),
                    'akhirPekan' => $kolom === 0 || $kolom === 6,
                ];
            }
        }

        return ['judul' => self::BULAN[$bulan->month - 1].' '.$bulan->year, 'hari' => self::HARI, 'minggu' => $minggu];
    }

    /**
     * Item ListView wiki. ListView mencetak tiap metadata di <span> biasa, jadi
     * ikon dan teksnya perlu pembungkus flex sendiri agar sebaris.
     */
    #[Computed]
    public function wiki(): array
    {
        $meta = fn (string $ikon, string $teks) => new HtmlString(
            '<span class="inline-flex items-center gap-1">'.svg($ikon, 'size-3.5', ['aria-hidden' => 'true'])->toHtml().e($teks).'</span>'
        );

        return array_map(fn (array $wiki) => [
            'variant' => 'simple',
            'platform' => 'desktop',
            'title' => $wiki['judul'],
            'icon' => svg('ceplok-o-file-lines', 'size-4', ['aria-hidden' => 'true']),
            'metadata' => [$meta('ceplok-o-calendar-month', $wiki['tanggal']), $meta('ceplok-o-user', $wiki['penulis'])],
            'badge' => new HtmlString(Blade::render(
                '<x-ceplok::badge variant="brand" size="sm" :icon="$ikon">{{ $ruang }}</x-ceplok::badge>',
                ['ikon' => svg('ceplok-o-code-branch'), 'ruang' => $wiki['ruang']],
            )),
            'actionLabel' => 'Buka wiki',
            'actionDisabled' => true,
            'actionWireClick' => "kabari('Detail wiki belum ada di contoh ini.')",
        ], self::WIKI);
    }

    /** Item ListView Forum Terbaru, varian article. Tiap kartunya tautan yang memberi tahu lewat Toast. */
    #[Computed]
    public function forum(): array
    {
        return array_map(fn (int $nomor) => [
            'variant' => 'article',
            'subject' => self::FORUM[$nomor]['penulis'],
            'category' => self::FORUM[$nomor]['kategori'],
            'timestamp' => self::FORUM[$nomor]['waktu'],
            'title' => self::FORUM[$nomor]['judul'],
            'views' => self::FORUM[$nomor]['dilihat'],
            'comments' => self::FORUM[$nomor]['komentar'],
            'likes' => self::FORUM[$nomor]['suka'],
            'href' => route('portal.app'),
            'attributes' => ['wire:click.prevent' => "bukaForum({$nomor})"],
        ], array_keys(self::FORUM));
    }

    /** Item ListView Sticky Post: tanpa ikon, dan aksinya pindah ke seluruh kartu seperti daftar wiki. */
    #[Computed]
    public function sticky(): array
    {
        return array_map(fn (int $nomor) => [
            'variant' => 'simple',
            'platform' => 'desktop',
            'title' => self::STICKY[$nomor]['judul'],
            'description' => self::STICKY[$nomor]['tanggal'],
            'metadata' => [self::STICKY[$nomor]['penulis']],
            'icon' => false,
            'actionDisabled' => true,
            'actionWireClick' => "bukaSticky({$nomor})",
        ], array_keys(self::STICKY));
    }

    /** Profil di Sidebar; halaman profilnya belum ada, jadi klik-nya memberi tahu lewat Toast. */
    #[Computed]
    public function profil(): array
    {
        return [
            'name' => self::NAMA,
            'profileLabel' => 'Lihat Profile',
            'avatarSrc' => '/images/avatar-sample.svg',
            'attributes' => [
                'wire:click' => "kabari('Halaman profil belum ada di contoh ini.')",
                'x-on:click' => '$wire.menuSeluler = false',
            ],
        ];
    }

    /** Menu Sidebar. Dashboard adalah halaman ini, jadi klik-nya tidak memuat ulang apa pun. */
    #[Computed]
    public function menuSidebar(): array
    {
        $menu = [];

        foreach (self::MENU as $id => ['label' => $label, 'ikon' => $ikon]) {
            $menu[] = [
                'id' => "portal-{$id}",
                'label' => $label,
                'href' => route('portal.app'),
                'icon' => svg($ikon, 'size-5'),
                'active' => $id === 'dashboard',
                'attributes' => $id === 'dashboard'
                    ? ['x-on:click.prevent' => '$wire.menuSeluler = false']
                    : ['wire:click.prevent' => "menuDemo('{$id}')"],
            ];
        }

        $menu[] = [
            'id' => 'portal-keluar',
            'label' => 'Keluar',
            'href' => route('portal.index'),
            // Sidebar belum punya tone danger untuk item menu; ikonnya saja yang merah.
            'icon' => svg('ceplok-o-arrow-right-to-bracket', 'size-5 text-red-600'),
            'attributes' => ['wire:navigate' => true],
        ];

        return $menu;
    }
};
?>

<div @if ($darkMode) data-theme="dark" @endif class="flex min-h-screen bg-surface">
    {{-- Di bawah lg Sidebar tetap dipakai, tetapi ditempatkan di dalam Drawer off-canvas. --}}
    <div class="hidden lg:block">
        <x-ceplok::sidebar
            :items="$this->menuSidebar"
            :user="$this->profil"
            sticky
            :collapsed="$ringkas"
            :dark-mode="$darkMode"
            wire:collapse="$set('ringkas', $event.detail.collapsed)"
            x-on:collapse="document.cookie = 'ceplok_portal_sidebar_collapsed=' + ($event.detail.collapsed ? 1 : 0) + '; path=/; max-age=31536000; samesite=lax'"
        >
            <x-slot:logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="h-8 w-auto">
            </x-slot:logo>
            <x-slot:collapsed-logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="size-8">
            </x-slot:collapsed-logo>
            <x-slot:footer>
                <x-portal.theme-toggle id="portal-theme-desktop" :dark-mode="$darkMode" :collapsed="$ringkas" />
            </x-slot:footer>
        </x-ceplok::sidebar>
    </div>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex h-18 items-center gap-3 border-b border-border bg-surface px-4 sm:px-6">
            {{-- Dibuka di browser lewat $wire, tanpa menunggu request: Drawer diikat wire:model ke properti yang sama. --}}
            <x-ceplok::button
                icon-only
                variant="outline"
                theme="gray"
                :dark-mode="$darkMode"
                size="s"
                class="lg:hidden"
                aria-label="Buka menu"
                aria-controls="portal-menu"
                :aria-expanded="$menuSeluler ? 'true' : 'false'"
                x-bind:aria-expanded="$wire.menuSeluler ? 'true' : 'false'"
                x-on:click="$wire.menuSeluler = true"
            >
                <x-ceplok-o-bars />
            </x-ceplok::button>
            <img src="/images/ceplok.svg" alt="CEPLOK" class="size-8 lg:hidden">

            {{-- Search berkategori di kiri, selebar slot search Navbar back-office:
                 348px, menyusut sampai 180px bila ruangnya kurang. Varian kategori
                 membawa role="search" sendiri, jadi pembungkus ini tidak.

                 Seperti slot search Navbar, garis field-nya gray-600 di mode gelap.
                 Tanpa itu ruas kategorinya (gray-700) lebur dengan header gray-700.
                 Yang ditimpa variabel palet gelapnya, karena Search ber-`dark-mode`
                 memasang `data-theme` sendiri dan akan menimpa token biasa. --}}
            <div class="hidden w-87 max-w-87 min-w-45 flex-[0_1_348px] [--color-field-border-dark:var(--color-gray-600)] md:block">
                <x-ceplok::search
                    :categories="$this->kategoriPilihan"
                    category-placeholder="Pilih"
                    :category-attributes="['wire:model' => 'kategori']"
                    placeholder="Pencarian"
                    aria-label="Pencarian"
                    button-label="Cari"
                    trigger="enter"
                    :dark-mode="$darkMode"
                    wire:model="kata"
                    wire:search="cari($event.detail.value, $event.detail.category)"
                />
            </div>

            {{-- Lonceng bergaya notificationTrigger Navbar front-office: ikon 20px
                 gray-500 dalam kotak 36px, tanpa angka. --}}
            <button
                type="button"
                aria-label="Notifikasi"
                wire:click="kabari('Ada 5 notifikasi baru.')"
                class="ml-auto grid size-9 shrink-0 place-items-center rounded-lg text-content-muted transition-colors hover:bg-surface-subtle focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand"
            >
                <x-ceplok-o-bell class="size-5" aria-hidden="true" />
            </button>
        </header>

        {{--
            Toast di kanan atas, tepat di bawah header. Ia hilang sendiri 4 detik
            setelah pesan terakhir: $umpanKali yang berganti memulai ulang
            hitungannya, dan menurunkan $wire.umpanTampil menutupnya lewat
            wire:model, dengan transisi keluar milik Toast.
        --}}
        <div
            x-data="{ jeda: null }"
            x-effect="$wire.umpanKali; clearTimeout(jeda); jeda = $wire.umpanTampil ? setTimeout(() => $wire.umpanTampil = false, 4000) : null"
            class="fixed top-22 right-4 z-30 w-80 max-w-[calc(100vw-2rem)] sm:right-6"
        >
            <x-ceplok::toast wire:model="umpanTampil" :dark-mode="$darkMode">{{ $umpan }}</x-ceplok::toast>
        </div>

        {{-- Latar halaman gray-800 di mode gelap, setingkat di bawah kartu gray-700. --}}
        <main class="flex-1 bg-blue-50 in-data-[theme=dark]:bg-gray-800">
            {{--
                Padding isi mengikuti rasio desain: kolom konten 1350px berisi isi
                1080px dengan padding 135px kiri-kanan, yaitu 10% : 80% : 10%. Dalam
                persen, rasio itu sama di layar selebar apa pun; lebar kartu ikut,
                karena grid-nya juga proporsional. Ditulis di sini, bukan lewat
                Container, karena lebar dan padding bawaan Container (1126px,
                padding sampai 56px) tidak mengikuti rasio ini. Di bawah xl
                padding-nya tetap kecil supaya kartu masih lega.
            --}}
            <div class="w-full px-5 sm:px-6 xl:px-[10%]">
                <div class="space-y-5 py-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <h1 class="text-heading-3 font-bold text-content">Dashboard</h1>
                        <x-ceplok::button theme="primary" size="s" :dark-mode="$darkMode" wire:click="kabari('Pengaturan layout belum ada di contoh ini.')">
                            Pengaturan Layout
                        </x-ceplok::button>
                    </div>

                    {{-- Satu kolom di layar sempit, dua kolom di md, lalu grid 12 kolom di xl
                         dengan pembagian seperti desainnya: 8/4, 5/7, 5/7, 12, lalu 7/5.
                         Jarak antarkartu 14px; di desain, baris ketiga dan sesudahnya
                         berjarak 20px, jadi kartunya diberi tambahan 6px di xl. --}}
                    <div class="grid gap-3.5 md:grid-cols-2 xl:grid-cols-12">
                        <section
                            aria-labelledby="portal-sapaan"
                            class="relative isolate flex min-h-56 flex-col overflow-hidden rounded-lg bg-primary-700 p-6 text-white shadow-soft md:col-span-2 xl:col-span-8"
                        >
                            <p class="text-body">Selamat Datang,</p>
                            <h2 id="portal-sapaan" class="mt-1 text-heading-4 font-black">{{ $this::NAMA }}!</h2>
                            <x-portal.hiasan />
                            <p class="mt-auto max-w-xl self-start rounded bg-gray-900/30 px-2 py-1 text-body-sm">
                                Ketika kamu merasa lelah, ingatlah mengapa kamu memulai. Have a Good Day!
                            </p>
                        </section>

                        <x-portal.kalender class="xl:col-span-4" :kalender="$this->kalender" :dark-mode="$darkMode" />

                        <x-ceplok::card class="xl:col-span-5" :dark-mode="$darkMode">
                            <x-portal.judul-kartu judul="Space & Page Wiki Terbaru">
                                {{-- Ikon judul kartu naik ke tangga 400 di mode gelap: tangga
                                     600–700 terlalu redup di atas kartu gray-700. --}}
                                <x-slot:ikon>
                                    <x-ceplok::icon class="mt-0.5 text-orange-600 in-data-[theme=dark]:text-orange-400"><x-ceplok-o-book-open /></x-ceplok::icon>
                                </x-slot:ikon>
                                <a
                                    href="{{ route('portal.app') }}"
                                    wire:click.prevent="kabari('Daftar wiki belum ada di contoh ini.')"
                                    class="text-body-sm font-medium text-brand underline underline-offset-2 hover:text-brand-hover"
                                >Lihat semua</a>
                            </x-portal.judul-kartu>
                            <x-ceplok::list-view size="sm" decoration gap="md" :items="$this->wiki" :dark-mode="$darkMode" />
                        </x-ceplok::card>

                        <x-ceplok::card class="relative xl:col-span-7" :dark-mode="$darkMode">
                            <x-portal.judul-kartu judul="Anggaran">
                                <x-slot:ikon>
                                    <x-ceplok::icon class="mt-0.5 text-green-700 in-data-[theme=dark]:text-green-400"><x-ceplok-o-cash /></x-ceplok::icon>
                                </x-slot:ikon>
                                <x-ceplok::select
                                    aria-label="Sumber data anggaran"
                                    :options="$this->sumberAnggaran"
                                    class="w-full sm:w-72"
                                    :dark-mode="$darkMode"
                                    wire:model.live="sumber"
                                />
                            </x-portal.judul-kartu>
                            <dl class="grid grid-cols-[auto_1fr] gap-x-4 gap-y-2 text-body text-content">
                                <dt>Pagu</dt>
                                <dd class="text-right">{{ $this->anggaran['pagu'] }}</dd>
                                <dt>Realisasi</dt>
                                <dd class="text-right">{{ $this->anggaran['realisasi'] }}</dd>
                                <dt>Persentase</dt>
                                <dd class="text-right">{{ $this->anggaran['persentase'] }}</dd>
                            </dl>
                            <p class="text-body-sm text-content-subtle">Data Anggaran Per Tanggal 13 Oct 2026</p>
                            {{-- Pola titik hiasan di pojok kanan bawah, seperti desainnya. Hanya di
                                 xl: di sana kartu ini memanjang setinggi kartu wiki dan punya ruang
                                 kosong; di layout satu kolom titiknya menimpa angka dan catatan.
                                 Titik gray-200 terlalu terang di mode gelap, jadi diganti gray-600. --}}
                            <div
                                aria-hidden="true"
                                class="pointer-events-none absolute right-5 bottom-5 hidden h-12 w-44 bg-[radial-gradient(var(--color-gray-200)_2px,transparent_2px)] bg-size-[16px_16px] xl:block in-data-[theme=dark]:bg-[radial-gradient(var(--color-gray-600)_2px,transparent_2px)]"
                            ></div>
                        </x-ceplok::card>

                        <x-ceplok::card class="xl:col-span-5 xl:mt-1.5" :dark-mode="$darkMode">
                            {{-- Di mode gelap token `border` sewarna kartu (gray-700), jadi
                                 garisnya memakai gray-600 supaya tetap terlihat. --}}
                            <div class="flex items-center justify-between gap-3 border-b border-border pb-3 in-data-[theme=dark]:border-gray-600">
                                <h2 class="text-body font-bold text-content">Presensi Harian</h2>
                                <x-ceplok::badge variant="brand" size="sm" :dark-mode="$darkMode">Kantor (WFO)</x-ceplok::badge>
                            </div>
                            <p class="flex items-center gap-2 text-body-sm text-content-subtle">
                                <x-ceplok-o-calendar-month class="size-4" aria-hidden="true" />
                                Selasa, 13 Oktober 2026
                            </p>
                            <p class="flex items-center gap-2">
                                <span class="text-heading-2 font-bold text-content">14:03:52</span>
                                <x-ceplok::badge variant="gray" size="sm" :dark-mode="$darkMode">WIB</x-ceplok::badge>
                            </p>
                            <x-ceplok::alert variant="info" :dismissible="false" :dark-mode="$darkMode">
                                <x-slot:icon><x-ceplok-o-question-circle /></x-slot:icon>
                                Rekomendasi waktu Check-In Anda adalah pukul 05.00 - Batas waktu Check-Out Anda adalah
                                pukul 22.00
                            </x-ceplok::alert>
                            {{-- Jam presensi seperti desainnya: check-in hijau, check-out biru,
                                 naik ke tangga 400 di mode gelap. Kolom tombol `auto` mengikuti
                                 tombol disabled yang lebih lebar, lalu Check Out mengisi lebar itu. --}}
                            <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center">
                                <div class="flex items-center gap-3">
                                    <span class="grid size-10 place-items-center rounded-lg bg-green-100 text-green-700 in-data-[theme=dark]:bg-green-900 in-data-[theme=dark]:text-green-400">
                                        <x-ceplok-o-arrow-left-to-bracket class="size-5" aria-hidden="true" />
                                    </span>
                                    <div>
                                        <p class="text-caption text-content-muted">Jam Check-In Anda</p>
                                        <p class="text-body-sm font-bold text-green-700 in-data-[theme=dark]:text-green-400">07:12:45 WIB</p>
                                    </div>
                                </div>
                                {{-- Tombol disabled tetap memakai palet light; opacity bawaan Button
                                     mempertahankan status nonaktif tanpa melebur dengan kartu dark. --}}
                                <x-ceplok::button theme="gray" size="s" disabled class="justify-self-start sm:justify-self-stretch">SUDAH CHECK IN</x-ceplok::button>
                                <div class="flex items-center gap-3">
                                    <span class="grid size-10 place-items-center rounded-lg bg-primary-100 text-primary-700 in-data-[theme=dark]:bg-primary-900 in-data-[theme=dark]:text-primary-400">
                                        <x-ceplok-o-arrow-right-to-bracket class="size-5" aria-hidden="true" />
                                    </span>
                                    <div>
                                        <p class="text-caption text-content-muted">Jam Check-Out Anda</p>
                                        <p class="text-body-sm font-bold text-primary-700 in-data-[theme=dark]:text-primary-400">15:30:00 WIB</p>
                                    </div>
                                </div>
                                <x-ceplok::button theme="primary" size="s" :dark-mode="$darkMode" class="justify-self-start sm:justify-self-stretch" wire:click="kabari('Check-out belum ada di contoh ini.')">CHECK OUT</x-ceplok::button>
                            </div>
                            <div class="flex items-center gap-3 border-t border-border pt-3 in-data-[theme=dark]:border-gray-600">
                                <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-gray-800 text-white in-data-[theme=dark]:bg-gray-900">
                                    <x-ceplok-o-fingerprint class="size-6" aria-hidden="true" />
                                </span>
                                <div>
                                    <p class="text-body-sm font-bold text-content">Detail Kehadiran</p>
                                    <p class="text-caption text-content-muted">Untuk detail data kehadiran, silakan cek di aplikasi Apik.</p>
                                </div>
                            </div>
                        </x-ceplok::card>

                        <x-ceplok::card class="md:col-span-2 xl:col-span-7 xl:mt-1.5" :dark-mode="$darkMode">
                            <x-portal.judul-kartu judul="Rencana dan Realisasi Kegiatan Harian" keterangan="Selasa, 13 Oktober 2026">
                                <x-slot:ikon>
                                    <x-ceplok::icon class="mt-0.5 text-orange-600 in-data-[theme=dark]:text-orange-400"><x-ceplok-o-clipboard-list /></x-ceplok::icon>
                                </x-slot:ikon>
                            </x-portal.judul-kartu>
                            <x-ceplok::alert variant="danger" :dismissible="false" :dark-mode="$darkMode">
                                <x-slot:icon><x-ceplok-o-exclamation-circle /></x-slot:icon>
                                Pengisian Rencana dan Realisasi Kegiatan tidak diaktifkan untuk hari ini.
                            </x-ceplok::alert>
                        </x-ceplok::card>

                        {{-- Tombol panah di tepi bawah kartu menonjol separuh ke luar, jadi
                             ia dipasang di pembungkus: Card memotong isinya dengan
                             overflow-hidden. Jarak bawah 20px di pembungkus memberi ruang
                             di bawah tombol itu, supaya tidak menempel ke kartu sesudahnya. --}}
                        <div class="relative mb-5 md:col-span-2 xl:col-span-12 xl:mt-1.5">
                            <x-ceplok::card class="h-full" :dark-mode="$darkMode">
                                <x-portal.judul-kartu judul="Aplikasi Intranet">
                                    <x-slot:ikon>
                                        <x-ceplok::icon class="mt-0.5 text-purple-700 in-data-[theme=dark]:text-purple-400"><x-ceplok-o-grid /></x-ceplok::icon>
                                    </x-slot:ikon>
                                    {{-- Oranye seperti desainnya, bukan brand seperti tautan kartu lain. --}}
                                    <a
                                        href="{{ route('portal.app') }}"
                                        wire:click.prevent="kabari('Pengaturan urutan aplikasi belum ada di contoh ini.')"
                                        class="text-body-sm font-medium text-orange-600 underline underline-offset-2 hover:text-orange-700 in-data-[theme=dark]:text-orange-400 in-data-[theme=dark]:hover:text-orange-300"
                                    >Atur Urutan</a>
                                </x-portal.judul-kartu>
                                {{-- Hover dan garis gelapnya mengikuti kartu ListView yang bisa diklik. --}}
                                <ul id="portal-aplikasi" class="grid grid-cols-3 gap-3 pb-2 sm:grid-cols-4 lg:grid-cols-6">
                                    @foreach (array_slice($this::APLIKASI, 0, $semuaAplikasi ? null : $this::APLIKASI_RINGKAS) as $nomor => $aplikasi)
                                        <li>
                                            <button
                                                type="button"
                                                wire:click="bukaAplikasi({{ $nomor }})"
                                                class="flex size-full min-h-24 flex-col items-center justify-center gap-2 rounded-lg border border-border bg-surface px-2 py-3 text-center text-caption text-content transition-all hover:border-primary-400/80 hover:shadow-soft focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand in-data-[theme=dark]:border-gray-600"
                                            >
                                                {{ svg($aplikasi['ikon'], 'size-8 shrink-0 '.$aplikasi['warna'], ['aria-hidden' => 'true']) }}
                                                {{ $aplikasi['nama'] }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </x-ceplok::card>
                            {{-- Membuka dan menutup sisa ubin. Panahnya berbalik saat terbuka. --}}
                            <x-ceplok::button
                                icon-only
                                size="xs"
                                theme="primary"
                                :dark-mode="$darkMode"
                                aria-label="Tampilkan semua aplikasi"
                                aria-controls="portal-aplikasi"
                                :aria-expanded="$semuaAplikasi ? 'true' : 'false'"
                                class="absolute bottom-0 left-1/2 z-10 -translate-x-1/2 translate-y-1/2 shadow-soft"
                                wire:click="$toggle('semuaAplikasi')"
                            >
                                <x-ceplok-o-chevron-down :class="'size-4 transition-transform'.($semuaAplikasi ? ' rotate-180' : '')" />
                            </x-ceplok::button>
                        </div>

                        <x-ceplok::card class="md:col-span-2 xl:col-span-7" :dark-mode="$darkMode">
                            <x-portal.judul-kartu judul="Forum Terbaru">
                                <x-slot:ikon>
                                    <x-ceplok::icon class="mt-0.5 text-orange-600 in-data-[theme=dark]:text-orange-400"><x-ceplok-o-messages /></x-ceplok::icon>
                                </x-slot:ikon>
                                <x-ceplok::button
                                    variant="outline"
                                    theme="primary"
                                    size="s"
                                    :dark-mode="$darkMode"
                                    :left-icon="svg('ceplok-o-circle-plus', 'size-[18px]')"
                                    class="[&>span:first-child]:size-[18px]"
                                    wire:click="kabari('Halaman buat forum belum ada di contoh ini.')"
                                >
                                    Buat Forum
                                </x-ceplok::button>
                            </x-portal.judul-kartu>
                            {{-- Ukuran lg (paling lebar 728px) supaya kartunya memenuhi kolom. --}}
                            <x-ceplok::list-view size="lg" gap="md" :items="$this->forum" :dark-mode="$darkMode" />
                        </x-ceplok::card>

                        {{-- Sticky Post lalu kartu unduhan KOSMO: bertumpuk di samping forum
                             di xl, bersebelahan di md. `content-start` menjaga barisnya
                             tidak ikut memanjang setinggi forum. --}}
                        <div class="grid content-start gap-3.5 md:col-span-2 md:grid-cols-2 xl:col-span-5 xl:grid-cols-1">
                            <x-ceplok::card :dark-mode="$darkMode">
                                <x-portal.judul-kartu judul="Sticky Post">
                                    <x-slot:ikon>
                                        <x-ceplok::icon class="mt-0.5 text-red-600 in-data-[theme=dark]:text-red-400"><x-ceplok-o-thumbtack /></x-ceplok::icon>
                                    </x-slot:ikon>
                                </x-portal.judul-kartu>
                                <x-ceplok::list-view size="sm" decoration gap="md" :items="$this->sticky" :dark-mode="$darkMode" />
                            </x-ceplok::card>

                            <section aria-label="Unduh aplikasi KOSMO" class="grid grid-cols-2 gap-3.5">
                                @foreach ($this::TOKO as $id => $toko)
                                    <x-portal.kartu-kosmo :id="$id" :toko="$toko" />
                                @endforeach
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <x-ceplok::drawer
        id="portal-menu"
        wire:model="menuSeluler"
        position="left"
        size="s"
        aria-label="Menu Portal Dashboard"
        style="width: 280px"
        :dark-mode="$darkMode"
    >
        <x-ceplok::sidebar
            :items="$this->menuSidebar"
            :user="$this->profil"
            :collapsed="false"
            :dark-mode="$darkMode"
            wire:collapse="$set('menuSeluler', false)"
            class="h-full min-h-full border-r-0"
        >
            <x-slot:logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="h-8 w-auto">
            </x-slot:logo>
            <x-slot:collapsed-logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="size-8">
            </x-slot:collapsed-logo>
            <x-slot:footer>
                <x-portal.theme-toggle id="portal-theme-mobile" :dark-mode="$darkMode" :collapsed="false" />
            </x-slot:footer>
        </x-ceplok::sidebar>
    </x-ceplok::drawer>
</div>
