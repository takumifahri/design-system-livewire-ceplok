@php
    use App\Docs\Theme;

    $tanggal = '2021-06-25';
    $sepekan = ['start' => '2021-06-20', 'end' => '2021-06-26'];
    $rentang = ['start' => '2021-06-25', 'end' => '2021-07-12'];
    $semuaWaktu = ['start' => null, 'end' => null];

    // Batas data contoh: tiket pesawat yang bisa dipesan dari hari ini sampai tanggal
    // yang sama tahun depan. Yang ini sengaja mengikuti hari ini — begitulah pemakaiannya.
    $hariIni = today();
    $setahunLagi = today()->addYear();

    $props = [
        ['type', '"single" | "period" | "multiple"', 'single', 'Bentuk pemilih: satu tanggal, satu kalender dengan pintasan periode, atau rentang dengan dua kalender.'],
        ['shortcuts', 'bool', 'false', 'Khusus multiple: menambahkan Minggu ini, Bulan ini, dan Semua Waktu di samping Hari ini dan Hapus. Period selalu memilikinya.'],
        ['platform', '"default" | "mobile"', 'default', 'Tata letak mobile: lebar bawaan 325px dan panel selebar kotak. Single menaruh kisinya di tengah, period memuat empat pintasannya dalam satu baris, dan multiple menumpuk kotak serta kalendernya.'],
        ['min', 'string | DateTimeInterface', 'null', 'Awal data: tanggal sebelumnya tidak bisa dipilih, pintasan dipotong ke sini, dan Semua Waktu dimulai dari sini.'],
        ['max', 'string | DateTimeInterface', 'null', 'Akhir data: tanggal sesudahnya tidak bisa dipilih, pintasan dipotong ke sini, dan Semua Waktu berakhir di sini.'],
        ['label', 'string | slot', 'null', 'Teks label di atas kotak tanggal.'],
        ['placeholder', 'string', 'Pilih Tanggal', 'Teks saat belum ada tanggal. Pada multiple dipakai kedua kotak, kecuali end-placeholder diisi.'],
        ['end-placeholder', 'string', 'placeholder', 'Teks kotak kedua pada multiple.'],
        ['value', 'string | array | null', 'null', 'Nilai awal: \'YYYY-MM-DD\' untuk single, [\'start\' => …, \'end\' => …] untuk period dan multiple. Di dalam Livewire nilai awal wire:model dibaca dari propertinya.'],
        ['wire:model', 'string | array | null', '—', 'Diikat ke elemen terluar; nilainya berbentuk sama dengan value.'],
        ['wire:change', 'event', '—', 'Dipicu setiap tanggal atau pintasan dipilih, dengan detail.value; padanan onChange. Pada period dan multiple juga saat baru tanggal mulai yang terisi.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap untuk kotak, panel, kalender, dan tombolnya.'],
        ['disabled', 'bool', 'false', 'Menonaktifkan kotak tanggal sehingga panel tidak bisa dibuka.'],
        ['name', 'string', 'null', 'Nama field formulir. Period dan multiple mengirim dua field: nama[start] dan nama[end].'],
        ['id', 'string', 'otomatis', 'id kotak pertama; label menunjuk ke sana.'],
        ['…atribut', 'HTML', '—', 'Diteruskan ke pembungkus terluar (class, style, data-*, …). Lebarnya dibatasi selebar panel; max-w-none melepasnya.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Datepicker">
    <x-docs.page
        eyebrow="Form"
        title="Datepicker"
        description="Pemilih tanggal dengan kalender di panel melayang — satu tanggal, satu kalender dengan pintasan periode, atau rentang dengan dua kalender. Tersedia juga tata letak mobile dan tampilan gelap."
        :toc="[
            'single' => 'Single',
            'period' => 'Period',
            'multiple' => 'Multiple',
            'mobile' => 'Mobile',
            'batas' => 'Batas data',
            'dark-mode' => 'Dark mode',
            'nilai' => 'Nilai & formulir',
            'papan-ketik' => 'Papan ketik',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="single" title="Single">
            <x-docs.lead>
                Kotak setinggi 42px dengan ikon kalender di kiri. Menekannya membuka kalender tepat di bawah kotak,
                lengkap dengan tombol <span class="text-primary-500 dark:text-primary-400">Hari ini</span> dan <span class="text-primary-500 dark:text-primary-400">Hapus</span>. Memilih tanggal langsung menutup panelnya.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Belum dipilih">
                    <x-ceplok::datepicker label="Nama Tanggal" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Sudah dipilih">
                    <x-ceplok::datepicker label="Nama Tanggal" :value="$tanggal" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Komponen Livewire: public ?string $tanggal = null; --}}
                <x-ceplok::datepicker
                    label="Nama Tanggal"
                    ⟦wire:model⟧="tanggal"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Kotak single selebar 325px, sama dengan di mobile, sedangkan panelnya 284px — seperti desainnya.
                Bentuk lain selebar panelnya, 325px untuk period dan 600px untuk multiple (325px di mobile), supaya
                tepi kotak dan kalendernya segaris. Di wadah yang lebih sempit ia ikut menyusut;
                <span class="text-primary-500 dark:text-primary-400">class="max-w-none"</span> melepas batas itu.
            </p>
        </x-docs.section>

        <x-docs.section id="period" title="Period">
            <x-docs.lead>
                Satu kalender dengan lima pintasan — <span class="text-primary-500 dark:text-primary-400">Hari ini</span>, <span class="text-primary-500 dark:text-primary-400">Minggu ini</span>, <span class="text-primary-500 dark:text-primary-400">Bulan ini</span>,
                <span class="text-primary-500 dark:text-primary-400">Hapus</span>, dan <span class="text-primary-500 dark:text-primary-400">Semua Waktu</span> — untuk menyaring daftar berdasarkan waktu. Karena Minggu ini
                dan Bulan ini adalah rentang, nilainya berupa rentang <span class="text-primary-500 dark:text-primary-400">['start' => …, 'end' => …]</span>. Rentang lain dipilih
                langsung di kalendernya dengan dua klik: klik pertama mengisi tanggal mulai, klik kedua tanggal selesai,
                dan panel baru tertutup setelah klik kedua.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Satu minggu">
                    <x-ceplok::datepicker type="period" label="Nama Tanggal" :value="$sepekan" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Semua Waktu">
                    <x-ceplok::datepicker type="period" label="Nama Tanggal" :value="$semuaWaktu" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Komponen Livewire: public ?array $periode = null; --}}
                <x-ceplok::datepicker
                    ⟦type⟧="period"
                    label="Nama Tanggal"
                    wire:model="periode"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="multiple" title="Multiple">
            <x-docs.lead>
                Dua kotak — tanggal mulai dan tanggal selesai — dengan dua kalender berdampingan. Seperti period,
                klik pertama mengisi tanggal mulai dan klik kedua tanggal selesai, baru panelnya tertutup; tanggal
                di antaranya diberi latar abu. Seperti single, tombolnya hanya <span class="text-primary-500 dark:text-primary-400">Hari ini</span> dan <span class="text-primary-500 dark:text-primary-400">Hapus</span>;
                prop <span class="text-primary-500 dark:text-primary-400">shortcuts</span> menambahkan <span class="text-primary-500 dark:text-primary-400">Minggu ini</span>, <span class="text-primary-500 dark:text-primary-400">Bulan ini</span>, dan <span class="text-primary-500 dark:text-primary-400">Semua Waktu</span>
                seperti pada period.
            </x-docs.lead>
            <div class="grid gap-5">
                <x-docs.demo label="Rentang terpilih">
                    <x-ceplok::datepicker type="multiple" label="Nama Tanggal" :value="$rentang" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Dengan shortcuts">
                    <x-ceplok::datepicker type="multiple" shortcuts label="Nama Tanggal" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::datepicker
                    ⟦type⟧="multiple"
                    label="Nama Tanggal"
                    wire:model="rentang"
                    ⟨dark-mode⟩
                />

                {{-- Dengan Minggu ini, Bulan ini, dan Semua Waktu --}}
                <x-ceplok::datepicker type="multiple" ⟦shortcuts⟧ label="Nama Tanggal"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Kedua kalender bisa digeser sendiri-sendiri, jadi rentang yang panjang tidak perlu melewati setiap
                bulan di antaranya. Kotak mana pun yang ditekan, pilihan selalu dimulai dari tanggal mulai; tanggal
                selesai yang jatuh sebelum tanggal mulai dianggap memulai rentang baru. Tanggal bulan sebelah yang
                ikut tampil di tepi kalender — misalnya 27–30 September di kalender Oktober — tidak disorot bila
                bulannya sudah tampil di kalender sebelah: sorotannya cukup di sana, tetapi tanggal itu tetap bisa
                ditekan. Di layar sempit kedua kalender bertumpuk, tetapi kotaknya tetap berdampingan — untuk
                ponsel, pakai tata letak mobile di bawah.
            </p>
        </x-docs.section>

        <x-docs.section id="mobile" title="Mobile">
            <x-docs.lead>
                Untuk ponsel, <span class="text-primary-500 dark:text-primary-400">platform="mobile"</span> memakai tata letak dari desain mobile: ketiga bentuk
                selebar 325px, dan panelnya selebar kotak. Single menaruh kisi tanggalnya di tengah panel, period
                memuat <span class="text-primary-500 dark:text-primary-400">Hari ini</span>, <span class="text-primary-500 dark:text-primary-400">Minggu ini</span>, <span class="text-primary-500 dark:text-primary-400">Bulan ini</span>, dan <span class="text-primary-500 dark:text-primary-400">Hapus</span> dalam satu
                baris, dan multiple menumpuk kotak tanggal selesai di bawah kotak tanggal mulai serta kalender
                kedua di bawah yang pertama.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Single">
                    <x-ceplok::datepicker platform="mobile" label="Nama Tanggal" :value="$tanggal" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Period">
                    <x-ceplok::datepicker type="period" platform="mobile" label="Nama Tanggal" :value="$sepekan" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Multiple">
                    <x-ceplok::datepicker type="multiple" platform="mobile" label="Nama Tanggal" :value="$rentang" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::datepicker
                    type="multiple"
                    ⟦platform⟧="mobile"
                    label="Nama Tanggal"
                    wire:model="rentang"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Dengan <span class="text-primary-500 dark:text-primary-400">class="max-w-none"</span> ia memenuhi wadahnya dan panelnya ikut melebar. Panel
                multiple setinggi ini jarang muat di layar ponsel, jadi bila tidak muat di bawah maupun di atas
                kotak, tingginya dibatasi ruang yang tersedia dan isinya digulir di dalam panel. Selebihnya sama
                dengan desktop: cara memilih, papan ketik, dan nilainya. Kedua kalender multiple juga tetap bisa
                digeser sendiri-sendiri — yang atas selalu lebih awal dari yang bawah.
            </p>
        </x-docs.section>

        <x-docs.section id="batas" title="Batas data">
            <x-docs.lead>
                Prop <span class="text-primary-500 dark:text-primary-400">min</span> dan <span class="text-primary-500 dark:text-primary-400">max</span> menandai dari mana data tersedia sampai di mana batas akhirnya —
                misalnya tiket pesawat yang bisa dipesan dari hari ini sampai tanggal yang sama tahun depan.
                Tanggal di luarnya tidak bisa dipilih, dan <span class="text-primary-500 dark:text-primary-400">Semua Waktu</span> memilih seluruh rentang itu: dari
                <span class="text-primary-500 dark:text-primary-400">min</span> sampai <span class="text-primary-500 dark:text-primary-400">max</span>.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Period">
                    <x-ceplok::datepicker type="period" label="Tanggal penerbangan" :min="$hariIni" :max="$setahunLagi" :dark-mode="$docsDark" />
                </x-docs.demo>
                <div class="sm:col-span-2">
                    <x-docs.demo label="Multiple dengan shortcuts">
                        <x-ceplok::datepicker type="multiple" shortcuts label="Tanggal penerbangan" :min="$hariIni" :max="$setahunLagi" :dark-mode="$docsDark" />
                    </x-docs.demo>
                </div>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::datepicker
                    type="period"
                    label="Tanggal penerbangan"
                    :⟦min⟧="today()"
                    :⟦max⟧="today()->addYear()"
                    ⟨dark-mode⟩
                />
                {{-- Semua Waktu → ['start' => hari ini, 'end' => setahun lagi] --}}
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Pintasan lain ikut dipotong ke batas itu: Minggu ini yang dimulai sebelum <span class="text-primary-500 dark:text-primary-400">min</span> hanya diambil
                mulai <span class="text-primary-500 dark:text-primary-400">min</span>, sedangkan pintasan yang seluruh rentangnya di luar batas — misalnya Hari ini saat
                datanya baru mulai minggu depan — dimatikan. Panah bulan berhenti di bulan pertama dan terakhir yang
                masih punya tanggal di dalam batas, begitu pula panah papan ketik. Tanpa <span class="text-primary-500 dark:text-primary-400">min</span> dan <span class="text-primary-500 dark:text-primary-400">max</span>,
                Semua Waktu bernilai <span class="text-primary-500 dark:text-primary-400">['start' => null, 'end' => null]</span> — rentang tanpa batas.
            </p>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti seluruh warnanya ke tampilan gelap: kotak dan panel gray-700,
                tanggal terpilih primary-600, dan tombol pintasan berisi. Tata letak mobile memakai warna yang sama.
            </x-slot:lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Single" dark>
                    <x-ceplok::datepicker dark-mode label="Nama Tanggal" :value="$tanggal" />
                </x-docs.demo>
                <x-docs.demo label="Period" dark>
                    <x-ceplok::datepicker dark-mode type="period" label="Nama Tanggal" />
                </x-docs.demo>
                <div class="sm:col-span-2">
                    <x-docs.demo label="Multiple" dark>
                        <x-ceplok::datepicker dark-mode type="multiple" label="Nama Tanggal" :value="$rentang" />
                    </x-docs.demo>
                </div>
                <x-docs.demo label="Multiple · Mobile dengan shortcuts" dark>
                    <x-ceplok::datepicker dark-mode type="multiple" platform="mobile" shortcuts label="Nama Tanggal" :value="$rentang" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::datepicker ⟦dark-mode⟧ label="Nama Tanggal" />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <x-docs.section id="nilai" title="Nilai & formulir">
            <x-docs.lead>
                Single bernilai <span class="text-primary-500 dark:text-primary-400">'YYYY-MM-DD'</span>, sedangkan period dan multiple bernilai rentang
                <span class="text-primary-500 dark:text-primary-400">['start' => …, 'end' => …]</span>. Tanpa jam dan zona waktu, jadi dua tanggal cukup dibandingkan sebagai teks.
            </x-docs.lead>
            <ul class="max-w-2xl space-y-2 text-body-sm text-gray-600 dark:text-gray-300">
                <li>
                    <span class="text-primary-500 dark:text-primary-400">null</span> — belum diisi, atau setelah <span class="text-primary-500 dark:text-primary-400">Hapus</span>.
                </li>
                <li>
                    <span class="text-primary-500 dark:text-primary-400">['start' => '2021-06-20', 'end' => '2021-06-26']</span> — rentang. Hari ini, Minggu ini (Minggu–Sabtu, mengikuti
                    urutan hari di kalender), dan Bulan ini (tanggal 1 sampai terakhir) menghasilkan bentuk ini —
                    dipotong ke <span class="text-primary-500 dark:text-primary-400">min</span> dan <span class="text-primary-500 dark:text-primary-400">max</span> bila ada.
                </li>
                <li>
                    <span class="text-primary-500 dark:text-primary-400">['start' => '2021-06-20', 'end' => null]</span> — period atau multiple yang baru terisi tanggal mulainya.
                </li>
                <li>
                    <span class="text-primary-500 dark:text-primary-400">['start' => min, 'end' => max]</span> — Semua Waktu: seluruh rentang data. Sisi yang tidak diberi
                    batas bernilai <span class="text-primary-500 dark:text-primary-400">null</span>, jadi tanpa keduanya hasilnya <span class="text-primary-500 dark:text-primary-400">['start' => null, 'end' => null]</span> —
                    rentang tanpa batas, berbeda dari belum diisi.
                </li>
            </ul>
            <x-docs.code>
                @verbatim
                <x-ceplok::datepicker ⟦name⟧="tanggal_lahir"⟨ dark-mode⟩ />
                {{-- terkirim: tanggal_lahir=2021-06-25 --}}

                <x-ceplok::datepicker type="multiple" ⟦name⟧="periode"⟨ dark-mode⟩ />
                {{-- terkirim: periode[start]=2021-06-25 & periode[end]=2021-07-12 --}}
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Dengan <span class="text-primary-500 dark:text-primary-400">name</span>, tanggalnya ikut terkirim bersama formulir dalam format <span class="text-primary-500 dark:text-primary-400">YYYY-MM-DD</span>. Semua
                Waktu terkirim sebagai <span class="text-primary-500 dark:text-primary-400">min</span> dan <span class="text-primary-500 dark:text-primary-400">max</span>-nya; sisi yang tidak berbatas terkirim kosong.
            </p>
        </x-docs.section>

        <x-docs.section id="papan-ketik" title="Papan ketik">
            <x-docs.lead>
                Begitu panel terbuka, fokus langsung berada di tanggal terpilih — atau hari ini — sehingga kalender
                bisa ditelusuri tanpa tetikus.
            </x-docs.lead>
            <ul class="max-w-2xl space-y-2 text-body-sm text-gray-600 dark:text-gray-300">
                <li>
                    <span class="text-primary-500 dark:text-primary-400">←</span> <span class="text-primary-500 dark:text-primary-400">→</span> hari sebelumnya dan berikutnya; <span class="text-primary-500 dark:text-primary-400">↑</span> <span class="text-primary-500 dark:text-primary-400">↓</span> minggu sebelumnya dan
                    berikutnya.
                </li>
                <li>
                    <span class="text-primary-500 dark:text-primary-400">Home</span> dan <span class="text-primary-500 dark:text-primary-400">End</span> ke awal dan akhir minggu.
                </li>
                <li>
                    <span class="text-primary-500 dark:text-primary-400">PageUp</span> dan <span class="text-primary-500 dark:text-primary-400">PageDown</span> ke bulan sebelumnya dan berikutnya; bersama <span class="text-primary-500 dark:text-primary-400">Shift</span>, ke
                    tahun sebelumnya dan berikutnya.
                </li>
                <li>
                    Ketik tanggalnya untuk melompat langsung ke sana: <span class="text-primary-500 dark:text-primary-400">17</span> ke tanggal 17 bulan itu,
                    <span class="text-primary-500 dark:text-primary-400">17 agu</span> ke 17 Agustus, <span class="text-primary-500 dark:text-primary-400">17/8/1945</span> atau <span class="text-primary-500 dark:text-primary-400">17 agustus 1945</span> ke tanggal itu, dan
                    <span class="text-primary-500 dark:text-primary-400">1945</span> ke tahunnya. Bagian yang tidak diketik tetap seperti tanggal yang sedang difokus.
                    Ketikan dengan jeda di bawah satu detik digabung, dan <span class="text-primary-500 dark:text-primary-400">Backspace</span> menghapus huruf terakhirnya.
                    Tanggal di luar <span class="text-primary-500 dark:text-primary-400">min</span>/<span class="text-primary-500 dark:text-primary-400">max</span> berhenti di tepinya, sama dengan panah.
                </li>
                <li>
                    <span class="text-primary-500 dark:text-primary-400">Enter</span> atau <span class="text-primary-500 dark:text-primary-400">Spasi</span> memilih tanggal; <span class="text-primary-500 dark:text-primary-400">Escape</span> atau klik di luar panel menutupnya,
                    dan fokus kembali ke kotak tanggalnya. Spasi di tengah ketikan tanggal ikut jadi bagian ketikan.
                </li>
            </ul>
            <x-docs.demo label="Coba: buka, lalu ketik 17 agustus 1945 dan tekan Enter">
                <x-ceplok::datepicker label="Tanggal lahir" placeholder="Pilih tanggal lahir" :max="$hariIni" :dark-mode="$docsDark" />
            </x-docs.demo>
        </x-docs.section>

        <livewire:playground.datepicker :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Pasang <span class="text-primary-500 dark:text-primary-400">wire:model</span> pada tag komponennya: nilainya diikat ke elemen terluar lewat
                <span class="text-primary-500 dark:text-primary-400">x-modelable</span>, dan nilai awal propertinya sudah dirender server. Dengan
                <span class="text-primary-500 dark:text-primary-400">wire:model.live</span>, setiap tanggal atau pintasan yang dipilih langsung sampai ke server — rentang
                yang baru terisi tanggal mulainya pun tetap bisa diselesaikan dengan klik kedua. Pilih periode di bawah
                untuk menyaring daftarnya.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.datepicker-filter :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                {{-- Komponen Livewire: public ?array $periode = null; --}}
                <x-ceplok::datepicker
                    type="period"
                    label="Tanggal pengajuan"
                    ⟦wire:model.live⟧="periode"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya.</x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="48rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
