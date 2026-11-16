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
 * kartu sapaan, dan hiasannya.
 *
 * Bedanya dengan React, keadaannya hidup di komponen Livewire ini: sumber
 * anggaran (wire:model.live pada Select), pencarian (wire:model dan event
 * search), Sidebar ringkas (event collapse), Sidebar di dalam Drawer pada layar sempit,
 * bulan kalender, dan pesan Toast.
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

    public string $kata = '';

    public string $kategori = '';

    public string $sumber = 'monsakti';

    public bool $ringkas = false;

    public bool $menuSeluler = false;

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

<div class="flex min-h-screen bg-surface">
    {{-- Di bawah lg Sidebar tetap dipakai, tetapi ditempatkan di dalam Drawer off-canvas. --}}
    <div class="hidden lg:block">
        <x-ceplok::sidebar
            :items="$this->menuSidebar"
            :user="$this->profil"
            sticky
            :collapsed="$ringkas"
            wire:collapse="$set('ringkas', $event.detail.collapsed)"
        >
            <x-slot:logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="h-8 w-auto">
            </x-slot:logo>
            <x-slot:collapsed-logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="size-8">
            </x-slot:collapsed-logo>
        </x-ceplok::sidebar>
    </div>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex h-18 items-center gap-3 border-b border-border bg-surface px-4 sm:px-6">
            {{-- Dibuka di browser lewat $wire, tanpa menunggu request: Drawer diikat wire:model ke properti yang sama. --}}
            <x-ceplok::button
                icon-only
                variant="outline"
                theme="gray"
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
                 membawa role="search" sendiri, jadi pembungkus ini tidak. --}}
            <div class="hidden w-87 max-w-87 min-w-45 flex-[0_1_348px] md:block">
                <x-ceplok::search
                    :categories="$this->kategoriPilihan"
                    category-placeholder="Pilih"
                    :category-attributes="['wire:model' => 'kategori']"
                    placeholder="Pencarian"
                    aria-label="Pencarian"
                    button-label="Cari"
                    trigger="enter"
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
            <x-ceplok::toast wire:model="umpanTampil">{{ $umpan }}</x-ceplok::toast>
        </div>

        <main class="flex-1 bg-blue-50">
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
                        <x-ceplok::button variant="outline" size="s" wire:click="kabari('Pengaturan layout belum ada di contoh ini.')">
                            Pengaturan Layout
                        </x-ceplok::button>
                    </div>

                    {{-- Satu kolom di layar sempit, dua kolom di md, lalu grid 12 kolom di xl
                         dengan pembagian seperti desainnya: 8/4, 5/7, 5/7. Jarak antarkartu
                         14px; di desain, baris ketiga berjarak 20px, jadi kartunya diberi
                         tambahan 6px di xl. --}}
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

                        <x-portal.kalender class="xl:col-span-4" :kalender="$this->kalender" />

                        <x-ceplok::card class="xl:col-span-5">
                            <x-portal.judul-kartu judul="Space & Page Wiki Terbaru">
                                <x-slot:ikon>
                                    <x-ceplok::icon class="mt-0.5 text-orange-600"><x-ceplok-o-book-open /></x-ceplok::icon>
                                </x-slot:ikon>
                                <a
                                    href="{{ route('portal.app') }}"
                                    wire:click.prevent="kabari('Daftar wiki belum ada di contoh ini.')"
                                    class="text-body-sm font-medium text-brand underline underline-offset-2 hover:text-brand-hover"
                                >Lihat semua</a>
                            </x-portal.judul-kartu>
                            <x-ceplok::list-view size="sm" decoration gap="md" :items="$this->wiki" />
                        </x-ceplok::card>

                        <x-ceplok::card class="relative xl:col-span-7">
                            <x-portal.judul-kartu judul="Anggaran">
                                <x-slot:ikon>
                                    <x-ceplok::icon class="mt-0.5 text-green-700"><x-ceplok-o-cash /></x-ceplok::icon>
                                </x-slot:ikon>
                                <x-ceplok::select
                                    aria-label="Sumber data anggaran"
                                    :options="$this->sumberAnggaran"
                                    class="w-full sm:w-72"
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
                                 kosong; di layout satu kolom titiknya menimpa angka dan catatan. --}}
                            <div
                                aria-hidden="true"
                                class="pointer-events-none absolute right-5 bottom-5 hidden h-12 w-44 bg-[radial-gradient(var(--color-gray-200)_2px,transparent_2px)] bg-size-[16px_16px] xl:block"
                            ></div>
                        </x-ceplok::card>

                        <x-ceplok::card class="xl:col-span-5 xl:mt-1.5">
                            <div class="flex items-center justify-between gap-3 border-b border-border pb-3">
                                <h2 class="text-body font-bold text-content">Presensi Harian</h2>
                                <x-ceplok::badge variant="brand" size="sm">Kantor (WFO)</x-ceplok::badge>
                            </div>
                            <p class="flex items-center gap-2 text-body-sm text-content-subtle">
                                <x-ceplok-o-calendar-month class="size-4" aria-hidden="true" />
                                Selasa, 13 Oktober 2026
                            </p>
                            <p class="flex items-center gap-2">
                                <span class="text-heading-2 font-bold text-content">14:03:52</span>
                                <x-ceplok::badge variant="gray" size="sm">WIB</x-ceplok::badge>
                            </p>
                            <x-ceplok::alert variant="info" :dismissible="false">
                                <x-slot:icon><x-ceplok-o-question-circle /></x-slot:icon>
                                Rekomendasi waktu Check-In Anda adalah pukul 05.00 - Batas waktu Check-Out Anda adalah
                                pukul 22.00
                            </x-ceplok::alert>
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="grid size-10 place-items-center rounded-lg bg-green-100 text-green-700">
                                        <x-ceplok-o-clock class="size-5" aria-hidden="true" />
                                    </span>
                                    <div>
                                        <p class="text-caption text-content-muted">Jam Check-In Anda</p>
                                        <p class="text-body-sm font-bold text-content">07:12:45 WIB</p>
                                    </div>
                                </div>
                                <x-ceplok::button theme="gray" size="s" disabled>SUDAH CHECK IN</x-ceplok::button>
                            </div>
                        </x-ceplok::card>

                        <x-ceplok::card class="md:col-span-2 xl:col-span-7 xl:mt-1.5">
                            <x-portal.judul-kartu judul="Rencana dan Realisasi Kegiatan Harian" keterangan="Selasa, 13 Oktober 2026">
                                <x-slot:ikon>
                                    <x-ceplok::icon class="mt-0.5 text-orange-600"><x-ceplok-o-clipboard-list /></x-ceplok::icon>
                                </x-slot:ikon>
                            </x-portal.judul-kartu>
                            <x-ceplok::alert variant="danger" :dismissible="false">
                                <x-slot:icon><x-ceplok-o-exclamation-circle /></x-slot:icon>
                                Pengisian Rencana dan Realisasi Kegiatan tidak diaktifkan untuk hari ini.
                            </x-ceplok::alert>
                        </x-ceplok::card>
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
    >
        <x-ceplok::sidebar
            :items="$this->menuSidebar"
            :user="$this->profil"
            :collapsed="false"
            wire:collapse="$set('menuSeluler', false)"
            class="h-full min-h-full border-r-0"
        >
            <x-slot:logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="h-8 w-auto">
            </x-slot:logo>
            <x-slot:collapsed-logo>
                <img src="/images/ceplok.svg" alt="CEPLOK" class="size-8">
            </x-slot:collapsed-logo>
        </x-ceplok::sidebar>
    </x-ceplok::drawer>
</div>
