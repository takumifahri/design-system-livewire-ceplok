@php
    $autoRows = [
        ['id' => 1, 'namaLayanan' => 'Pembuatan KTP', 'jenis_layanan' => 'Kependudukan', 'estimasiHari' => 3, 'aktif' => true],
        ['id' => 2, 'namaLayanan' => 'Akta Kelahiran', 'jenis_layanan' => 'Pencatatan Sipil', 'estimasiHari' => 7, 'aktif' => true],
        ['id' => 3, 'namaLayanan' => 'Surat Pindah', 'jenis_layanan' => 'Kependudukan', 'estimasiHari' => 5, 'aktif' => false],
    ];

    $tableProps = [
        ['columns', 'array | null', 'dari data', 'Daftar kolom yang tampil. Kalau tidak diisi, kolom dibuat otomatis dari data.'],
        ['data', 'array | Collection | LengthAwarePaginator', '[]', 'Isi tabel. Dengan manual atau paginator, cukup baris halaman yang sedang tampil.'],
        ['row-key', 'string | Closure', '—', 'Field yang nilainya unik di tiap baris, biasanya "id".'],
        ['size', '"normal" | "compact"', 'normal', 'Ukuran tabel. compact lebih rapat dan teksnya lebih kecil.'],
        ['search', 'array | bool | null', 'null', 'Menampilkan kotak pencarian di kiri atas. Isi array berisi atribut <input>, mis. placeholder dan wire:model.live.debounce.300ms.'],
        ['filter', 'array | bool | null', 'null', 'Menampilkan tombol "Filter Data" untuk memilih kolom yang tampil. Isi [] untuk memakai pengaturan bawaan.'],
        ['actions', 'slot', '—', 'Tombol di kanan atas tabel, mis. "Tambah Data".'],
        ['selectable', 'bool', 'false', 'Menambahkan checkbox di tiap baris.'],
        ['wire:model', 'atribut', '—', 'Mengikat daftar row-key baris yang dicentang ke properti array Livewire, padanan selectedKeys + onSelectionChange.'],
        ['selected-keys', 'array | null', 'null', 'Baris yang dicentang, tanpa wire:model.'],
        ['default-selected-keys', 'array | null', 'null', 'Baris yang dicentang saat tabel pertama tampil.'],
        ['sort', "['key' => …, 'direction' => 'asc' | 'desc'] | null", 'null', 'Urutan yang sedang aktif, biasanya properti Livewire.'],
        ['wire:sort-change', 'event', '—', 'Dipicu saat judul kolom diklik, dengan $event.detail.sort (urutan berikutnya atau null).'],
        ['sort-icon', 'Closure(?string $direction)', 'panah', 'Ganti ikon sort untuk semua kolom.'],
        ['header-align', '"left" | "center" | "right" | null', 'null', 'Posisi semua judul kolom sekaligus. header-align di kolom tetap menang. Kalau kosong, judul ikut align kolomnya.'],
        ['pagination', 'array | bool | null', 'null', 'Membagi data per halaman. Tanpa ini semua baris tampil.'],
        ['wire:page-change', 'event', '—', 'Dipicu saat user pindah halaman, dengan $event.detail.page.'],
        ['manual', 'bool', 'false', 'Pasang kalau urutan dan halaman diurus server. Otomatis bila data berupa paginator.'],
        ['striped', 'bool', 'false', 'Baris zebra: baris genap diberi latar abu-abu muda.'],
        ['loading', 'bool', 'false', 'Menampilkan ikon memuat sebagai ganti isi tabel.'],
        ['empty-text', 'string | slot', 'Tidak ada data', 'Tulisan yang tampil kalau data kosong.'],
    ];

    $columnProps = [
        ['key', 'string', '—', 'Nama field di data yang ditampilkan di kolom ini. Boleh notasi titik, mis. "pemohon.nama".'],
        ['header', 'string | Htmlable', '—', 'Judul kolom.'],
        ['children', 'array', 'null', 'Kolom anak. Kolom ini jadi judul induk di atas anak-anaknya.'],
        ['cell', 'Closure($row, $index)', 'row[key]', 'Isi sel sendiri: teks, HtmlString, atau view(), mis. Badge.'],
        ['actions', 'array', 'null', 'Tombol aksi di tiap baris. Tidak dipakai kalau cell diisi.'],
        ['image', 'array', 'null', 'Gambar di sel, bisa diklik untuk dilihat besar. Tidak dipakai kalau cell atau actions diisi.'],
        ['file', 'array', 'null', 'Berkas di sel: ikon jenis berkas dan nama, klik untuk pratinjau.'],
        ['sortable', 'bool', 'false', 'Kolom bisa diurutkan dengan klik judulnya.'],
        ['sort-value', 'Closure($row)', 'row[key]', 'Nilai yang dipakai untuk mengurutkan.'],
        ['sort-fn', 'Closure($a, $b): int', 'null', 'Fungsi pembanding sendiri. Dipakai sebagai ganti sort-value.'],
        ['sort-directions', "list<'asc' | 'desc'>", "['asc', 'desc']", "Urutan klik. ['desc', 'asc'] = mulai dari terbesar."],
        ['sort-resettable', 'bool', 'true', 'Setelah naik dan turun, klik lagi kembali ke urutan awal.'],
        ['sort-icon', 'Closure(?string $direction)', 'ikut Table', 'Ganti ikon sort khusus kolom ini.'],
        ['align', '"left" | "center" | "right"', 'center', 'Posisi isi sel: kiri, tengah, atau kanan.'],
        ['header-align', '"left" | "center" | "right"', 'ikut align, atau left', 'Posisi judul kolom. Kalau tidak diisi, ikut align. Kalau align juga kosong, rata kiri. Judul induk kolom bertingkat bawaannya center.'],
        ['emphasis', 'bool', 'false', 'Teks lebih gelap, biasanya untuk kolom utama seperti nama.'],
        ['width', 'string', 'null', 'Lebar kolom, mis. "12rem".'],
        ['min-width', 'string', 'null', 'Lebar paling kecil kolom.'],
        ['hideable', 'bool', 'true', 'Kolom bisa disembunyikan lewat Filter Data. Isi false untuk kolom yang harus selalu tampil.'],
        ['wrap', 'bool', 'false', 'Izinkan teks turun baris, mis. untuk alamat panjang.'],
    ];

    $actionProps = [
        ['key', 'string', '—', 'Nama unik tombol, mis. "ubah".'],
        ['icon', 'string | Htmlable', '—', "Ikon tombol: nama ikon (mis. 'ceplok-s-edit') atau svg(…)."],
        ['label', 'string | Closure($row)', '—', 'Judul tombol, muncul sebagai tooltip.'],
        ['show-label', 'bool', 'false', 'Tulis judul di samping ikon.'],
        ['theme', '"primary" | "green" | "gray" | "purple" | "orange" | "yellow" | "red"', 'primary', 'Warna tombol.'],
        ['variant', '"filled" | "outline"', 'filled', 'Tombol berisi warna atau hanya garis tepi.'],
        ['tone', '"light" | "dark"', 'light', 'Terang-gelapnya warna tombol.'],
        ['icon-size', '"s" | "base" | "l" | "xl"', 'base', 'Ukuran ikon. Tombol ikut membesar: 20px, 24px, 32px, 40px.'],
        ['radius', '"none" | "s" | "base" | "l" | "full"', 'base', 'Kelengkungan sudut tombol. "full" membuat tombol bulat.'],
        ['href', 'string | Closure($row)', 'null', 'Alamat tujuan saat tombol diklik.'],
        ['target', 'string', 'null', 'Isi "_blank" untuk membuka di tab baru.'],
        ['attributes', 'array | Closure($row, $index)', '[]', "Atribut tombol, padanan onClick, mis. ['wire:click' => \"hapus({\$row->id})\"]."],
        ['hidden', 'bool | Closure($row)', 'false', 'Sembunyikan tombol.'],
        ['disabled', 'bool | Closure($row)', 'false', 'Tombol tampil tapi tidak bisa diklik.'],
    ];

    $imageProps = [
        ['src', 'Closure($row)', '—', 'Alamat gambar. Kalau kosong, sel berisi "—".'],
        ['alt', 'string | Closure($row)', "''", 'Keterangan gambar untuk pembaca layar.'],
        ['caption', 'Closure($row)', 'null', 'Teks di bawah gambar, mis. nama file. Di size compact hanya tampil saat pratinjau dibuka.'],
        ['preview', 'bool', 'true', 'Klik gambar untuk melihatnya besar. Isi false kalau tidak perlu.'],
    ];

    $fileProps = [
        ['name', 'Closure($row)', '—', 'Nama berkas yang tampil, mis. "Document.pdf". Kalau kosong, sel berisi "—".'],
        ['href', 'Closure($row)', 'null', 'Alamat berkas. Tanpa ini nama tampil sebagai teks biasa.'],
        ['type', '"image" | "pdf" | "file" | Closure($row)', 'dari ekstensi', 'Jenis berkas, menentukan ikon dan pratinjau. Bawaannya ditebak dari ekstensi nama.'],
        ['preview', 'bool', 'true', 'Klik untuk pratinjau gambar/PDF di atas halaman. false membuka tab baru. Jenis file selalu diunduh.'],
    ];

    $filterProps = [
        ['label', 'string', 'Filter Data', 'Teks tombol.'],
        ['title', 'string', 'Tampilkan kolom', 'Judul di atas daftar kolom.'],
        ['default-hidden-columns', 'list<string>', '[]', 'key kolom yang disembunyikan saat tabel pertama tampil.'],
        ['wire:hidden-columns-change', 'event', '—', 'Dipicu setiap kali pilihan kolom berubah, dengan $event.detail.keys. Pasang di tag Table.'],
    ];

    $paginationProps = [
        ['page', 'int', '1', 'Halaman yang sedang tampil, biasanya properti Livewire.'],
        ['page-size', 'int', '10', 'Jumlah baris per halaman.'],
        ['page-size-options', 'list<int>', 'null', 'Pilihan jumlah baris, mis. [5, 10, 25, 50, 100]. Kalau diisi, muncul pilihan "Baris per halaman" di samping pagination.'],
        ['wire:page-size-change', 'event', '—', 'Dipicu saat user mengganti jumlah baris, dengan $event.detail.pageSize, lalu page-change ke halaman 1. Pasang di tag Table.'],
        ['total', 'int', 'jumlah data', 'Jumlah seluruh data. Wajib diisi kalau memakai manual tanpa paginator.'],
        ['theme', 'PaginationTheme', 'primary', 'Warna tombol halaman yang aktif.'],
        ['summary', 'Closure($from, $to, $total)', 'Menampilkan …', 'Ganti tulisan "Menampilkan 1–10 dari 50 data".'],
    ];

    $hl = 'text-primary-500';
    $points = 'mb-4 list-disc space-y-1 pl-5 text-body-sm text-gray-500';
    $box = 'rounded-lg border border-gray-200 bg-white p-4 text-body-sm text-gray-500';
@endphp

<x-docs.layout title="Table">
    <x-docs.page
        eyebrow="Components · Table"
        title="Table"
        description="Tabel data dengan toolbar pencarian, filter, aksi, pengurutan kolom, seleksi baris, dan pagination."
        :toc="[
            'table' => 'Table',
            'livewire' => 'Livewire, Alpine, dan Blaze',
            'states' => 'States',
            'size' => 'Size',
            'zebra' => 'Baris zebra',
            'aksi' => 'Tombol aksi',
            'posisi' => 'Posisi judul',
            'gambar' => 'Gambar',
            'berkas' => 'Berkas (PDF)',
            'bertingkat' => 'Kolom bertingkat',
            'sort' => 'Sorting',
            'filter' => 'Filter kolom',
            'scroll' => 'Scroll',
            'otomatis' => 'Kolom otomatis',
            'server' => 'Data server',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="table" title="Table">
            <x-docs.lead>
                Table butuh dua hal: <span class="{{ $hl }}">data</span> (isi tabel) dan <span class="{{ $hl }}">columns</span> (kolom apa saja
                yang tampil). Sisanya bisa dinyalakan sesuai kebutuhan:
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">search</span> menampilkan kotak pencarian di kiri atas.</li>
                <li><span class="{{ $hl }}">filter</span> menampilkan tombol "Filter Data" untuk memilih kolom yang tampil.</li>
                <li>Slot <span class="{{ $hl }}">actions</span> diisi tombol di kanan atas, mis. "Tambah Data".</li>
                <li><span class="{{ $hl }}">selectable</span> menambahkan checkbox di tiap baris, plus checkbox "pilih semua" di judul untuk baris di halaman yang tampil.</li>
                <li><span class="{{ $hl }}">pagination</span> membagi data per halaman. Isi <span class="{{ $hl }}">page-size-options</span> supaya user bisa memilih jumlah baris.</li>
                <li>Klik judul kolom yang <span class="{{ $hl }}">sortable</span> untuk mengurutkan.</li>
            </ul>
            <x-docs.lead>
                Bagian-bagiannya memakai komponen yang sudah ada:
                <a class="{{ $hl }}" href="{{ route('components.dropdown') }}">Dropdown</a>,
                <a class="{{ $hl }}" href="{{ route('components.button') }}">Button</a>,
                <a class="{{ $hl }}" href="{{ route('form.checkbox') }}">Checkbox</a>, dan
                <a class="{{ $hl }}" href="{{ route('components.pagination') }}">Pagination</a>.
            </x-docs.lead>

            <div class="mb-4">
                <livewire:playground.table-demo example="data" />
            </div>

            <x-docs.code>
                @verbatim
                @php
                    $columns = [
                        ['key' => 'field1', 'header' => 'Field 1', 'sortable' => true, 'emphasis' => true],
                        ['key' => 'status', 'header' => 'Field 4', 'cell' => fn ($row) => view('badge-status', ['row' => $row])],
                    ];
                @endphp

                <x-ceplok::table
                    :columns="$columns"
                    :data="$rows"
                    row-key="id"
                    selectable
                    wire:model.live="selected"
                    :search="['wire:model.live.debounce.300ms' => 'q']"
                    :filter="[]"
                    :sort="$sort"
                    wire:sort-change="$set('sort', $event.detail.sort)"
                    :pagination="['page' => $page, 'page-size-options' => [5, 10, 25, 50, 100]]"
                    wire:page-change="$set('page', $event.detail.page)"
                >
                    <x-slot:actions>
                        <x-ceplok::button size="s" :left-icon="svg('ceplok-o-plus')">Tambah Data</x-ceplok::button>
                    </x-slot:actions>
                </x-ceplok::table>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="livewire" title="Livewire, Alpine, dan Blaze">
            <x-docs.lead>
                Di kit React, Table menyimpan urutan, halaman, dan pilihan baris di state-nya sendiri. Di kit ini
                pekerjaannya dibagi tiga, supaya data tetap di server dan tabel tetap terasa cepat:
            </x-docs.lead>
            <div class="mb-6 grid gap-4 md:grid-cols-3">
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Livewire: data</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Menyimpan <span class="{{ $hl }}">$sort</span>, <span class="{{ $hl }}">$page</span>, dan kata pencarian sebagai properti.</li>
                        <li>Mengambil baris dari database, lalu merender ulang tabelnya.</li>
                        <li>Menangkap event <span class="{{ $hl }}">sort-change</span> dan <span class="{{ $hl }}">page-change</span> dengan <span class="{{ $hl }}">wire:</span>.</li>
                    </ul>
                </div>
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Alpine: tampilan</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Centang baris dan "pilih semua", langsung tanpa menunggu server.</li>
                        <li>Kolom yang disembunyikan lewat Filter Data.</li>
                        <li>Pratinjau gambar dan PDF, serta panel Dropdown.</li>
                    </ul>
                </div>
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Blaze: render</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Mengompilasi komponen kit menjadi fungsi PHP biasa.</li>
                        <li>Tabel 10 baris memanggil puluhan Checkbox, Badge, dan ikon; Blaze membuatnya tetap cepat.</li>
                        <li>Sudah aktif untuk semua komponen kit. Tidak perlu dipasang sendiri.</li>
                    </ul>
                </div>
            </div>
            <x-docs.lead>
                Jadi yang Anda tulis hanya komponen Livewire: properti untuk urutan dan halaman, lalu
                <span class="{{ $hl }}">wire:sort-change</span> dan <span class="{{ $hl }}">wire:page-change</span> di tag Table. Tanpa
                Livewire, centang baris dan filter kolom tetap jalan, tapi judul kolom dan tombol halaman hanya
                mengirim event yang perlu Anda tangani sendiri.
            </x-docs.lead>
            <x-docs.code>
                @verbatim
                {{-- Komponen Livewire --}}
                public ?array $sort = null;   // ['key' => 'nama', 'direction' => 'asc']
                public int $page = 1;
                public array $selected = [];

                public function urutkan(?array $sort): void
                {
                    $this->sort = $sort;
                    $this->page = 1;
                }

                {{-- View-nya --}}
                <x-ceplok::table :columns="$columns" :data="$rows" row-key="id" selectable
                    :sort="$sort"
                    :pagination="['page' => $page]"
                    ⟦wire:model.live⟧="selected"
                    ⟦wire:sort-change⟧="urutkan($event.detail.sort)"
                    ⟦wire:page-change⟧="$set('page', $event.detail.page)"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="states" title="States">
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">loading</span>: pasang saat data sedang diambil. Isi tabel diganti ikon memuat.</li>
                <li><span class="{{ $hl }}">empty-text</span>: tulisan yang tampil kalau data kosong. Bawaannya "Tidak ada data".</li>
            </ul>

            <div class="mb-4">
                <livewire:playground.table-demo example="states" />
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::table :columns="$columns" :data="$rows" row-key="id" ⟦loading⟧ />
                <x-ceplok::table :columns="$columns" :data="[]" row-key="id" ⟦empty-text⟧="Belum ada pengajuan" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="size" title="Size">
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">normal</span> (bawaan): header abu-abu dengan huruf kapital, dan ada garis di antara baris.</li>
                <li><span class="{{ $hl }}">compact</span>: tampilan lebih bersih. Header putih tanpa huruf kapital, garis antarbaris tetap ada, teks lebih gelap, dan gambar kecil berbentuk kotak tanpa teks di bawahnya.</li>
            </ul>

            <div class="mb-4 grid gap-5">
                <x-docs.demo label="Normal">
                    <livewire:playground.table-demo example="normal" />
                </x-docs.demo>
                <x-docs.demo label="Compact">
                    <livewire:playground.table-demo example="compact" />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::table :columns="$columns" :data="$rows" row-key="id" ⟦size⟧="compact" />

                {{-- Gambar di compact otomatis kotak 32px, caption hanya tampil di pratinjau --}}
                ['key' => 'gambar', 'header' => 'Gambar', 'image' => ['src' => fn ($row) => $row->foto_url]]
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="zebra" title="Baris zebra">
            <x-docs.lead>
                Isi <span class="{{ $hl }}">striped</span> supaya baris genap berlatar abu-abu muda. Cocok untuk tabel dengan
                banyak kolom, karena mata lebih mudah mengikuti satu baris dari kiri ke kanan. Warna hover
                dibuat sedikit lebih gelap supaya tetap terlihat di baris genap.
            </x-docs.lead>

            <x-docs.demo label="Normal" class="mb-4">
                <livewire:playground.table-demo example="zebra" />
            </x-docs.demo>

            <x-docs.code>
                @verbatim
                <x-ceplok::table :columns="$columns" :data="$rows" row-key="id" ⟦striped⟧ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="aksi" title="Tombol aksi">
            <x-docs.lead>
                Untuk tombol seperti Ubah, Unduh, atau Hapus di tiap baris, buat satu kolom lalu isi
                <span class="{{ $hl }}">actions</span>. Setiap tombol cukup diisi:
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">icon</span>: ikon tombolnya, mis. <span class="{{ $hl }}">'ceplok-s-edit'</span>.</li>
                <li><span class="{{ $hl }}">label</span>: judul tombol. Muncul sebagai tooltip saat kursor diarahkan ke tombol.</li>
                <li><span class="{{ $hl }}">href</span> untuk pindah halaman, <b>atau</b> <span class="{{ $hl }}">attributes</span> untuk menjalankan aksi, mis. <span class="{{ $hl }}">['wire:click' => …]</span>.</li>
                <li><span class="{{ $hl }}">theme</span> (opsional): warna tombol, mis. <span class="{{ $hl }}">"yellow"</span> atau <span class="{{ $hl }}">"red"</span>.</li>
            </ul>
            <x-docs.lead>Opsi tambahan kalau dibutuhkan:</x-docs.lead>
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">show-label</span>: judul ditulis di samping ikon, bukan hanya tooltip.</li>
                <li><span class="{{ $hl }}">disabled</span>: tombol tampil tapi tidak bisa diklik.</li>
                <li><span class="{{ $hl }}">hidden</span>: tombol tidak tampil sama sekali.</li>
            </ul>
            <x-docs.lead>
                Semua isian di atas boleh berupa closure <span class="{{ $hl }}">fn ($row) => …</span> kalau nilainya
                berbeda per baris. Di contoh ini tombol Hapus di baris pertama dinonaktifkan.
            </x-docs.lead>

            <div class="mb-4">
                <livewire:playground.table-demo example="actions" />
            </div>

            <x-docs.code>
                @verbatim
                [
                    'key' => 'aksi',
                    'header' => 'Aksi',
                    ⟦'actions'⟧ => [
                        ['key' => 'detail', 'icon' => 'ceplok-s-eye', 'label' => 'Lihat detail', 'show-label' => true,
                            ⟦'href'⟧ => fn ($row) => route('penduduk.show', $row)],
                        ['key' => 'hapus', 'icon' => 'ceplok-s-trash-bin', 'label' => 'Hapus', 'theme' => 'red',
                            ⟦'attributes'⟧ => fn ($row) => ['wire:click' => "hapus({$row->id})"],
                            ⟦'disabled'⟧ => fn ($row) => $row->id === 1],
                    ],
                ]
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="posisi" title="Posisi judul">
            <x-docs.lead>
                Judul kolom bisa diletakkan di kiri, tengah, atau kanan lewat <span class="{{ $hl }}">header-align</span>.
                Bawaannya rata kiri.
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">header-align</span>: posisi judul kolom, <span class="{{ $hl }}">"left"</span>, <span class="{{ $hl }}">"center"</span>, atau <span class="{{ $hl }}">"right"</span>.</li>
                <li><span class="{{ $hl }}">align</span>: posisi isi sel. Kalau <span class="{{ $hl }}">header-align</span> tidak diisi, judul ikut <span class="{{ $hl }}">align</span>.</li>
                <li>Kalau dua-duanya tidak diisi, judul rata kiri dan isi sel rata tengah.</li>
                <li>Judul induk di kolom bertingkat bawaannya di tengah, karena membawahi beberapa kolom. Bisa diubah dengan <span class="{{ $hl }}">header-align</span> juga.</li>
            </ul>

            <div class="mb-4">
                <livewire:playground.table-demo example="align" />
            </div>

            <x-docs.code>
                @verbatim
                ['key' => 'nama', 'header' => 'Kiri (bawaan)', 'align' => 'left'],
                ['key' => 'tempatLahir', 'header' => 'Tengah', ⟦'header-align'⟧ => 'center'],
                ['key' => 'telepon', 'header' => 'Kanan', ⟦'align'⟧ => 'right'], // judul ikut align
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="gambar" title="Gambar">
            <x-docs.lead>
                Untuk menampilkan gambar di sel, isi <span class="{{ $hl }}">image</span> pada kolom. Tidak perlu menulis
                <span class="{{ $hl }}">&lt;img&gt;</span> sendiri. Isiannya:
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">src</span>: alamat gambar dari tiap baris, mis. <span class="{{ $hl }}">fn ($row) => $row->foto_url</span>.</li>
                <li><span class="{{ $hl }}">alt</span> (opsional): keterangan gambar untuk pembaca layar.</li>
                <li><span class="{{ $hl }}">caption</span> (opsional): teks di bawah gambar, mis. nama file. Di size <span class="{{ $hl }}">compact</span> teks ini tidak tampil di tabel, hanya saat pratinjau dibuka.</li>
                <li><span class="{{ $hl }}">preview</span> (opsional): bawaannya <span class="{{ $hl }}">true</span>. Klik gambar untuk melihatnya besar di atas latar gelap. Tutup dengan tombol X, tombol Esc, atau klik di luar gambar.</li>
                <li>Isi <span class="{{ $hl }}">'preview' => false</span> kalau gambarnya cukup tampil kecil dan tidak perlu bisa diklik.</li>
            </ul>
            <x-docs.lead>Klik gambar di kolom "Dengan pratinjau" untuk mencobanya.</x-docs.lead>

            <div class="mb-4">
                <livewire:playground.table-demo example="image" />
            </div>

            <x-docs.code>
                @verbatim
                [
                    'key' => 'foto',
                    'header' => 'Foto',
                    ⟦'image'⟧ => [
                        'src' => fn ($row) => $row->foto_url,
                        'alt' => fn ($row) => $row->nama,
                        'caption' => fn ($row) => $row->nama_file,
                        ⟦'preview'⟧ => false, // hapus baris ini kalau ingin bisa diklik
                    ],
                ]
                @endverbatim
            </x-docs.code>

            <x-docs.lead class="mt-6">
                Kalau isi sel perlu disusun sendiri dengan <span class="{{ $hl }}">cell</span>, pakai komponen
                <span class="{{ $hl }}">table-image</span> supaya pratinjaunya tetap ada:
            </x-docs.lead>

            <x-docs.code>
                @verbatim
                {{-- resources/views/pengajuan/foto.blade.php --}}
                <div class="flex items-center gap-2">
                    <⟦x-ceplok::table-image⟧ :src="$row->foto_url" :alt="$row->nama" />
                    {{ $row->nama }}
                </div>

                {{-- Kolomnya --}}
                ['key' => 'foto', 'header' => 'Foto', 'cell' => fn ($row) => view('pengajuan.foto', ['row' => $row])]
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="berkas" title="Berkas (PDF dan lainnya)">
            <x-docs.lead>
                Untuk kolom dokumen, isi <span class="{{ $hl }}">file</span>. Sel menampilkan ikon jenis berkas dan namanya.
                Ikonnya ditebak dari ekstensi nama berkas:
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li>Gambar (<span class="{{ $hl }}">.png</span>, <span class="{{ $hl }}">.jpg</span>, …): ikon biru, klik untuk melihat gambarnya besar.</li>
                <li>PDF: ikon merah, klik untuk membaca PDF-nya langsung di atas halaman.</li>
                <li>Berkas lain (<span class="{{ $hl }}">.docx</span>, <span class="{{ $hl }}">.xlsx</span>, …): ikon abu-abu, klik untuk mengunduh.</li>
            </ul>

            <x-docs.demo label="Kolom file" class="mb-4">
                <livewire:playground.table-demo example="files" />
            </x-docs.demo>

            <x-docs.code>
                @verbatim
                ['key' => 'dokumen', 'header' => 'Dokumen',
                    ⟦'file'⟧ => ['name' => fn ($row) => $row->nama_berkas, 'href' => fn ($row) => $row->url_berkas]]

                {{-- Di dalam cell sendiri --}}
                <⟦x-ceplok::table-file⟧ name="Document.pdf" href="/berkas/dokumen.pdf" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="bertingkat" title="Kolom bertingkat">
            <x-docs.lead>
                Satu judul bisa membawahi beberapa kolom, mis. judul "Nama" di atas "Nama Awal" dan
                "Nama Akhir". Caranya, isi <span class="{{ $hl }}">children</span> pada kolom induk:
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li>Kolom induk cukup diisi <span class="{{ $hl }}">key</span>, <span class="{{ $hl }}">header</span>, dan <span class="{{ $hl }}">children</span>. Kolom induk tidak punya isi sendiri.</li>
                <li>Kolom anak ditulis seperti kolom biasa: bisa <span class="{{ $hl }}">sortable</span>, <span class="{{ $hl }}">cell</span>, <span class="{{ $hl }}">actions</span>, <span class="{{ $hl }}">image</span>, dan lainnya.</li>
                <li>Kolom tanpa <span class="{{ $hl }}">children</span> otomatis setinggi seluruh header, seperti "Email" di contoh ini.</li>
                <li>Di Filter Data, grup tampil sebagai satu pilihan. Menyembunyikannya berarti seluruh anaknya ikut tersembunyi.</li>
            </ul>

            <div class="mb-4">
                <livewire:playground.table-demo example="group" />
            </div>

            <x-docs.code>
                @verbatim
                $columns = [
                    [
                        'key' => 'nama',
                        'header' => 'Nama',
                        ⟦'children'⟧ => [
                            ['key' => 'namaAwal', 'header' => 'Nama Awal', 'sortable' => true],
                            ['key' => 'namaAkhir', 'header' => 'Nama Akhir', 'sortable' => true],
                        ],
                    ],
                    [
                        'key' => 'kelahiran',
                        'header' => 'Kelahiran',
                        ⟦'children'⟧ => [
                            ['key' => 'tempatLahir', 'header' => 'Tempat'],
                            ['key' => 'tanggalLahir', 'header' => 'Tanggal'],
                        ],
                    ],
                    ['key' => 'email', 'header' => 'Email'],
                ];
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="sort" title="Sorting">
            <x-docs.lead>
                Kolom hanya bisa diurutkan kalau diberi <span class="{{ $hl }}">'sortable' => true</span>. Kolom tanpa itu tidak
                bisa diklik. Bawaannya, klik judul kolom berulang kali akan mengurutkan:
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li>A ke Z (naik), lalu Z ke A (turun), lalu kembali ke urutan awal.</li>
            </ul>
            <x-docs.lead>Kalau perlu cara lain, atur per kolom:</x-docs.lead>
            <ul class="{{ $points }}">
                <li><span class="{{ $hl }}">sort-directions</span>: urutan klik. Isi <span class="{{ $hl }}">['desc', 'asc']</span> supaya klik pertama langsung dari yang terbaru atau terbesar.</li>
                <li><span class="{{ $hl }}">'sort-resettable' => false</span>: tidak kembali ke urutan awal, hanya bolak-balik naik dan turun.</li>
                <li><span class="{{ $hl }}">sort-value</span>: nilai yang dipakai untuk mengurutkan, mis. urutkan tanggal berdasarkan angka, bukan teksnya.</li>
                <li><span class="{{ $hl }}">sort-fn</span>: fungsi pembanding sendiri kalau aturannya lebih rumit.</li>
                <li><span class="{{ $hl }}">sort-icon</span>: ganti ikon panah di judul kolom.</li>
            </ul>
            <x-docs.lead>Pilih nilai urut sesuai jenis datanya:</x-docs.lead>
            <ul class="{{ $points }}">
                <li><b>Teks biasa</b> (nama, layanan): tidak perlu <span class="{{ $hl }}">sort-value</span>. Langsung urut A-Z / Z-A.</li>
                <li><b>Tanggal</b>: isi <span class="{{ $hl }}">sort-value</span> dengan waktunya, mis. <span class="{{ $hl }}">$row->tanggal->timestamp</span>. Teks seperti "02 Sep 2026" kalau diurutkan sebagai teks hasilnya salah.</li>
                <li><b>ID atau angka</b>: isi <span class="{{ $hl }}">sort-value</span> dengan angkanya, supaya 9 jatuh sebelum 10.</li>
            </ul>
            <x-docs.lead>
                Urutan yang sedang aktif datang dari prop <span class="{{ $hl }}">sort</span>. Klik judul kolom mengirim
                event <span class="{{ $hl }}">sort-change</span> berisi urutan berikutnya, lalu Livewire menyimpannya dan
                merender ulang. Tanpa <span class="{{ $hl }}">manual</span>, Table mengurutkan <span class="{{ $hl }}">data</span> sendiri sesuai
                <span class="{{ $hl }}">sort</span> itu.
            </x-docs.lead>
            <x-docs.lead>
                Di contoh ini: Nama memakai cara bawaan, Tanggal Lahir mulai dari yang terbaru dan tidak
                kembali ke urutan awal, Tempat Lahir memakai ikon sendiri, dan Email tidak bisa
                diurutkan.
            </x-docs.lead>

            <div class="mb-4">
                <livewire:playground.table-demo example="sort" />
            </div>

            <x-docs.code>
                @verbatim
                // Bawaan
                ['key' => 'nama', 'header' => 'Nama', ⟦'sortable'⟧ => true],

                // Mulai dari terbaru, tidak kembali ke urutan awal
                ['key' => 'tanggalLahir', 'header' => 'Tanggal Lahir', 'sortable' => true,
                    ⟦'sort-directions'⟧ => ['desc', 'asc'],
                    ⟦'sort-resettable'⟧ => false,
                    ⟦'sort-value'⟧ => fn ($row) => $row->tanggal_lahir->timestamp],

                // Tidak bisa diurutkan: cukup tanpa sortable
                ['key' => 'email', 'header' => 'Email'],
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="filter" title="Filter kolom">
            <x-docs.lead>
                Tombol <span class="{{ $hl }}">Filter Data</span> dipakai untuk memilih kolom mana saja yang ditampilkan.
                Klik tombolnya, lalu centang atau hapus centang kolom di daftar.
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li>Pasang <span class="{{ $hl }}">:filter="[]"</span> untuk menampilkan tombolnya. Semua kolom otomatis masuk daftar.</li>
                <li><span class="{{ $hl }}">default-hidden-columns</span>: kolom yang disembunyikan saat tabel pertama tampil. Isi dengan <span class="{{ $hl }}">key</span> kolomnya.</li>
                <li><span class="{{ $hl }}">'hideable' => false</span> di kolom: kolom itu selalu tampil dan tidak bisa dimatikan, mis. kolom aksi.</li>
                <li>Kolom terakhir yang masih tampil tidak bisa dimatikan, supaya tabel tidak kosong.</li>
                <li>Pilihan kolom diurus Alpine di browser, jadi langsung berubah tanpa request. Kalau perlu disimpan, tangkap <span class="{{ $hl }}">wire:hidden-columns-change</span> lalu kirim kembali sebagai <span class="{{ $hl }}">default-hidden-columns</span>.</li>
            </ul>
            <x-docs.lead>
                Di contoh ini kolom Payment Mode disembunyikan dari awal, dan kolom Action tidak bisa
                dimatikan. Klik Filter Data untuk mencobanya.
            </x-docs.lead>

            <div class="mb-4">
                <livewire:playground.table-demo example="filter" />
            </div>

            <x-docs.code>
                @verbatim
                $columns = [
                    // ...
                    ['key' => 'aksi', 'header' => 'Action', 'actions' => [...], ⟦'hideable'⟧ => false],
                ];

                <x-ceplok::table
                    :columns="$columns"
                    :data="$rows"
                    row-key="id"
                    :⟦filter⟧="['⟦default-hidden-columns⟧' => ['pembayaran']]"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="scroll" title="Scroll">
            <x-docs.lead>
                Kalau kolomnya banyak, tabel otomatis bisa digeser ke samping. Tidak perlu pengaturan
                apa pun.
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li>Teks di sel bawaannya tidak turun baris, supaya tabel melebar dan bisa digeser.</li>
                <li>Kalau kolom boleh turun baris, mis. alamat panjang, beri <span class="{{ $hl }}">'wrap' => true</span>.</li>
            </ul>
            <x-docs.lead>Geser tabel di bawah ke kanan untuk mencobanya.</x-docs.lead>

            <div class="mb-4">
                <livewire:playground.table-demo example="scroll" />
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::table
                    :columns="$columns"
                    :data="$rows"
                    row-key="id"
                    selectable
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="otomatis" title="Kolom otomatis">
            <x-docs.lead>
                Anda cukup kirim <span class="{{ $hl }}">data</span> tanpa <span class="{{ $hl }}">columns</span>, dan tabelnya langsung jadi.
                Table membaca baris pertama data Anda: setiap field jadi satu kolom, dan nama field
                jadi judul kolom.
            </x-docs.lead>

            <div class="mb-4 grid gap-4 md:grid-cols-2">
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Nama field → judul kolom</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li><span class="{{ $hl }}">namaLayanan</span> → "Nama layanan"</li>
                        <li><span class="{{ $hl }}">jenis_layanan</span> → "Jenis layanan"</li>
                        <li><span class="{{ $hl }}">estimasiHari</span> → "Estimasi hari"</li>
                    </ul>
                </div>
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Nilai → isi sel (apa adanya)</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Teks dan angka → tampil biasa</li>
                        <li><span class="{{ $hl }}">true</span> / <span class="{{ $hl }}">false</span> → tulisan "true" / "false"</li>
                        <li>Kosong atau <span class="{{ $hl }}">null</span> → "—"</li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <x-ceplok::table :data="$autoRows" row-key="id" size="compact" />
            </div>

            <x-docs.code>
                @verbatim
                $data = [
                    ['id' => 1, 'namaLayanan' => 'Pembuatan KTP', 'jenis_layanan' => 'Kependudukan', 'estimasiHari' => 3, 'aktif' => true],
                ];

                {{-- Tanpa columns — tabel di atas jadi dari sini saja --}}
                <x-ceplok::table :⟦data⟧="$data" row-key="id" />
                @endverbatim
            </x-docs.code>

            <div class="mt-6 mb-4 grid gap-4 md:grid-cols-2">
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Cocok untuk</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Mengecek isi respons API dengan cepat</li>
                        <li>Halaman admin atau debug internal</li>
                        <li>Prototipe sebelum desain tabelnya final</li>
                    </ul>
                </div>
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Jangan untuk halaman yang dilihat user, karena</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Semua field ikut tampil, termasuk <span class="{{ $hl }}">id</span> atau field internal</li>
                        <li>Tidak bisa diurutkan dan tidak ada tombol aksi</li>
                        <li>Judul dan isi tidak bisa diubah, mis. "true" tidak bisa jadi Badge "Aktif"</li>
                        <li>Hanya field di baris pertama yang jadi kolom</li>
                    </ul>
                </div>
            </div>

            <x-docs.lead>
                Untuk halaman yang dilihat user, tulis <span class="{{ $hl }}">columns</span> sendiri. Pilih field yang mau
                ditampilkan, beri judul, dan atur isinya:
            </x-docs.lead>

            <x-docs.code>
                @verbatim
                $columns = [
                    ['key' => 'namaLayanan', 'header' => 'Nama Layanan', 'sortable' => true],
                    ['key' => 'estimasiHari', 'header' => 'Estimasi', ⟦'cell'⟧ => fn ($row) => "{$row['estimasiHari']} hari"],
                    ['key' => 'aktif', 'header' => 'Status', ⟦'cell'⟧ => fn ($row) => view('layanan.status', ['aktif' => $row['aktif']])],
                ];

                <x-ceplok::table :⟦columns⟧="$columns" :data="$data" row-key="id" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="server" title="Data server">
            <x-docs.lead>
                Ada dua cara memberi data ke Table. Bedanya ada di siapa yang mengurutkan dan
                membagi data per halaman: Table sendiri, atau database.
            </x-docs.lead>
            <ul class="{{ $points }}">
                <li><b>Bawaan:</b> kirim <b>semua</b> data sekaligus. Table yang mengurutkan dan membagi per halaman sesuai <span class="{{ $hl }}">sort</span> dan <span class="{{ $hl }}">pagination.page</span>.</li>
                <li><b>Paginator atau <span class="{{ $hl }}">manual</span>:</b> kirim <b>satu halaman</b> saja. Database yang mengurutkan, mencari, dan membagi. Table hanya menampilkan.</li>
            </ul>

            <div class="mb-6 grid gap-4 sm:grid-cols-2">
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Pakai bawaan bila</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Datanya kurang dari sekitar 1.000 baris.</li>
                        <li>Datanya sudah berupa array kecil, mis. dari config atau satu kali query.</li>
                    </ul>
                </div>
                <div class="{{ $box }}">
                    <p class="mb-2 font-semibold text-gray-900">Pakai paginator bila</p>
                    <ul class="list-disc space-y-1 pl-5">
                        <li>Datanya ribuan baris atau lebih.</li>
                        <li>Datanya dari Eloquent: cukup <span class="{{ $hl }}">->paginate(10)</span>.</li>
                        <li>Data berubah terus, jadi harus selalu diambil yang terbaru.</li>
                    </ul>
                </div>
            </div>

            <p class="mb-2 text-body-sm font-semibold text-gray-900">Yang perlu diperhatikan</p>
            <ul class="{{ $points }}">
                <li>Isi <span class="{{ $hl }}">data</span> dengan <span class="{{ $hl }}">LengthAwarePaginator</span>. Halaman, jumlah per halaman, dan total ikut terbaca, dan <span class="{{ $hl }}">manual</span> menyala sendiri.</li>
                <li>Tanpa paginator, pasang <span class="{{ $hl }}">manual</span> dan isi <span class="{{ $hl }}">pagination.total</span>. Tanpa total jumlah halamannya salah.</li>
                <li>Kirim kata pencarian ke query juga. Table hanya memegang 10 baris, jadi mencari di sana hanya mencari 10 baris itu.</li>
                <li>Kembalikan ke halaman 1 setiap kali sort atau pencarian berubah.</li>
                <li>Tunda pencarian sekitar 300ms dengan <span class="{{ $hl }}">wire:model.live.debounce.300ms</span>, supaya tidak mengirim request di setiap huruf.</li>
                <li>Selama request berjalan, <span class="{{ $hl }}">wire:loading</span> bisa meredupkan tabel.</li>
            </ul>

            <x-docs.demo label="Coba sendiri: 1000 baris, satu halaman per request" class="mb-2">
                <livewire:playground.table-demo example="server" />
            </x-docs.demo>
            <p class="mb-6 text-body-sm text-gray-500">Klik halaman, judul kolom, atau ketik di pencarian, lalu lihat kotak hitam di bawah tabel.</p>

            <x-docs.code flush>
                @verbatim
                {{-- app/Livewire/PengajuanTable.php --}}
                class PengajuanTable extends Component
                {
                    use WithPagination;

                    public ?array $sort = null;
                    public string $q = '';

                    public function urutkan(?array $sort): void
                    {
                        $this->sort = $sort;
                        $this->resetPage();
                    }

                    public function updatedQ(): void
                    {
                        $this->resetPage();
                    }

                    public function render()
                    {
                        $rows = Pengajuan::query()
                            ->where('nama', 'like', "%{$this->q}%")
                            ->when($this->sort, fn ($query, $sort) => $query->orderBy($sort['key'], $sort['direction']))
                            ->⟦paginate⟧(10);

                        return view('livewire.pengajuan-table', ['rows' => $rows]);
                    }
                }

                {{-- resources/views/livewire/pengajuan-table.blade.php --}}
                <x-ceplok::table
                    :columns="$columns"
                    :⟦data⟧="$rows"
                    row-key="id"
                    :search="['wire:model.live.⟦debounce.300ms⟧' => 'q']"
                    :sort="$sort"
                    ⟦wire:sort-change⟧="urutkan($event.detail.sort)"
                    ⟦wire:page-change⟧="gotoPage($event.detail.page)"
                    wire:loading.class="opacity-60"
                />
                @endverbatim
            </x-docs.code>

            <div class="{{ $box }} mt-6">
                <p class="mb-2 font-semibold text-gray-900">Kesalahan yang sering terjadi</p>
                <ul class="list-disc space-y-1 pl-5">
                    <li>Memakai <span class="{{ $hl }}">manual</span> tanpa <span class="{{ $hl }}">total</span>: pagination hanya menampilkan 1 halaman.</li>
                    <li>Mengirim satu halaman tanpa <span class="{{ $hl }}">manual</span> atau paginator: Table mengurutkan ulang 10 baris yang sudah diurutkan, dan footer menulis "dari 10 data".</li>
                    <li>Mencari di array hasil query, bukan di query-nya: hasil pencarian hanya dari halaman yang sedang tampil.</li>
                    <li>Tidak kembali ke halaman 1 setelah mencari: tabel bisa tampil kosong karena halamannya sudah tidak ada.</li>
                    <li>Lupa <span class="{{ $hl }}">wire:sort-change</span> atau <span class="{{ $hl }}">wire:page-change</span>: judul kolom dan tombol halaman bisa diklik, tapi tabelnya tidak berubah.</li>
                </ul>
            </div>
        </x-docs.section>

        <livewire:playground.table />

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500">Referensi semua prop yang tersedia pada komponen Table.</p>
            <x-docs.props-table :rows="$tableProps" min-width="52rem" />

            <h3 class="mt-10 mb-4 text-base font-bold text-gray-900">Kolom (columns)</h3>
            <x-docs.props-table :rows="$columnProps" min-width="46rem" />

            <h3 class="mt-10 mb-4 text-base font-bold text-gray-900">Tombol aksi (actions)</h3>
            <x-docs.props-table :rows="$actionProps" min-width="46rem" />

            <h3 class="mt-10 mb-4 text-base font-bold text-gray-900">Gambar (image)</h3>
            <x-docs.props-table :rows="$imageProps" min-width="46rem" />

            <h3 class="mt-10 mb-4 text-base font-bold text-gray-900">Berkas (file)</h3>
            <x-docs.props-table :rows="$fileProps" min-width="46rem" />

            <h3 class="mt-10 mb-4 text-base font-bold text-gray-900">Filter Data (filter)</h3>
            <x-docs.props-table :rows="$filterProps" min-width="46rem" />

            <h3 class="mt-10 mb-4 text-base font-bold text-gray-900">Pagination (pagination)</h3>
            <x-docs.props-table :rows="$paginationProps" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
