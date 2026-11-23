@php
    use App\Docs\Theme;

    $dropdownProps = [
        ['trigger', 'slot', '— (wajib)', 'Tombol pembuka panel. Dirender sebagai Button kit dengan atribut slot sebagai props-nya, atau sebagai <button> polos dengan raw.'],
        ['items', 'array', 'null', 'Daftar aksi tanpa pengelompokan.'],
        ['groups', 'array', 'null', 'Aksi terkelompok, lengkap dengan label dan pemisah antar-kelompok.'],
        ['slot', 'slot', '—', 'Isi panel bila bukan daftar aksi. Diabaikan selama items atau groups terisi.'],
        ['attached', 'bool', 'false', 'Mengaktifkan positioning panel relatif terhadap trigger dengan penyesuaian batas viewport, termasuk flip dan pembatasan tinggi saat ruang terbatas.'],
        ['dark-mode', 'bool', 'false', 'Menggunakan tampilan gelap pada panel dan konten yang dihasilkan Dropdown.'],
        ['content-label', 'string', 'null', 'Nama panel bagi pembaca layar; hanya terpasang pada bentuk daftar pilihan.'],
        ['content-class', 'string', 'null', 'Class tambahan pada panelnya.'],
        ['panel-id', 'string', 'otomatis', 'ID panel. Bawaannya `id` Dropdown ditambah -panel, atau ID stabil buatan kit.'],
        ['…atribut', 'HTML', '—', 'Atribut <div> standar diteruskan ke pembungkus terluar (class, id, aria-label, wire:key, …).'],
    ];

    $itemProps = [
        ['label', 'string', '— (wajib)', 'Teks aksi.'],
        ['description', 'string', 'null', 'Baris kedua di bawah label.'],
        ['icon', 'string | Htmlable', 'null', 'Ikon kecil di kiri label: nama ikon (mis. ceplok-o-user) atau markup SVG. Ukurannya diatur komponen.'],
        ['href', 'string', 'null', 'Bila diisi, aksinya dirender sebagai tautan.'],
        ['attributes', 'array', '[]', 'Atribut baris itu, mis. [\'wire:click\' => \'hapus(3)\'] — padanan onClick. Panel menutup sendiri setelah baris ditekan.'],
        ['tone', '"default" | "danger"', 'default', 'Warna semantik aksi.'],
        ['disabled', 'bool', 'false', 'Mematikan aksi; atribut barisnya tidak dipasang.'],
        ['selected', 'bool', 'null', 'Menandai item sebagai opsi terpilih. Jika digunakan pada item, Dropdown menggunakan semantik daftar pilihan (listbox).'],
        ['selected-when', 'string', 'null', 'Ekspresi Alpine untuk aria-selected, supaya tanda terpilihnya ikut berganti di browser tanpa render ulang server.'],
    ];

    $groupProps = [
        ['id', 'string', 'null', 'Penanda kelompok di kode; tidak dirender.'],
        ['label', 'string', 'null', 'Judul opsional yang ditampilkan di atas item dalam kelompok.'],
        ['separator', 'bool', 'false', 'Menampilkan garis pemisah sebelum kelompok ini.'],
        ['items', 'array', '— (wajib)', 'Daftar item yang ditampilkan di dalam kelompok.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: Dropdown di pratinjau memakai
    // `dark-mode`, dan Playground mulai dari mode docs. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Dropdown">
    <x-docs.page
        eyebrow="Components · Dropdown"
        title="Dropdown"
        description="Panel aksi yang dibuka dari sebuah tombol. Isinya ditentukan prop — slot trigger untuk tombolnya, items atau groups untuk daftarnya — seperti Select."
        :toc="[
            'dropdown' => 'Dropdown',
            'kelompok' => 'Kelompok',
            'keterangan' => 'Keterangan',
            'disable' => 'Disable',
            'selection' => 'Selection',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="dropdown" title="Dropdown">
            <x-docs.lead>
                Slot <span class="text-primary-500 dark:text-primary-400">trigger</span> menjadi tombolnya; <span class="text-primary-500 dark:text-primary-400">items</span> menerima daftar aksinya. Panel
                ditutup lewat trigger, klik di luar, atau Escape — semuanya diurus HTML Popover API, jadi
                tidak ada state buka/tutup yang bisa melenceng. Aksi menutup panel setelah ditekan;
                kontrol seperti Radio dan Checkbox tetap terbuka saat pilihannya berubah. Setelah panel
                terbuka, ketik nama aksinya untuk melompat ke sana — mis. <span class="text-primary-500 dark:text-primary-400">k</span> ke Keluar — lalu tekan
                Enter.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-xl">
                <div class="flex min-h-72 items-start justify-center pt-10">
                    <x-docs.dropdown-example example="actions" group-name="overview-dropdown" :dark="$docsDark" />
                </div>
            </x-docs.stage>
            <x-docs.code>
                @verbatim
                <x-ceplok::dropdown :items="[
                    ['label' => 'Profil'],
                    ['label' => 'Pengaturan'],
                    ['label' => 'Keluar', 'tone' => 'danger'],
                ]">
                    <x-slot:trigger raw>Dropdown button</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="kelompok" title="Kelompok">
            <x-docs.lead>
                Aksi yang perlu dipisah disusun lewat <span class="text-primary-500 dark:text-primary-400">groups</span>. Prop <span class="text-primary-500 dark:text-primary-400">separator</span> menambahkan
                garis sebelum sebuah kelompok, dan <span class="text-primary-500 dark:text-primary-400">label</span> memberinya judul. Untuk panel berisi kontrol
                bebas, pakai <span class="text-primary-500 dark:text-primary-400">&lt;hr&gt;</span> biasa.
            </x-docs.lead>
            <div class="grid gap-6 sm:grid-cols-2">
                <x-docs.demo label="Satu daftar">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="separator-none" :dark="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Dua kelompok">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="separator-with" separator :dark="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code mark="'separator'">
                @verbatim
                <x-ceplok::dropdown :⟦groups⟧="[
                    [
                        'id' => 'utama',
                        'items' => [
                            ['label' => 'Profil'],
                            ['label' => 'Pengaturan'],
                        ],
                    ],
                    [
                        'id' => 'keluar',
                        'separator' => true,
                        'items' => [['label' => 'Keluar', 'tone' => 'danger']],
                    ],
                ]">
                    <x-slot:trigger raw>Dropdown button</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="keterangan" title="Keterangan">
            <x-docs.lead>
                Aksi yang perlu konteks tambahan diberi <span class="text-primary-500 dark:text-primary-400">description</span> — baris kedua yang lebih kecil
                di bawah labelnya. Untuk panel berisi Radio atau Checkbox, yang dipakai <span class="text-primary-500 dark:text-primary-400">helper-text</span>
                milik komponen itu sendiri.
            </x-docs.lead>
            <div class="grid gap-6 sm:grid-cols-2">
                <x-docs.demo label="Tanpa keterangan">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="description-none" :dark="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Dengan keterangan">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="description-with" description :dark="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code mark="'description'">
                @verbatim
                <x-ceplok::dropdown :items="[
                    [
                        'label' => 'Editor',
                        'description' => 'Dapat mengubah konten.',
                    ],
                ]">
                    <x-slot:trigger raw>Dropdown button</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="disable" title="Disable">
            <x-docs.lead>
                Tambahkan <span class="text-primary-500 dark:text-primary-400">disabled</span> pada item yang belum dapat dipilih. Pengaturan ini berlaku per
                item, bukan untuk seluruh Dropdown.
            </x-docs.lead>
            <div class="grid gap-6 sm:grid-cols-2">
                <x-docs.demo label="Tanpa disable">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="disable-none" :dark="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Dengan disable">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="disable-with" disabled :dark="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code mark="'disabled'">
                @verbatim
                <x-ceplok::dropdown :items="[
                    [
                        'label' => 'Bantuan',
                        'disabled' => true,
                    ],
                ]">
                    <x-slot:trigger raw>Dropdown button</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="selection" title="Selection">
            <x-docs.lead>
                Gunakan Selection ketika Dropdown dipakai untuk memilih nilai. Saat item menggunakan
                <span class="text-primary-500 dark:text-primary-400">selected</span>, koleksinya memakai pola <span class="text-primary-500 dark:text-primary-400">listbox</span>, setiap baris menjadi
                <span class="text-primary-500 dark:text-primary-400">option</span>, dan pilihan aktif menerima fokus saat dibuka. Option dapat ditelusuri
                dengan tombol panah, Home, End, dan pencarian lewat ketikan — sama seperti daftar pilihan
                Select. Gunakan <span class="text-primary-500 dark:text-primary-400">content-label</span> untuk menamai listbox tersebut. Action dan Selection tidak
                dapat dicampur dalam satu koleksi: koleksi yang memakai <span class="text-primary-500 dark:text-primary-400">selected</span> seluruhnya
                diperlakukan sebagai Selection, sedangkan <span class="text-primary-500 dark:text-primary-400">href</span> hanya untuk item Action.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-xl">
                <div class="flex min-h-56 items-start justify-center pt-10">
                    <livewire:playground.dropdown-sort :dark="$docsDark" />
                </div>
            </x-docs.stage>
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Prop <span class="text-primary-500 dark:text-primary-400">attached</span> mengaktifkan positioning panel relatif terhadap trigger dengan penyesuaian batas viewport,
                termasuk flip dan pembatasan tinggi saat ruang terbatas. Bila pilihannya dipegang Alpine, bukan
                properti Livewire, isi <span class="text-primary-500 dark:text-primary-400">selected-when</span> dengan ekspresinya supaya tanda terpilih ikut berganti tanpa
                render ulang server.
            </p>
            <x-docs.code mark="'selected'">
                @verbatim
                @php
                    $urutan = collect(['Terbaru', 'Terlama', 'Nama A–Z'])->map(fn ($label) => [
                        'label' => $label,
                        'selected' => $label === $urut,
                        'attributes' => ['wire:click' => "\$set('urut', '{$label}')"],
                    ])->all();
                @endphp

                <x-ceplok::dropdown ⟦attached⟧ ⟦content-label⟧="Urutan hasil" :⟦items⟧="$urutan">
                    <x-slot:⟦trigger⟧ raw>Urutkan: {{ $urut }}</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengubah panel dan item bawaan ke tampilan gelap: panel gray-800,
                teks dan ikon gray-300, pemisah gray-700, serta aksi danger red-500. Untuk isi kustom di slot,
                tema komponen di dalamnya dikelola pemakai. Teruskan <span class="text-primary-500 dark:text-primary-400">dark-mode</span> ke Radio atau Checkbox
                jika komposisi perlu mengikuti tema Dropdown.
            </x-slot:lead>
            <div class="grid gap-6 md:grid-cols-2">
                <x-docs.demo label="Action" dark>
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="dark-actions" separator dark />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Action dengan ikon" dark>
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="icons" group-name="dark-icons" separator dark />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Selection" dark>
                    <div class="flex min-h-56 items-start justify-center pt-3">
                        <livewire:playground.dropdown-sort :dark="true" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::dropdown
                    ⟦dark-mode⟧
                    :⟦items⟧="[
                        ['label' => 'Profil', 'icon' => 'ceplok-o-user'],
                        ['label' => 'Keluar', 'icon' => 'ceplok-o-arrow-right-to-bracket', 'tone' => 'danger'],
                    ]"
                >
                    <x-slot:⟦trigger⟧ raw>Dropdown button</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.dropdown :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Aksi Livewire dipasang lewat <span class="text-primary-500 dark:text-primary-400">attributes</span> tiap item, mis. <span class="text-primary-500 dark:text-primary-400">wire:click</span>; panel menutup
                sendiri setelah baris ditekan. Tanpa <span class="text-primary-500 dark:text-primary-400">raw</span>, slot trigger dirender sebagai Button kit, jadi
                atribut slotnya dibaca sebagai props Button. Tombol dan atribut ARIA-nya dirender server, sehingga
                tetap utuh setiap kali Livewire merender ulang komponennya.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.dropdown-actions :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code mark="'attributes'">
                @verbatim
                <x-ceplok::dropdown :items="[
                    ['label' => 'Duplikat', 'attributes' => ['wire:click' => 'duplikat']],
                    ['label' => 'Arsipkan', 'attributes' => ['wire:click' => 'arsipkan']],
                    ['label' => 'Hapus', 'tone' => 'danger', 'attributes' => ['wire:click' => 'hapus']],
                ]">
                    <x-slot:trigger ⟦variant⟧="outline">Aksi dokumen</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya, diikuti bentuk data
                untuk <span class="text-primary-500 dark:text-primary-400">items</span> dan <span class="text-primary-500 dark:text-primary-400">groups</span>.
            </x-docs.lead>

            <div class="space-y-8 [&_td:first-child]:whitespace-nowrap">
                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900 dark:text-white">Dropdown</h3>
                    <x-docs.props-table :rows="$dropdownProps" min-width="48rem" />
                </div>
                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900 dark:text-white">Item</h3>
                    <x-docs.props-table :rows="$itemProps" min-width="46rem" />
                </div>
                <div>
                    <h3 class="mb-3 text-heading-4 font-bold text-gray-900 dark:text-white">Kelompok</h3>
                    <x-docs.props-table :rows="$groupProps" min-width="42rem" />
                </div>
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
