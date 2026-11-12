<?php

use CeplokUi\DesignKit\Enums\PaginationTheme;
use CeplokUi\DesignKit\Enums\TableActionIconSize;
use CeplokUi\DesignKit\Enums\TableAlign;
use CeplokUi\DesignKit\Enums\TableSize;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;
use Livewire\Component;

/*
 * Playground halaman Table: data pengajuan layanan yang jumlahnya bisa
 * dipilih, port PlaygroundTable di docs React. Mulai dari tabel dasar — data,
 * zebra, dan pagination — lalu fitur lain dinyalakan lewat kontrol.
 *
 * Urutan, halaman, pencarian, dan baris yang dicentang adalah properti
 * Livewire; Table mengirim event, komponen ini menyimpannya lalu merender
 * ulang. Kolom berisi closure, jadi dibuat di setiap render.
 */
new class extends Component
{
    public int $count = 50;

    public bool $selectable = false;

    public bool $search = false;

    public bool $filter = false;

    public bool $toolbarActions = false;

    public bool $striped = true;

    /** `light` atau `dark`. */
    public string $mode = 'light';

    /**
     * Table tidak punya prop platform: rupa mobile muncul sendiri saat tabel
     * lebih sempit dari 640px, jadi pratinjaunya cukup menyempitkan panggung.
     * `desktop` atau `mobile`.
     */
    public string $view = 'desktop';

    public TableSize $size = TableSize::Normal;

    public bool $paginate = true;

    public PaginationTheme $theme = PaginationTheme::Primary;

    public bool $rowActions = false;

    public TableActionIconSize $iconSize = TableActionIconSize::Base;

    public bool $preview = true;

    public bool $grouped = false;

    public TableAlign $headerAlign = TableAlign::Left;

    /** @var array{key: string, direction: string}|null */
    public ?array $sort = null;

    public int $page = 1;

    public int $pageSize = 10;

    public string $q = '';

    /** @var list<int> */
    public array $selected = [];

    /** @var list<int> */
    public array $deleted = [];

    /** Data baru saat jumlahnya diganti, seperti `key={count}` di React. */
    public function updatedCount(): void
    {
        $this->reset('page', 'selected', 'deleted', 'sort');
    }

    public function updatedQ(): void
    {
        $this->page = 1;
    }

    public function updatedPageSize(): void
    {
        $this->page = 1;
    }

    public function sortBy(?array $sort): void
    {
        $this->sort = $sort;
        $this->page = 1;
    }

    public function deleteSelected(): void
    {
        $this->deleted = array_values(array_unique([...$this->deleted, ...$this->selected]));
        $this->selected = [];
    }

    public function deleteRow(int $id): void
    {
        $this->deleted[] = $id;
        $this->selected = array_values(array_diff($this->selected, [$id]));
    }

    /** Data contoh yang sama setiap kali dibuat. @return list<array<string, mixed>> */
    public static function pengajuan(int $count): array
    {
        $depan = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gilang', 'Hana', 'Indra', 'Joko', 'Kartika', 'Lukman', 'Maya', 'Nanda', 'Oki', 'Putri', 'Rizky', 'Sari', 'Taufik', 'Wulan'];
        $belakang = ['Saputra', 'Santoso', 'Lestari', 'Anggraini', 'Prasetyo', 'Rahmawati', 'Ramadhan', 'Pertiwi', 'Kurniawan', 'Susanto', 'Wijaya', 'Hakim', 'Permata', 'Utami', 'Firmansyah'];
        $layanan = ['Pembuatan KTP', 'Akta Kelahiran', 'Kartu Keluarga', 'Surat Pindah', 'Akta Kematian', 'Surat Keterangan Usaha'];
        $status = ['Disetujui', 'Diproses', 'Disetujui', 'Ditolak', 'Diproses'];
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $start = gmmktime(0, 0, 0, 1, 5, 2026);

        return array_map(function (int $i) use ($depan, $belakang, $layanan, $status, $bulan, $start) {
            $d = $depan[$i % count($depan)];
            $b = $belakang[($i * 7) % count($belakang)];
            $time = $start + $i * 86400;

            return [
                'id' => $i + 1,
                'nama' => "{$d} {$b}",
                'email' => strtolower("{$d}.{$b}".($i + 1).'@mail.com'),
                'layanan' => $layanan[($i * 5) % count($layanan)],
                'tanggal' => gmdate('d', $time).' '.$bulan[(int) gmdate('n', $time) - 1].' '.gmdate('Y', $time),
                'waktu' => $time,
                'dokumen' => sprintf('berkas-%04d.png', $i + 1),
                'status' => $status[$i % count($status)],
            ];
        }, range(0, $count - 1));
    }

    /** @return list<array<string, mixed>> */
    public function columns(): array
    {
        $badge = ['Disetujui' => 'success', 'Diproses' => 'warning', 'Ditolak' => 'danger'];
        $style = $this->iconSize === TableActionIconSize::Base ? [] : ['icon-size' => $this->iconSize->value];
        $nama = ['key' => 'nama', 'header' => 'Nama', 'align' => 'left', 'emphasis' => true, 'sortable' => true, 'sort-resettable' => false];
        $email = ['key' => 'email', 'header' => 'Email', 'align' => 'left'];

        return [
            ...($this->grouped ? [['key' => 'pemohon', 'header' => 'Pemohon', 'children' => [$nama, $email]]] : [$nama, $email]),
            ['key' => 'layanan', 'header' => 'Layanan', 'align' => 'left', 'sortable' => true, 'sort-resettable' => false],
            ['key' => 'tanggal', 'header' => 'Tanggal', 'align' => 'left', 'sortable' => true, 'sort-resettable' => false, 'sort-value' => fn ($row) => $row['waktu']],
            ['key' => 'dokumen', 'header' => 'Dokumen', 'align' => 'left', 'image' => [
                'src' => fn ($row) => asset('images/Placeholder/'.((($row['id'] - 1) % 5) + 1).'.png'),
                'alt' => fn ($row) => 'Dokumen '.$row['nama'],
                'caption' => fn ($row) => $row['dokumen'],
                'preview' => $this->preview,
            ]],
            ['key' => 'status', 'header' => 'Status', 'align' => 'left', 'cell' => fn ($row) => new HtmlString(
                Blade::render('<x-ceplok::badge :variant="$v">{{ $s }}</x-ceplok::badge>', ['v' => $badge[$row['status']], 's' => $row['status']]),
            )],
            ...($this->rowActions ? [['key' => 'aksi', 'header' => 'Aksi', 'hideable' => false, 'actions' => [
                ['key' => 'ubah', 'icon' => 'ceplok-s-edit', 'label' => 'Ubah', 'theme' => 'yellow'] + $style,
                ['key' => 'unduh', 'icon' => 'ceplok-s-download', 'label' => 'Unduh'] + $style,
                ['key' => 'cetak', 'icon' => 'ceplok-s-printer', 'label' => 'Cetak', 'theme' => 'green'] + $style,
                ['key' => 'hapus', 'icon' => 'ceplok-s-trash-bin', 'label' => 'Hapus', 'theme' => 'orange', 'attributes' => fn ($row) => [
                    'wire:click' => 'deleteRow('.$row['id'].')',
                    'wire:confirm' => 'Apakah anda yakin ingin menghapus data '.$row['nama'].'?',
                ]] + $style,
            ]]] : []),
        ];
    }

    /** @return list<array<string, mixed>> */
    public function rows(): array
    {
        $q = mb_strtolower($this->q);

        return array_values(array_filter(
            self::pengajuan($this->count),
            fn ($row) => ! in_array($row['id'], $this->deleted, true)
                && (str_contains(mb_strtolower($row['nama']), $q) || str_contains($row['email'], $q) || str_contains(mb_strtolower($row['layanan']), $q)),
        ));
    }

    /** Kode di blok Penggunaan, mengikuti pengaturan Playground. */
    #[Computed]
    public function usage(): string
    {
        $style = $this->iconSize === TableActionIconSize::Base ? '' : ", ⟦'icon-size'⟧ => '{$this->iconSize->value}'";

        return implode("\n", array_filter([
            '@php',
            '    $columns = [',
            ...($this->grouped
                ? [
                    "        ['key' => 'pemohon', 'header' => 'Pemohon', ⟦'children'⟧ => [",
                    "            ['key' => 'nama', 'header' => 'Nama', 'sortable' => true, 'emphasis' => true],",
                    "            ['key' => 'email', 'header' => 'Email'],",
                    '        ]],',
                ]
                : [
                    "        ['key' => 'nama', 'header' => 'Nama', 'sortable' => true, 'emphasis' => true],",
                    "        ['key' => 'email', 'header' => 'Email'],",
                ]),
            "        ['key' => 'layanan', 'header' => 'Layanan', 'sortable' => true],",
            "        ['key' => 'tanggal', 'header' => 'Tanggal', 'sortable' => true, 'sort-value' => fn (\$row) => \$row->created_at->timestamp],",
            "        ['key' => 'dokumen', 'header' => 'Dokumen', 'image' => ['src' => fn (\$row) => \$row->dokumen_url".($this->preview ? '' : ", ⟦'preview'⟧ => false").']],',
            "        ['key' => 'status', 'header' => 'Status', 'cell' => fn (\$row) => view('pengajuan.status', ['row' => \$row])],",
            $this->rowActions ? "        ['key' => 'aksi', 'header' => 'Aksi', 'hideable' => false, ⟦'actions'⟧ => [" : null,
            $this->rowActions ? "            ['key' => 'ubah', 'icon' => 'ceplok-s-edit', 'label' => 'Ubah', 'theme' => 'yellow'{$style}]," : null,
            $this->rowActions ? "            ['key' => 'hapus', 'icon' => 'ceplok-s-trash-bin', 'label' => 'Hapus', 'theme' => 'orange'{$style}," : null,
            $this->rowActions ? "                'attributes' => fn (\$row) => ['wire:click' => \"hapus({\$row->id})\"]]," : null,
            $this->rowActions ? '        ]],' : null,
            '    ];',
            '@endphp',
            '',
            '<x-ceplok::table',
            '    :columns="$columns"',
            '    :data="$rows"',
            '    row-key="id"',
            $this->size === TableSize::Compact ? '    ⟦size⟧="compact"' : null,
            $this->headerAlign === TableAlign::Left ? null : '    ⟦header-align⟧="'.$this->headerAlign->value.'"',
            $this->striped ? '    ⟦striped⟧' : null,
            $this->mode === 'dark' ? '    ⟦dark-mode⟧' : null,
            $this->selectable ? '    ⟦selectable⟧' : null,
            $this->selectable ? '    ⟦wire:model.live⟧="selected"' : null,
            $this->search ? "    :⟦search⟧=\"['placeholder' => 'Cari nama, email, atau layanan', 'wire:model.live.debounce.300ms' => 'q']\"" : null,
            $this->filter ? '    :⟦filter⟧="[]"' : null,
            '    :sort="$sort"',
            '    wire:sort-change="$set(\'sort\', $event.detail.sort)"',
            $this->paginate ? "    :⟦pagination⟧=\"['page' => \$page, 'page-size' => \$pageSize, 'page-size-options' => [5, 10, 25, 50, 100]".($this->theme === PaginationTheme::Primary ? '' : ", 'theme' => '{$this->theme->value}'").']"' : null,
            $this->paginate ? '    wire:page-change="$set(\'page\', $event.detail.page)"' : null,
            $this->paginate ? '    wire:page-size-change="$set(\'pageSize\', $event.detail.pageSize)"' : null,
            $this->toolbarActions ? '>' : '/>',
            $this->toolbarActions ? '    <x-slot:⟦actions⟧>' : null,
            $this->toolbarActions ? '        <x-ceplok::button size="s" :left-icon="svg(\'ceplok-o-plus\')">Tambah Data</x-ceplok::button>' : null,
            $this->toolbarActions ? '        <x-ceplok::button size="s" theme="orange" wire:click="hapusTerpilih">Hapus Data</x-ceplok::button>' : null,
            $this->toolbarActions ? '    </x-slot:actions>' : null,
            $this->toolbarActions ? '</x-ceplok::table>' : null,
        ], fn (?string $line) => $line !== null));
    }
};
?>

<div class="space-y-16">
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>
            Tabel yang bisa kamu utak-atik lewat pengaturan di bawahnya. Setiap perubahan langsung
            terlihat di sini, dan bagian Penggunaan menuliskan kodenya.
        </x-docs.lead>

        <x-docs.stage :max-width="$view === 'mobile' ? 'max-w-[390px]' : 'max-w-[1024px]'" :dark="$mode === 'dark'">
            <x-ceplok::table
                wire:key="playground-table-{{ $count }}"
                :columns="$this->columns()"
                :data="$this->rows()"
                row-key="id"
                :size="$size"
                :header-align="$headerAlign"
                :striped="$striped"
                :dark-mode="$mode === 'dark'"
                :selectable="$selectable"
                :sort="$sort"
                :search="$search ? ['placeholder' => 'Cari nama, email, atau layanan', 'wire:model.live.debounce.300ms' => 'q'] : null"
                :filter="$filter ? [] : null"
                :pagination="$paginate ? ['page' => $page, 'page-size' => $pageSize, 'page-size-options' => [5, 10, 25, 50, 100], 'theme' => $theme] : null"
                wire:model.live="selected"
                wire:sort-change="sortBy($event.detail.sort)"
                wire:page-change="$set('page', $event.detail.page)"
                wire:page-size-change="$set('pageSize', $event.detail.pageSize)"
            >
                @if ($toolbarActions)
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
                @endif
            </x-ceplok::table>
        </x-docs.stage>

        @php
            $onOff = [['value' => true, 'label' => 'On'], ['value' => false, 'label' => 'Off']];
        @endphp

        <x-docs.controls>
            <x-docs.control label="Mode">
                <x-docs.segmented label="Pilih mode" model="mode" :value="$mode" :options="[['value' => 'light', 'label' => 'Light'], ['value' => 'dark', 'label' => 'Dark']]" />
            </x-docs.control>
            <x-docs.control label="Tampilan">
                <x-docs.segmented label="Pilih tampilan" model="view" :value="$view" :options="[['value' => 'desktop', 'label' => 'Desktop'], ['value' => 'mobile', 'label' => 'Mobile']]" />
            </x-docs.control>
            <x-docs.control label="Jumlah data">
                <x-docs.segmented label="Pilih jumlah data" model="count" :value="$count" :options="array_map(fn ($n) => ['value' => $n, 'label' => (string) $n], [50, 100, 1000])" />
            </x-docs.control>
            <x-docs.control label="Size">
                <x-docs.segmented label="Pilih size" model="size" :value="$size" :options="[['value' => 'normal', 'label' => 'Normal'], ['value' => 'compact', 'label' => 'Compact']]" />
            </x-docs.control>
            <x-docs.control label="Posisi judul">
                <x-docs.segmented label="Pilih posisi judul" model="headerAlign" :value="$headerAlign" :options="[['value' => 'left', 'label' => 'Left'], ['value' => 'center', 'label' => 'Center'], ['value' => 'right', 'label' => 'Right']]" />
            </x-docs.control>
            <x-docs.control label="Baris zebra">
                <x-docs.segmented label="Baris zebra" model="striped" :value="$striped" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Seleksi baris">
                <x-docs.segmented label="Seleksi baris" model="selectable" :value="$selectable" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Pencarian">
                <x-docs.segmented label="Pencarian" model="search" :value="$search" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Filter kolom">
                <x-docs.segmented label="Filter kolom" model="filter" :value="$filter" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Tombol toolbar">
                <x-docs.segmented label="Tombol toolbar" model="toolbarActions" :value="$toolbarActions" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Kolom bertingkat">
                <x-docs.segmented label="Kolom bertingkat" model="grouped" :value="$grouped" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Pratinjau gambar">
                <x-docs.segmented label="Pratinjau gambar" model="preview" :value="$preview" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Tombol aksi baris">
                <x-docs.segmented label="Tombol aksi baris" model="rowActions" :value="$rowActions" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Ukuran ikon aksi">
                <x-docs.segmented
                    label="Pilih ukuran ikon aksi"
                    model="iconSize"
                    :value="$iconSize"
                    :disabled="! $rowActions"
                    :options="[['value' => 's', 'label' => 'S'], ['value' => 'base', 'label' => 'Base'], ['value' => 'l', 'label' => 'L'], ['value' => 'xl', 'label' => 'XL']]"
                />
            </x-docs.control>
            <x-docs.control label="Pagination">
                <x-docs.segmented label="Pagination" model="paginate" :value="$paginate" :options="$onOff" />
            </x-docs.control>
            <x-docs.control label="Theme pagination">
                <x-docs.segmented
                    label="Pilih theme pagination"
                    model="theme"
                    :value="$theme"
                    :disabled="! $paginate"
                    :options="[['value' => 'default', 'label' => 'Default'], ['value' => 'primary', 'label' => 'Primary'], ['value' => 'purple', 'label' => 'Purple']]"
                />
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Kode ini mengikuti pengaturan di Playground. Ubah pengaturannya, kodenya ikut berubah.
            <span class="text-primary-500">$rows</span>, <span class="text-primary-500">$sort</span>,
            <span class="text-primary-500">$page</span>, dan <span class="text-primary-500">$selected</span> adalah properti komponen Livewire Anda.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
