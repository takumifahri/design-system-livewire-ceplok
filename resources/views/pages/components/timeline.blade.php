@php
    use App\Docs\Theme;

    $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';
    $sed = 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';

    // Status seperti di desain: Badge success dengan ikon jam dan tombol tutup.
    $designStatus = ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true];

    // Isi contoh dari desain: item pertama ringkas, sisanya lengkap.
    $designItems = [
        ['name' => 'User 01', 'role' => 'Role / Department', 'time' => '2026-01-01 09:00', 'description' => $lorem],
        ...array_map(fn (int $n) => [
            'name' => "User 0{$n}",
            'role' => 'Role / Department',
            'time' => '2026-01-01 09:'.str_pad((string) (($n - 1) * 5), 2, '0', STR_PAD_LEFT),
            'elapsed' => '5 minutes',
            'description' => $lorem,
            'details' => $sed,
            'attachments' => [['name' => "sample-document-0{$n}.pdf"], ['name' => 'sample-document-0'.($n - 1).'.pdf']],
            'status' => $designStatus,
        ], [2, 3, 4]),
    ];

    // Lebar paling kecil Timeline desktop. Nama dan jabatan di sebelah Badge status baru muat
    // sebaris mulai sekitar 625px, jadi 40rem menyisakan sedikit ruang. Di layar yang lebih
    // sempit kotak contohnya digeser ke samping, alih-alih kartunya diperas.
    $desktopMinWidth = '40rem';

    // Tiga variant Badge untuk tiga tahap proses yang berbeda.
    $statusItems = [
        ['name' => 'User 01', 'role' => 'Role / Department', 'time' => '2026-01-01 09:00', 'status' => ['label' => 'Menunggu', 'variant' => 'warning', 'icon' => 'ceplok-s-exclamation-circle']],
        ['name' => 'User 02', 'role' => 'Role / Department', 'time' => '2026-01-01 09:05', 'status' => ['label' => 'Disetujui', 'icon' => 'ceplok-s-check-circle']],
        ['name' => 'User 03', 'role' => 'Role / Department', 'time' => '2026-01-01 09:10', 'status' => $designStatus],
    ];

    // PDF dummy untuk lampiran yang bisa dibuka atau diunduh di halaman ini.
    $samplePdf = asset('files/sample-document.pdf');
    $lampiranItems = [[
        'name' => 'User 02',
        'role' => 'Role / Department',
        'time' => '2026-01-01 09:05',
        'description' => $lorem,
        'attachments' => [
            ['name' => 'surat-permohonan.pdf'],
            ['name' => 'berita-acara.pdf', 'href' => $samplePdf, 'target' => '_blank'],
            ['name' => 'lampiran-ktp.pdf', 'href' => $samplePdf, 'download' => 'lampiran-ktp.pdf'],
        ],
    ]];

    // Tiga waktu untuk bagian Waktu: tanggal dan jam selalu dua digit, dan jamnya 24 jam.
    $waktuItems = [
        ['name' => 'User 01', 'role' => 'Role / Department', 'time' => '2026-01-01 09:05'],
        ['name' => 'User 02', 'role' => 'Role / Department', 'time' => '2026-08-07 07:30'],
        ['name' => 'User 03', 'role' => 'Role / Department', 'time' => '2026-12-25 15:45'],
    ];

    $contohIndonesia = [
        [
            'name' => 'Rina Wulandari',
            'role' => 'Pemohon',
            'initials' => 'RW',
            'time' => '2026-10-12 08:30',
            'description' => 'Mengajukan permohonan izin usaha mikro.',
        ],
        [
            'name' => 'Budi Santoso',
            'role' => 'Verifikator / Dinas PMPTSP',
            'avatar' => asset('images/avatar-sample.svg'),
            'time' => '2026-10-12 10:05',
            'elapsed' => '1 jam 35 menit',
            'description' => 'Memeriksa kelengkapan berkas permohonan.',
            'details' => 'Seluruh dokumen persyaratan lengkap dan sesuai.',
            'attachments' => [['name' => 'berita-acara-verifikasi.pdf']],
            'status' => ['label' => 'Terverifikasi', 'icon' => 'ceplok-s-check-circle'],
        ],
    ];
    $labelIndonesia = [
        'elapsed' => 'Waktu proses:',
        'details' => 'Rincian',
        'attachments' => 'Lampiran',
        'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
    ];

    $props = [
        ['items', 'array', '[]', 'Kejadian berurutan dari atas ke bawah. Isi tiap item ada di tabel Item.'],
        ['platform', '"desktop" | "mobile"', 'desktop', 'desktop menaruh garis di tengah dengan kartu berselang-seling kiri dan kanan. mobile menaruh garis di kiri dan semua kartu di kanannya.'],
        ['size', '"large" | "small"', 'large', 'large: teks 14px, avatar 44px, padding kartu 20px. small: teks 12px, avatar 36px, padding kartu 16px.'],
        ['labels', 'array', 'Elapsed time:, Details, Attachments, Jan–Dec', "Mengganti label ['elapsed' => …, 'details' => …, 'attachments' => …] di dalam kartu, serta 'months': dua belas singkatan bulan untuk time, dimulai Januari. Label yang tidak diisi tetap memakai bawaannya."],
        ['dark-mode', 'bool', 'false', 'Memaksa tema gelap. Tanpa prop ini Timeline ikut data-theme elemen pembungkusnya.'],
        ['…atribut', 'HTML', '—', 'Atribut lain (class, id, wire:key, …) diteruskan ke elemen <ol> terluar; class disambung ke class bawaannya.'],
    ];

    $itemProps = [
        ['name', 'string | HtmlString', '—', 'Wajib. Nama pelaku, ditulis paling atas dengan warna aksen.'],
        ['role', 'string | HtmlString', 'null', 'Jabatan atau unit kerja, di bawah nama.'],
        ['time', 'string | DateTimeInterface', 'null', 'Waktu kejadian: string ISO 8601 dengan jam (mis. 2026-01-01 09:05) atau DateTimeInterface, ditulis dengan format tetap 01 Jan 2026 · 09:05 di zona waktu nilainya.'],
        ['elapsed', 'string | HtmlString', 'null', 'Lama prosesnya, ditulis setelah label elapsed.'],
        ['description', 'string | HtmlString', 'null', 'Keterangan utama kejadiannya.'],
        ['details', 'string | HtmlString', 'null', 'Isi bagian Details, satu baris kosong di bawah keterangan utama.'],
        ['attachments', 'array', '[]', 'Daftar berkas bagian Attachments; isinya ada di tabel Lampiran. Berkas yang punya href menjadi tautan.'],
        ['status', 'array', 'null', 'Badge status di pojok kanan atas kartu; isinya ada di tabel Status.'],
        ['avatar', 'string', 'null', 'Alamat foto pelaku, diteruskan ke Avatar. Bila kosong atau gagal dimuat, yang tampil initials, lalu siluet bawaan.'],
        ['initials', 'string', 'null', 'Inisial di Avatar saat tidak ada foto.'],
    ];

    $statusProps = [
        ['label', 'string | HtmlString', '—', 'Wajib. Teks Badge, mis. Disetujui.'],
        ['variant', '"gray" | "brand" | "danger" | "warning" | "success"', 'success', 'Warna Badge.'],
        ['icon', 'string | HtmlString', 'null', "Ikon di kiri label: nama ikon (mis. 'ceplok-s-clock') atau markup svg()."],
        ['dismissible', 'bool', 'false', 'Menampilkan tombol tutup (×) di kanan label; menekannya memicu event dismiss.'],
        ['open', 'bool', 'null', 'Kendalikan tampil/sembunyi dari luar, seperti prop open Badge.'],
        ['size', '"sm" | "lg"', 'sm', 'Ukuran Badge.'],
        ['dark-mode', 'bool', 'dark-mode Timeline', 'Tampilan gelap Badge. Bawaannya mengikuti dark-mode Timeline.'],
        ['class', 'string', 'null', 'Class tambahan untuk Badge.'],
        ['attributes', 'array', '[]', "Atribut tambahan pada Badge, mis. ['wire:dismiss' => 'tutupStatus(2)']."],
    ];

    $attachmentProps = [
        ['name', 'string | HtmlString', '—', 'Wajib. Nama berkas yang tampil.'],
        ['href', 'string', 'null', 'Alamat berkasnya. Tanpa ini namanya tampil sebagai teks biasa.'],
        ['target', 'string', 'null', 'Tempat tautan dibuka, mis. _blank untuk tab baru. Tab baru otomatis diberi rel="noreferrer".'],
        ['download', 'bool | string', 'null', 'Mengunduh berkas alih-alih membukanya; teks menjadi nama berkas hasil unduhan. Browser hanya menurutinya untuk alamat di domain yang sama, blob:, dan data:.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Timeline View">
    <x-docs.page
        eyebrow="Components · Timeline View"
        title="Timeline View"
        description="Riwayat kejadian berurutan: siapa, kapan, dan apa yang terjadi di tiap langkah, dengan avatar di sepanjang satu garis."
        :toc="[
            'platform' => 'Platform',
            'sizes' => 'Sizes',
            'isi-item' => 'Isi item',
            'waktu' => 'Waktu',
            'status' => 'Status',
            'attachments' => 'Attachments',
            'label' => 'Label',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                <span class="text-primary-500 dark:text-primary-400">desktop</span> menaruh garis di tengah dengan kartu berselang-seling kiri dan kanan,
                untuk halaman yang lebar. <span class="text-primary-500 dark:text-primary-400">mobile</span> menaruh garis di kiri dan semua kartu di
                kanannya, untuk layar atau kolom yang sempit. Susunannya tidak berganti sendiri mengikuti
                lebar layar, jadi pilih platform-nya dari halaman pemakai. Lancip kartu selalu menunjuk ke
                avatarnya.
            </x-docs.lead>

            <div class="space-y-5">
                <x-docs.demo label="desktop" :min-width="$desktopMinWidth">
                    <x-ceplok::timeline :items="array_slice($designItems, 0, 3)" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="mobile">
                    <div class="max-w-102">
                        <x-ceplok::timeline :items="array_slice($designItems, 0, 2)" platform="mobile" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::timeline :items="$items"⟨ dark-mode⟩ />

                <x-ceplok::timeline :items="$items" ⟦platform="mobile"⟧⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="sizes" title="Sizes">
            <x-docs.lead>
                <span class="text-primary-500 dark:text-primary-400">large</span> adalah ukuran bawaan: teks 14px, avatar 44px, padding kartu 20px, dan
                jarak antar-item 24px. <span class="text-primary-500 dark:text-primary-400">small</span> untuk tampilan yang padat: teks 12px, avatar 36px,
                padding 16px, dan jarak 18px.
            </x-docs.lead>

            <div class="grid gap-5 lg:grid-cols-2">
                <x-docs.demo label="large">
                    <x-ceplok::timeline :items="array_slice($designItems, 0, 2)" platform="mobile" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="small">
                    <x-ceplok::timeline :items="array_slice($designItems, 0, 2)" platform="mobile" size="small" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::timeline :items="$items"⟨ dark-mode⟩ />

                <x-ceplok::timeline :items="$items" ⟦size="small"⟧⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="isi-item" title="Isi item">
            <x-docs.lead>
                Tiap item hanya wajib punya <span class="text-primary-500 dark:text-primary-400">name</span>; bagian lain tampil bila diisi. <span class="text-primary-500 dark:text-primary-400">role</span>
                ditulis di bawah nama, lalu <span class="text-primary-500 dark:text-primary-400">time</span>, <span class="text-primary-500 dark:text-primary-400">elapsed</span>, dan <span class="text-primary-500 dark:text-primary-400">description</span>
                sebagai keterangan utama. <span class="text-primary-500 dark:text-primary-400">details</span> dan <span class="text-primary-500 dark:text-primary-400">attachments</span> menyusul sebagai
                kelompok berikutnya, dan <span class="text-primary-500 dark:text-primary-400">status</span> menjadi Badge di pojok kanan atas kartu.
            </x-docs.lead>

            <x-docs.demo label="ringkas dan lengkap" :min-width="$desktopMinWidth">
                <x-ceplok::timeline :items="array_slice($designItems, 0, 2)" :dark-mode="$docsDark" />
            </x-docs.demo>

            <x-docs.code :mark="['\'name\'', '\'elapsed\'', '\'details\'', '\'attachments\'', '\'status\'']">
                @verbatim
                @php
                    $items = [
                        [
                            'name' => 'User 01',
                            'role' => 'Role / Department',
                            'time' => '2026-01-01 09:00',
                            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        ],
                        [
                            'name' => 'User 02',
                            'role' => 'Role / Department',
                            'time' => '2026-01-01 09:05',
                            'elapsed' => '5 minutes',
                            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                            'details' => 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                            'attachments' => [['name' => 'sample-document-02.pdf'], ['name' => 'sample-document-01.pdf']],
                            'status' => ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true],
                        ],
                    ];
                @endphp

                <x-ceplok::timeline :items="$items"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="waktu" title="Waktu">
            <x-docs.lead>
                <span class="text-primary-500 dark:text-primary-400">time</span> menerima string waktu ISO 8601 dengan jam, mis. <span class="text-primary-500 dark:text-primary-400">'2026-01-01 09:05'</span>, atau
                <span class="text-primary-500 dark:text-primary-400">DateTimeInterface</span> seperti Carbon dari Eloquent. Timeline selalu menuliskannya dengan format
                desain: tanggal dua digit, singkatan bulan, tahun, lalu jam 24 jam, mis.
                <span class="text-primary-500 dark:text-primary-400">01 Jan 2026 · 09:05</span>. Formatnya tidak mengikuti locale aplikasi, jadi tampilannya sama di
                mana pun. Jamnya ditulis di zona waktu nilainya: string tanpa zona waktu dibaca di zona waktu
                aplikasi. String lain, mis. <span class="text-primary-500 dark:text-primary-400">'01/02/2026'</span>, ditolak karena urutan hari dan bulannya rancu.
                Singkatan bulannya bisa diganti lewat <span class="text-primary-500 dark:text-primary-400">labels['months']</span>; lihat bagian Label.
            </x-docs.lead>

            <x-docs.demo label="tanggal dan jam dua digit, jam 24 jam">
                <div class="max-w-102">
                    <x-ceplok::timeline :items="$waktuItems" platform="mobile" :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>

            <x-docs.code mark="'time'">
                @verbatim
                'time' => '2026-01-01 09:05',            // 01 Jan 2026 · 09:05
                'time' => '2026-08-07 07:30',            // 07 Aug 2026 · 07:30
                'time' => '2026-12-25T15:45:00+07:00',   // 25 Dec 2026 · 15:45, zona waktunya ikut dibaca

                // Dari Eloquent: Carbon di zona waktu aplikasi.
                'time' => $log->created_at,
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="status" title="Status">
            <x-docs.lead>
                <span class="text-primary-500 dark:text-primary-400">status</span> dirender dengan komponen Badge, jadi kuncinya mengikuti prop Badge:
                <span class="text-primary-500 dark:text-primary-400">variant</span>, <span class="text-primary-500 dark:text-primary-400">icon</span>, <span class="text-primary-500 dark:text-primary-400">dismissible</span>, <span class="text-primary-500 dark:text-primary-400">open</span>, dan lainnya,
                dengan <span class="text-primary-500 dark:text-primary-400">label</span> sebagai isinya. Bawaannya mengikuti desain: variant
                <span class="text-primary-500 dark:text-primary-400">success</span> ukuran <span class="text-primary-500 dark:text-primary-400">sm</span>. Desainnya memakai ikon jam dan tombol tutup; pilih
                variant dan ikon lain untuk tahap yang berbeda.
            </x-docs.lead>

            <x-docs.demo label="warning, success, dan Badge seperti di desain">
                <div class="max-w-102">
                    <x-ceplok::timeline :items="$statusItems" platform="mobile" :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>

            <x-docs.code :mark="['\'variant\'', '\'icon\'', '\'dismissible\'']">
                @verbatim
                'status' => ['label' => 'Menunggu', 'variant' => 'warning', 'icon' => 'ceplok-s-exclamation-circle'],
                'status' => ['label' => 'Disetujui', 'icon' => 'ceplok-s-check-circle'],
                'status' => ['label' => 'Badge', 'icon' => 'ceplok-s-clock', 'dismissible' => true],
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="attachments" title="Attachments">
            <x-docs.lead>
                Tiap lampiran berisi <span class="text-primary-500 dark:text-primary-400">name</span> dan, bila berkasnya bisa dibuka, <span class="text-primary-500 dark:text-primary-400">href</span>. Tanpa
                <span class="text-primary-500 dark:text-primary-400">href</span> namanya tampil sebagai teks biasa seperti di desain. Dengan <span class="text-primary-500 dark:text-primary-400">href</span> ia
                menjadi tautan: <span class="text-primary-500 dark:text-primary-400">target="_blank"</span> membukanya di tab baru, dan
                <span class="text-primary-500 dark:text-primary-400">download</span> mengunduhnya. Browser hanya menuruti <span class="text-primary-500 dark:text-primary-400">download</span> untuk alamat di
                domain yang sama, <span class="text-primary-500 dark:text-primary-400">blob:</span>, dan <span class="text-primary-500 dark:text-primary-400">data:</span>. Timeline tidak menyimpan berkasnya:
                alamatnya bisa dari server, atau dari <span class="text-primary-500 dark:text-primary-400">temporaryUrl()</span> milik berkas unggahan Livewire.
            </x-docs.lead>

            <x-docs.demo label="teks biasa, tab baru, dan unduhan">
                <div class="max-w-102">
                    <x-ceplok::timeline :items="$lampiranItems" platform="mobile" :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>

            <x-docs.code :mark="['\'target\'', '\'download\'']">
                @verbatim
                'attachments' => [
                    ['name' => 'surat-permohonan.pdf'],
                    ['name' => 'berita-acara.pdf', 'href' => asset('files/sample-document.pdf'), 'target' => '_blank'],
                    ['name' => 'lampiran-ktp.pdf', 'href' => asset('files/sample-document.pdf'), 'download' => 'lampiran-ktp.pdf'],
                ],
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="label" title="Label">
            <x-docs.lead>
                Label <span class="text-primary-500 dark:text-primary-400">Elapsed time:</span>, <span class="text-primary-500 dark:text-primary-400">Details</span>, dan <span class="text-primary-500 dark:text-primary-400">Attachments</span> mengikuti teks
                desain, dan singkatan bulan di <span class="text-primary-500 dark:text-primary-400">time</span> bawaannya bahasa Inggris. Ganti lewat
                <span class="text-primary-500 dark:text-primary-400">labels</span>, mis. untuk aplikasi berbahasa Indonesia: <span class="text-primary-500 dark:text-primary-400">months</span> berisi dua belas
                singkatan bulan, dimulai Januari. Label yang tidak diisi tetap memakai bawaannya. Contoh ini
                juga memakai <span class="text-primary-500 dark:text-primary-400">initials</span> dan foto <span class="text-primary-500 dark:text-primary-400">avatar</span>, yang diteruskan ke komponen
                Avatar.
            </x-docs.lead>

            <x-docs.demo label="labels bahasa Indonesia">
                <div class="max-w-102">
                    <x-ceplok::timeline :items="$contohIndonesia" platform="mobile" :labels="$labelIndonesia" :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>

            <x-docs.code :mark="['\'initials\'', '\'avatar\'', 'labels', '\'months\'']">
                @verbatim
                @php
                    $items = [
                        ['name' => 'Rina Wulandari', 'role' => 'Pemohon', 'initials' => 'RW', /* … */],
                        ['name' => 'Budi Santoso', 'role' => 'Verifikator / Dinas PMPTSP', 'avatar' => asset('images/budi.jpg'), /* … */],
                    ];
                @endphp

                <x-ceplok::timeline
                    :items="$items"
                    platform="mobile"
                    :labels="[
                        'elapsed' => 'Waktu proses:',
                        'details' => 'Rincian',
                        'attachments' => 'Lampiran',
                        'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    ]"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Garis, tepi kartu, dan latar avatar memakai token semantik <span class="text-primary-500 dark:text-primary-400">border</span>, serta nama dan
                jabatan memakai <span class="text-primary-500 dark:text-primary-400">brand</span>. Warna lainnya dari token komponen
                <span class="text-primary-500 dark:text-primary-400">--color-timeline-surface</span>, <span class="text-primary-500 dark:text-primary-400">-content</span>, dan <span class="text-primary-500 dark:text-primary-400">-avatar</span>. Aktifkan
                lewat prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span>, atau biarkan Timeline ikut
                <span class="text-primary-500 dark:text-primary-400">data-theme="dark"</span> dari pembungkusnya. Kartu menjadi gray-800, garis
                dan tepinya gray-700, nama dan jabatan primary-400, serta teks isi dan siluet avatar
                gray-100. Badge status memakai tampilan gelapnya sendiri (green-900 dengan teks
                green-300) lewat <span class="text-primary-500 dark:text-primary-400">dark-mode</span>. Badge belum berbasis token, jadi ia tidak ikut gelap
                lewat <span class="text-primary-500 dark:text-primary-400">data-theme</span> pembungkus; pakai prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> bila kartunya memuat
                status.
            </x-slot:lead>

            <x-docs.demo label="dark-mode" dark :min-width="$desktopMinWidth">
                <x-ceplok::timeline :items="array_slice($designItems, 0, 2)" dark-mode />
            </x-docs.demo>

            <x-docs.code>
                @verbatim
                <x-ceplok::timeline ⟦dark-mode⟧ :items="$items" />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.timeline :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Kunci <span class="text-primary-500 dark:text-primary-400">attributes</span> pada <span class="text-primary-500 dark:text-primary-400">status</span> diteruskan ke Badge-nya. Tombol tutup Badge
                memicu event <span class="text-primary-500 dark:text-primary-400">dismiss</span>, jadi <span class="text-primary-500 dark:text-primary-400">wire:dismiss</span> bisa memanggil metode
                komponen Livewire. Di contoh ini status yang ditutup dicatat server, dan Timeline dirender
                ulang tanpa status itu.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.timeline-livewire :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code mark="'attributes'">
                @verbatim
                'status' => [
                    'label' => 'Badge',
                    'icon' => 'ceplok-s-clock',
                    'dismissible' => true,
                    'attributes' => ['wire:dismiss' => "tutupStatus({$index})"],
                ],
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>Seluruh prop Timeline, isi tiap item, status, dan bentuk berkas lampirannya.</x-docs.lead>

            <div class="space-y-8">
                <x-docs.props-table :rows="$props" min-width="46rem" />

                <div>
                    <h3 class="mb-3 text-sm font-black text-gray-900 dark:text-white">Item</h3>
                    <x-docs.props-table :rows="$itemProps" min-width="46rem" />
                </div>

                <div>
                    <h3 class="mb-3 text-sm font-black text-gray-900 dark:text-white">Status</h3>
                    <x-docs.props-table :rows="$statusProps" min-width="46rem" />
                </div>

                <div>
                    <h3 class="mb-3 text-sm font-black text-gray-900 dark:text-white">Lampiran</h3>
                    <x-docs.props-table :rows="$attachmentProps" min-width="46rem" />
                </div>
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
