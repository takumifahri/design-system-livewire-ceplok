<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Locked;
use Livewire\Component;

/*
 * Contoh-contoh di halaman Table, port contoh di TablePage docs React.
 * Satu komponen untuk semua contoh (`example`), karena urutan, halaman, dan
 * baris yang dicentang hidup di properti Livewire: Table hanya mengirim event
 * `sort-change` dan `page-change`, lalu komponen ini merender ulang tabelnya.
 *
 * Kolom berisi closure, jadi dibuat ulang di setiap render lewat method —
 * closure tidak bisa disimpan di properti publik Livewire.
 */
new class extends Component
{
    #[Locked]
    public string $example = 'data';

    /** @var array{key: string, direction: string}|null */
    public ?array $sort = null;

    public int $page = 1;

    public int $pageSize = 10;

    /** @var list<int> */
    public array $selected = [];

    public string $q = '';

    /** Baris yang sudah dihapus lewat tombol Hapus Data. @var list<int> */
    public array $deleted = [];

    public function mount(): void
    {
        $this->selected = $this->example === 'data' ? [1] : [];
        $this->pageSize = $this->example === 'dark' ? 4 : 10;
    }

    /** Urutan baru dari judul kolom: halaman kembali ke 1. */
    public function sortBy(?array $sort): void
    {
        $this->sort = $sort;
        $this->page = 1;
    }

    public function updatedQ(): void
    {
        $this->page = 1;
    }

    public function updatedPageSize(): void
    {
        $this->page = 1;
    }

    public function deleteSelected(): void
    {
        $this->deleted = array_values(array_unique([...$this->deleted, ...$this->selected]));
        $this->selected = [];
    }

    /** Gambar contoh bergantian 1.png sampai 5.png, sesuai id baris. */
    public static function image(int $id): string
    {
        return asset('images/Placeholder/'.((($id - 1) % 5) + 1).'.png');
    }

    /** Aksi per baris: Ubah, Unduh, dan Cetak. @return list<array<string, mixed>> */
    public static function rowActions(): array
    {
        return [
            ['key' => 'ubah', 'icon' => 'ceplok-s-edit', 'label' => 'Ubah', 'theme' => 'yellow'],
            ['key' => 'unduh', 'icon' => 'ceplok-s-download', 'label' => 'Unduh'],
            ['key' => 'cetak', 'icon' => 'ceplok-s-printer', 'label' => 'Cetak', 'theme' => 'green', 'attributes' => ['x-on:click' => 'window.print()']],
        ];
    }

    /** 1000 baris "Record n" dengan gambar dan status. @return list<array<string, mixed>> */
    public static function records(): array
    {
        $statuses = ['success', 'success', 'danger', 'brand', 'success', 'warning'];

        return array_map(fn (int $i) => [
            'id' => $i + 1,
            'field1' => 'Record '.($i + 1),
            'field2' => 'Record '.($i + 1),
            'file' => 'Image '.(($i % 5) + 1).'.png',
            'status' => $statuses[$i % count($statuses)],
        ], range(0, 999));
    }

    /** Lima penduduk untuk contoh kolom. @return list<array<string, string|int>> */
    public static function penduduk(): array
    {
        $names = ['Andi Saputra', 'Budi Santoso', 'Citra Lestari', 'Dewi Anggraini', 'Eko Prasetyo'];

        return array_map(fn (int $i) => [
            'id' => $i + 1,
            'nama' => $names[$i],
            'namaAwal' => explode(' ', $names[$i])[0],
            'namaAkhir' => explode(' ', $names[$i])[1],
            'tempatLahir' => ['Bandung', 'Bogor', 'Cimahi', 'Garut', 'Depok'][$i],
            'tanggalLahir' => (10 + $i).' Maret 199'.$i,
            'alamat' => 'Jl. Merdeka No. '.(12 + $i),
            'kecamatan' => 'Sumur Bandung',
            'kota' => 'Kota Bandung',
            'provinsi' => 'Jawa Barat',
            'telepon' => '0812-3456-78'.(10 + $i),
            'email' => 'warga'.($i + 1).'@contoh.go.id',
        ], range(0, 4));
    }

    /** 1000 pesanan untuk size compact. @return list<array<string, mixed>> */
    public static function orders(): array
    {
        $statuses = ['Delivered', 'Delivered', 'Process', 'Process', 'Canceled'];

        return array_map(fn (int $i) => [
            'id' => $i + 1,
            'kode' => '#'.(10000 + (($i * 7919) % 90000)),
            'customer' => 'Arandhana Rafli',
            'tanggal' => sprintf('%02d/%02d/2022', ($i % 28) + 1, ($i % 12) + 1),
            'jumlah' => 8000000,
            'pembayaran' => $i % 3 === 1 ? 'Cash on Delivery' : 'Tranfer Bank',
            'status' => $statuses[$i % count($statuses)],
        ], range(0, 999));
    }

    /** Badge kit untuk isi sel `cell`. */
    public static function badge(string $variant, string $label): HtmlString
    {
        return new HtmlString(Blade::render('<x-ceplok::badge :variant="$variant">{{ $label }}</x-ceplok::badge>', compact('variant', 'label')));
    }

    /** "10 Maret 1990" → detik, untuk mengurutkan tanggal sebagai waktu, bukan teks. */
    public static function waktuTanggal(string $text): int
    {
        $bulan = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
        [$hari, $namaBulan, $tahun] = explode(' ', $text);

        return gmmktime(0, 0, 0, array_search(strtolower($namaBulan), $bulan, true) + 1, (int) $hari, (int) $tahun);
    }

    /** @return list<array<string, mixed>> */
    public function columns(): array
    {
        $records = [
            ['key' => 'field1', 'header' => 'Field 1', 'sortable' => true, 'sort-resettable' => false, 'emphasis' => true],
            ['key' => 'field2', 'header' => 'Field 2', 'sortable' => true, 'sort-resettable' => false],
            ['key' => 'file', 'header' => 'Field 3', 'image' => [
                'src' => fn ($row) => self::image($row['id']),
                'alt' => fn ($row) => $row['file'],
                'caption' => fn ($row) => $row['file'],
            ]],
            ['key' => 'status', 'header' => 'Field 4', 'cell' => fn ($row) => self::badge($row['status'], $row['field1'])],
            ['key' => 'aksi', 'header' => 'Field 5', 'actions' => self::rowActions(), 'hideable' => false],
        ];
        $orderBadge = ['Delivered' => 'success', 'Process' => 'warning', 'Canceled' => 'danger'];

        return match ($this->example) {
            'states' => array_slice($records, 0, 3),
            'compact', 'filter' => [
                ['key' => 'kode', 'header' => 'ID', 'sortable' => true, 'sort-resettable' => false, 'sort-value' => fn ($row) => (int) substr($row['kode'], 1)],
                ['key' => 'gambar', 'header' => 'Gambar', 'align' => 'left', 'image' => ['src' => fn ($row) => self::image($row['id']), 'alt' => fn ($row) => 'Produk '.$row['kode']]],
                ['key' => 'customer', 'header' => 'Customer', 'align' => 'left', 'sortable' => true, 'sort-resettable' => false],
                ['key' => 'tanggal', 'header' => 'Date', 'align' => 'left', 'sortable' => true, 'sort-resettable' => false, 'sort-value' => function ($row) {
                    [$d, $m, $y] = array_map('intval', explode('/', $row['tanggal']));

                    return gmmktime(0, 0, 0, $m, $d, $y);
                }],
                ['key' => 'jumlah', 'header' => 'Amount', 'align' => 'left', 'cell' => fn ($row) => 'Rp.'.number_format($row['jumlah'])],
                ['key' => 'pembayaran', 'header' => 'Payment Mode', 'align' => 'left'],
                ['key' => 'status', 'header' => 'Status', 'align' => 'left', 'cell' => fn ($row) => self::badge($orderBadge[$row['status']], $row['status'])],
                ['key' => 'aksi', 'header' => 'Action', 'actions' => self::rowActions(), 'hideable' => false],
            ],
            'actions' => [
                ['key' => 'nama', 'header' => 'Nama', 'emphasis' => true, 'align' => 'left'],
                ['key' => 'email', 'header' => 'Email', 'align' => 'left'],
                ['key' => 'aksi', 'header' => 'Aksi', 'actions' => [
                    ['key' => 'detail', 'icon' => 'ceplok-s-eye', 'label' => 'Lihat detail', 'show-label' => true, 'variant' => 'outline'],
                    ['key' => 'hapus', 'icon' => 'ceplok-s-trash-bin', 'label' => 'Hapus', 'theme' => 'orange', 'disabled' => fn ($row) => $row['id'] === 1],
                ]],
            ],
            'align' => [
                ['key' => 'nama', 'header' => 'Kiri (bawaan)', 'emphasis' => true, 'align' => 'left'],
                ['key' => 'tempatLahir', 'header' => 'Tengah', 'header-align' => 'center'],
                ['key' => 'telepon', 'header' => 'Kanan', 'align' => 'right'],
            ],
            'image' => [
                ['key' => 'field1', 'header' => 'Nama', 'emphasis' => true],
                ['key' => 'file', 'header' => 'Dengan pratinjau', 'image' => [
                    'src' => fn ($row) => self::image($row['id']), 'alt' => fn ($row) => $row['file'], 'caption' => fn ($row) => $row['file'],
                ]],
                ['key' => 'tanpa', 'header' => 'Tanpa pratinjau', 'image' => [
                    'src' => fn ($row) => self::image($row['id']), 'alt' => fn ($row) => $row['file'], 'caption' => fn ($row) => $row['file'], 'preview' => false,
                ]],
            ],
            'files' => [
                ['key' => 'nama', 'header' => 'Nama', 'align' => 'left', 'emphasis' => true],
                ['key' => 'foto', 'header' => 'Foto', 'align' => 'left', 'file' => ['name' => fn ($row) => $row['foto'], 'href' => fn ($row) => self::image($row['id'])]],
                ['key' => 'dokumen', 'header' => 'Dokumen', 'align' => 'left', 'file' => ['name' => fn ($row) => $row['dokumen'], 'href' => fn ($row) => $row['dokumenUrl'] ?: null]],
            ],
            'group' => [
                ['key' => 'nama', 'header' => 'Nama', 'children' => [
                    ['key' => 'namaAwal', 'header' => 'Nama Awal', 'emphasis' => true, 'sortable' => true],
                    ['key' => 'namaAkhir', 'header' => 'Nama Akhir', 'emphasis' => true, 'sortable' => true],
                ]],
                ['key' => 'kelahiran', 'header' => 'Kelahiran', 'children' => [
                    ['key' => 'tempatLahir', 'header' => 'Tempat'],
                    ['key' => 'tanggalLahir', 'header' => 'Tanggal'],
                ]],
                ['key' => 'email', 'header' => 'Email'],
            ],
            'sort' => [
                // Bawaan: naik → turun → tanpa urutan.
                ['key' => 'nama', 'header' => 'Nama', 'emphasis' => true, 'align' => 'left', 'sortable' => true],
                // Terbaru dulu, dan tidak bisa kembali tanpa urutan.
                ['key' => 'tanggalLahir', 'header' => 'Tanggal Lahir', 'align' => 'left', 'sortable' => true, 'sort-directions' => ['desc', 'asc'], 'sort-resettable' => false, 'sort-value' => fn ($row) => self::waktuTanggal($row['tanggalLahir'])],
                // Ikon sort sendiri.
                ['key' => 'tempatLahir', 'header' => 'Tempat Lahir', 'align' => 'left', 'sortable' => true, 'sort-icon' => fn (?string $direction) => new HtmlString(
                    '<span aria-hidden="true" class="text-[10px]">'.match ($direction) { 'asc' => 'A→Z', 'desc' => 'Z→A', default => 'A↕Z' }.'</span>',
                )],
                // Tidak sortable.
                ['key' => 'email', 'header' => 'Email', 'align' => 'left'],
            ],
            'scroll' => [
                ...array_map(fn (array $c) => $c + ['align' => 'left'], [
                    ['key' => 'nama', 'header' => 'Nama', 'emphasis' => true],
                    ['key' => 'tempatLahir', 'header' => 'Tempat Lahir'],
                    ['key' => 'tanggalLahir', 'header' => 'Tanggal Lahir'],
                    ['key' => 'alamat', 'header' => 'Alamat'],
                    ['key' => 'kecamatan', 'header' => 'Kecamatan'],
                    ['key' => 'kota', 'header' => 'Kota'],
                    ['key' => 'provinsi', 'header' => 'Provinsi'],
                    ['key' => 'telepon', 'header' => 'Telepon'],
                    ['key' => 'email', 'header' => 'Email'],
                ]),
                ['key' => 'aksi', 'header' => 'Aksi', 'actions' => self::rowActions()],
            ],
            'server' => [
                ['key' => 'field1', 'header' => 'Field 1', 'sortable' => true, 'emphasis' => true, 'align' => 'left'],
                ['key' => 'field2', 'header' => 'Field 2', 'sortable' => true, 'align' => 'left'],
                ['key' => 'status', 'header' => 'Status', 'sortable' => true, 'cell' => fn ($row) => self::badge($row['status'], $row['status'])],
            ],
            default => $records,
        };
    }

    /** @return list<array<string, mixed>>|LengthAwarePaginator */
    public function rows(): array|LengthAwarePaginator
    {
        $q = mb_strtolower($this->q);

        return match ($this->example) {
            'states' => [],
            'compact', 'filter' => array_values(array_filter(self::orders(), fn ($row) => str_contains($row['kode'], $q) || str_contains(mb_strtolower($row['customer']), $q))),
            'zebra' => array_slice(self::records(), 0, 6),
            'dark' => array_values(array_filter(array_slice(self::records(), 0, 12), fn ($row) => str_contains(mb_strtolower($row['field1']), $q))),
            'normal', 'image' => array_slice(self::records(), 0, 3),
            'align' => array_slice(self::penduduk(), 0, 3),
            'actions', 'group', 'sort', 'scroll' => self::penduduk(),
            'files' => [
                ['id' => 1, 'nama' => 'Andi Pratama', 'foto' => 'Image.png', 'dokumen' => 'Document.pdf', 'dokumenUrl' => asset('files/contoh-dokumen.pdf')],
                ['id' => 2, 'nama' => 'Siti Rahma', 'foto' => 'Image.png', 'dokumen' => 'Document.pdf', 'dokumenUrl' => asset('files/contoh-dokumen.pdf')],
                ['id' => 3, 'nama' => 'Budi Santoso', 'foto' => 'Image.png', 'dokumen' => 'Document.pdf', 'dokumenUrl' => asset('files/contoh-dokumen.pdf')],
                // Tanpa alamat: hanya menunjukkan ikon berkas lain.
                ['id' => 4, 'nama' => 'Dewi Lestari', 'foto' => 'Image.png', 'dokumen' => 'Surat.docx', 'dokumenUrl' => ''],
            ],
            'server' => $this->serverPage(),
            default => array_values(array_filter(
                self::records(),
                fn ($row) => ! in_array($row['id'], $this->deleted, true) && str_contains(mb_strtolower($row['field1']), $q),
            )),
        };
    }

    /**
     * "Server" untuk contoh Data server: mencari, mengurutkan, lalu memotong
     * satu halaman — di aplikasi asli ini query Eloquent, mis.
     * Pengajuan::where(…)->orderBy(…)->paginate(10).
     */
    public function serverPage(): LengthAwarePaginator
    {
        $q = mb_strtolower($this->q);
        $found = collect(self::records())->filter(fn ($row) => str_contains(mb_strtolower($row['field1']), $q));

        if ($this->sort !== null) {
            $found = $found->sortBy(fn ($row) => $this->sort['key'] === 'status' ? $row['status'] : $row['id'], descending: $this->sort['direction'] === 'desc');
        }

        return new LengthAwarePaginator($found->forPage($this->page, 10)->values(), $found->count(), 10, $this->page);
    }

    /** Alamat request yang setara, ditampilkan di bawah contoh Data server. */
    public function requestUrl(): string
    {
        return 'GET /pengajuan?page='.$this->page
            .($this->sort ? '&sort='.$this->sort['key'].'&dir='.$this->sort['direction'] : '')
            .($this->q !== '' ? '&q='.rawurlencode($this->q) : '');
    }
};
?>

<div>
    @switch ($example)
        @case ('data')
            <x-ceplok::table
                :columns="$this->columns()"
                :data="$this->rows()"
                row-key="id"
                selectable
                :sort="$sort"
                :search="['wire:model.live.debounce.300ms' => 'q']"
                :filter="[]"
                :pagination="['page' => $page, 'page-size' => $pageSize, 'page-size-options' => [5, 10, 25, 50, 100]]"
                wire:model.live="selected"
                wire:sort-change="sortBy($event.detail.sort)"
                wire:page-change="$set('page', $event.detail.page)"
                wire:page-size-change="$set('pageSize', $event.detail.pageSize)"
            >
                <x-slot:actions>
                    <x-ceplok::button size="s" :left-icon="svg('ceplok-o-plus')">Tambah Data</x-ceplok::button>
                    <x-ceplok::button
                        size="s"
                        theme="orange"
                        :left-icon="svg('ceplok-s-trash-bin')"
                        :disabled="$selected === []"
                        wire:click="deleteSelected"
                        :wire:confirm="'Apakah anda yakin ingin menghapus '.count($selected).' data terpilih?'"
                    >Hapus Data</x-ceplok::button>
                </x-slot:actions>
            </x-ceplok::table>
            @break

        @case ('states')
            <div class="grid gap-5 lg:grid-cols-2">
                <x-docs.demo label="Loading">
                    <x-ceplok::table :columns="$this->columns()" :data="[]" row-key="id" loading />
                </x-docs.demo>
                <x-docs.demo label="Empty">
                    <x-ceplok::table :columns="$this->columns()" :data="[]" row-key="id" />
                </x-docs.demo>
            </div>
            @break

        @case ('compact')
        @case ('filter')
            <x-ceplok::table
                :columns="$this->columns()"
                :data="$this->rows()"
                row-key="id"
                size="compact"
                :sort="$sort"
                :search="['wire:model.live.debounce.300ms' => 'q']"
                :filter="['default-hidden-columns' => $example === 'filter' ? ['pembayaran'] : []]"
                :pagination="['page' => $page, 'page-size' => $pageSize, 'page-size-options' => [5, 10, 25, 50, 100]]"
                wire:sort-change="sortBy($event.detail.sort)"
                wire:page-change="$set('page', $event.detail.page)"
                wire:page-size-change="$set('pageSize', $event.detail.pageSize)"
            >
                <x-slot:actions>
                    <x-ceplok::button size="s" :left-icon="svg('ceplok-o-plus')">Tambah Data</x-ceplok::button>
                    <x-ceplok::button size="s" theme="orange" :left-icon="svg('ceplok-s-trash-bin')">Hapus Data</x-ceplok::button>
                </x-slot:actions>
            </x-ceplok::table>
            @break

        @case ('dark')
            {{-- Badge di kolom Field 4 ikut gelap otomatis karena dirender di sel Table ber-dark-mode. --}}
            <x-ceplok::table
                :columns="$this->columns()"
                :data="$this->rows()"
                row-key="id"
                striped
                dark-mode
                :sort="$sort"
                :search="['placeholder' => 'Cari Tabel...', 'wire:model.live.debounce.300ms' => 'q']"
                :filter="[]"
                :pagination="['page' => $page, 'page-size' => $pageSize, 'page-size-options' => [4, 10, 25]]"
                wire:sort-change="sortBy($event.detail.sort)"
                wire:page-change="$set('page', $event.detail.page)"
                wire:page-size-change="$set('pageSize', $event.detail.pageSize)"
            />
            @break

        @case ('server')
            <div class="space-y-3">
                {{-- Paginator Laravel membuat Table otomatis manual: halaman, jumlah per halaman, dan total ikut dari paginator. --}}
                <x-ceplok::table
                    :columns="$this->columns()"
                    :data="$this->rows()"
                    row-key="id"
                    :sort="$sort"
                    :search="['wire:model.live.debounce.300ms' => 'q']"
                    wire:sort-change="sortBy($event.detail.sort)"
                    wire:page-change="$set('page', $event.detail.page)"
                    wire:loading.class="opacity-60"
                />
                <div class="space-y-1 rounded-lg bg-gray-900 px-4 py-2.5 font-mono text-xs text-gray-300">
                    <p>
                        <span class="text-gray-500">Request terakhir: </span>{{ $this->requestUrl() }}
                        <span wire:loading.delay class="text-yellow-300"> · menunggu jawaban server…</span>
                    </p>
                </div>
            </div>
            @break

        @default
            <x-ceplok::table
                :columns="$this->columns()"
                :data="$this->rows()"
                row-key="id"
                :sort="$sort"
                :selectable="in_array($example, ['normal', 'zebra', 'group', 'scroll'], true)"
                :striped="$example === 'zebra'"
                wire:sort-change="sortBy($event.detail.sort)"
            />
    @endswitch
</div>
