@php
    $docsDark = \App\Docs\Theme::dark();
    $docsTriggerClass = implode(' ', [
        'inline-flex h-10 items-center justify-center gap-2 rounded-lg px-4',
        'text-base font-medium transition-colors duration-200',
        $docsDark
            ? 'bg-gray-50 text-gray-700 hover:bg-white'
            : 'bg-primary-700 text-white hover:bg-primary-800',
        'focus:outline-none focus:ring-2 focus:ring-primary-400',
    ]);

    $dropdownProps = [
        ['trigger', 'slot', '— (wajib)', 'Tombol pembuka panel. Dirender sebagai Button kit atau <button> raw; Dropdown mengelola popovertarget, popovertargetaction, aria-expanded, dan atribut listbox bila relevan.'],
        ['items', 'array', 'null', 'Daftar aksi tanpa pengelompokan.'],
        ['groups', 'array', 'null', 'Aksi terkelompok, lengkap dengan label dan pemisah antar-kelompok.'],
        ['slot', 'slot', '—', 'Isi panel kustom. Gunakan content-label bila panel memerlukan nama aksesibel. Kirim event dropdown-close untuk dismissal eksplisit. Diabaikan selama items atau groups terisi.'],
        ['dropdown-close', 'event', '—', 'Dari dalam Dropdown, kirim event ini untuk menutup panel. Dari luar, kirim ke window dengan detail.panelId agar hanya panel yang dituju ditutup; padanan close() di React.'],
        ['align', '"start" | "center" | "end"', 'start', 'Menentukan perataan horizontal panel terhadap trigger sebelum penyesuaian batas viewport.'],
        ['close-on-select', 'bool', 'true', 'Bila true, item generated yang aktif menutup panel setelah dijalankan. Bila false, panel tetap terbuka.'],
        ['dark-mode', 'bool', 'false', 'Memaksa tema gelap pada root Dropdown. Tanpa prop ini, Dropdown mewarisi tema pembungkusnya.'],
        ['content-label', 'string', 'null', 'Nama panel bagi pembaca layar pada Selection dan slot kustom; menu aksi generated tidak mengubah semantiknya.'],
        ['content-class', 'string', 'null', 'Class tambahan pada panelnya.'],
        ['panel-id', 'string', 'otomatis', 'ID panel stabil untuk hubungan trigger dan panel. Bawaannya id Dropdown ditambah -panel atau ID buatan kit.'],
        ['…atribut', 'HTML', '—', 'Atribut <div> standar diteruskan ke pembungkus terluar (class, id, aria-label, wire:key, …).'],
    ];

    $itemProps = [
        ['label', 'string', '— (wajib)', 'Teks aksi.'],
        ['description', 'string', 'null', 'Baris kedua di bawah label.'],
        ['icon', 'string | Htmlable', 'null', 'Ikon kecil di kiri label: nama ikon (mis. ceplok-o-user) atau markup SVG. Ukurannya diatur komponen.'],
        ['href', 'string', 'null', 'Bila diisi, aksinya dirender sebagai tautan.'],
        ['target', 'string', 'null', 'Target native untuk tautan aktif.'],
        ['rel', 'string', 'null', 'Relasi native tautan. target="_blank" memakai noopener noreferrer bila rel tidak diberikan.'],
        ['attributes', 'array', '[]', 'Atribut baris itu, mis. [\'wire:click\' => \'hapus(3)\'] — padanan onClick React.'],
        ['tone', '"default" | "danger"', 'default', 'Warna semantik aksi.'],
        ['disabled', 'bool', 'false', 'Mematikan aksi; atribut barisnya tidak dipasang.'],
        ['current', 'bool | string', 'null', 'Menandai tujuan navigasi saat ini tanpa mengaktifkan semantik listbox. true berarti page.'],
        ['selected', 'bool', 'null', 'Menandai item sebagai opsi terpilih. Jika digunakan pada item, Dropdown menggunakan semantik daftar pilihan (listbox).'],
        ['selected-when', 'string', 'null', 'Ekspresi Alpine untuk aria-selected, supaya tanda terpilihnya ikut berganti di browser tanpa render ulang server.'],
    ];

    $groupProps = [
        ['id', 'string', 'null', 'Penanda kelompok di data; tidak dirender karena Blade tidak memerlukan key React.'],
        ['label', 'string', 'null', 'Judul opsional yang ditampilkan di atas item dalam kelompok.'],
        ['separator', 'bool', 'false', 'Menampilkan garis pemisah sebelum kelompok ini.'],
        ['items', 'array', '— (wajib)', 'Daftar item yang ditampilkan di dalam kelompok.'],
    ];
@endphp

<x-docs.layout title="Dropdown">
    <x-docs.page
        eyebrow="Components · Dropdown"
        title="Dropdown"
        description="Panel berisi daftar aksi yang ditampilkan ketika pengguna menekan sebuah tombol. Mendukung item aksi, navigasi, pemilihan, dan pengelompokan. Tersedia juga dalam tampilan gelap."
        :toc="[
            'dropdown' => 'Dropdown',
            'kelompok' => 'Kelompok',
            'keterangan' => 'Keterangan',
            'disable' => 'Disable',
            'navigation' => 'Navigation',
            'selection' => 'Selection',
            'kontrol-penutupan' => 'Kontrol Penutupan',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="dropdown" title="Dropdown">
            <x-docs.lead>
                Slot <span class="text-primary-500">trigger</span> menerima tombol pembuka, sedangkan
                <span class="text-primary-500">items</span> atau <span class="text-primary-500">groups</span>
                menyediakan daftar isinya. Panel dapat ditutup melalui trigger, klik di luar, atau Escape.
                Item aksi menutup panel setelah diaktifkan; konten kustom tetap terbuka sampai consumer
                menutupnya.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-xl" :dark="$docsDark">
                <div class="flex min-h-72 items-start justify-center pt-10">
                    <x-docs.dropdown-example example="actions" group-name="overview-dropdown" :dark="$docsDark" />
                </div>
            </x-docs.stage>
            <x-docs.code>
                @verbatim
                <x-ceplok::dropdown⟨ dark-mode⟩ :items="[
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
                Aksi yang perlu dipisah disusun lewat <span class="text-primary-500">groups</span>. Prop <span class="text-primary-500">separator</span> menambahkan
                garis sebelum sebuah kelompok. Untuk panel berisi konten kustom, gunakan pemisah visual
                yang sesuai di dalam slot.
            </x-docs.lead>
            <div class="grid gap-6 sm:grid-cols-2">
                <x-docs.demo label="Satu daftar" :dark="$docsDark">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="separator-none" :dark="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Dua kelompok" :dark="$docsDark">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="separator-with" separator :dark="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code mark="'separator'">
                @verbatim
                <x-ceplok::dropdown⟨ dark-mode⟩ :⟦groups⟧="[
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
                Aksi yang perlu konteks tambahan diberi <span class="text-primary-500">description</span> — baris kedua yang lebih kecil
                di bawah labelnya. Untuk panel berisi Radio atau Checkbox, yang dipakai <span class="text-primary-500">helper-text</span>
                milik komponen itu sendiri.
            </x-docs.lead>
            <div class="grid gap-6 sm:grid-cols-2">
                <x-docs.demo label="Tanpa keterangan" :dark="$docsDark">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="description-none" :dark="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Dengan keterangan" :dark="$docsDark">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="description-with" description :dark="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code mark="'description'">
                @verbatim
                <x-ceplok::dropdown⟨ dark-mode⟩ :items="[
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
                Tambahkan <span class="text-primary-500">disabled</span> pada item yang belum dapat dipilih. Pengaturan ini berlaku per
                item, bukan untuk seluruh Dropdown.
            </x-docs.lead>
            <div class="grid gap-6 sm:grid-cols-2">
                <x-docs.demo label="Tanpa disable" :dark="$docsDark">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="disable-none" :dark="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Dengan disable" :dark="$docsDark">
                    <div class="flex min-h-72 items-start justify-center pt-3">
                        <x-docs.dropdown-example example="actions" group-name="disable-with" disabled :dark="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code mark="'disabled'">
                @verbatim
                <x-ceplok::dropdown⟨ dark-mode⟩ :items="[
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

        <x-docs.section id="navigation" title="Navigation">
            <x-docs.lead>
                Item dengan <span class="text-primary-500">href</span> dirender sebagai anchor native.
                Gunakan <span class="text-primary-500">target</span> dan <span class="text-primary-500">rel</span>
                untuk tujuan eksternal, serta <span class="text-primary-500">current</span> untuk tujuan aktif.
                Berbeda dari <span class="text-primary-500">selected</span>, current tidak mengubah koleksi
                menjadi listbox dan menghasilkan <span class="text-primary-500">aria-current</span>.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-xl" :dark="$docsDark">
                <div class="flex min-h-64 items-start justify-center pt-10">
                    <x-ceplok::dropdown :dark-mode="$docsDark" :items="[
                        ['label' => 'Ringkasan', 'href' => '#ringkasan', 'current' => true],
                        ['label' => 'Dokumentasi', 'href' => 'https://example.com', 'target' => '_blank', 'rel' => 'noopener noreferrer'],
                        ['label' => 'Tujuan terlindungi', 'href' => '#terlindungi', 'disabled' => true],
                    ]">
                        <x-slot:trigger raw :class="$docsTriggerClass">
                            Buka navigasi
                            {{ svg('ceplok-o-chevron-down', 'size-3.5 shrink-0', ['aria-hidden' => 'true']) }}
                        </x-slot:trigger>
                    </x-ceplok::dropdown>
                </div>
            </x-docs.stage>
            <x-docs.code mark="'href'">
                @verbatim
                <x-ceplok::dropdown⟨ dark-mode⟩ :items="[
                    ['label' => 'Ringkasan', 'href' => '/ringkasan', 'current' => true],
                    [
                        'label' => 'Dokumentasi eksternal',
                        'href' => 'https://example.com',
                        'target' => '_blank',
                        'rel' => 'noopener noreferrer',
                    ],
                    ['label' => 'Tujuan terkunci', 'href' => '/terkunci', 'disabled' => true],
                ]">
                    <x-slot:trigger raw>Buka navigasi</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
            <p class="mt-4 max-w-3xl text-body-sm text-gray-500">
                Nilai bawaan <span class="text-primary-500">close-on-select=true</span> menutup panel
                setelah item generated yang aktif dijalankan. Event browser seperti
                <span class="text-primary-500">preventDefault()</span> pada handler item tidak mengubah
                kebijakan ini; gunakan <span class="text-primary-500">close-on-select=&quot;false&quot;</span>
                bila dismissal perlu ditentukan consumer.
            </p>
        </x-docs.section>

        <x-docs.section id="selection" title="Selection">
            <x-docs.lead>
                Gunakan Selection ketika Dropdown dipakai untuk memilih nilai. Saat item menggunakan
                <span class="text-primary-500">selected</span>, koleksinya memakai pola <span class="text-primary-500">listbox</span>, setiap baris menjadi
                <span class="text-primary-500">option</span>, dan pilihan aktif menerima fokus saat dibuka. Option dapat ditelusuri
                dengan tombol panah, Home, End, dan pencarian lewat ketikan — sama seperti daftar pilihan
                Select. Gunakan <span class="text-primary-500">content-label</span> untuk menamai listbox tersebut. Action dan Selection tidak
                dapat dicampur dalam satu koleksi: koleksi yang memakai <span class="text-primary-500">selected</span> seluruhnya
                diperlakukan sebagai Selection, sedangkan <span class="text-primary-500">href</span> hanya untuk item Action.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-xl" :dark="$docsDark">
                <div class="flex min-h-56 items-start justify-center pt-10">
                    <livewire:playground.dropdown-sort :dark="$docsDark" />
                </div>
            </x-docs.stage>
            <p class="mt-2 max-w-2xl text-body-sm text-gray-500">
                Dropdown selalu memosisikan panel relatif terhadap trigger, termasuk flip dan
                pembatasan viewport. Bila pilihannya dipegang Alpine, bukan properti Livewire,
                isi <span class="text-primary-500">selected-when</span> dengan ekspresinya supaya
                tanda terpilih ikut berganti tanpa render ulang server.
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

                <x-ceplok::dropdown⟨ dark-mode⟩ ⟦content-label⟧="Urutan hasil" :⟦items⟧="$urutan">
                    <x-slot:⟦trigger⟧ raw>Urutkan: {{ $urut }}</x-slot:trigger>
                </x-ceplok::dropdown>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="kontrol-penutupan" title="Kontrol Penutupan">
            <div class="space-y-8">
                <div>
                    <h3 class="mb-2 text-heading-4 font-bold text-gray-900 dark:text-white">Menutup konten kustom</h3>
                    <x-docs.lead>
                        Konten slot tetap terbuka agar form, Radio, atau Checkbox dapat dipakai berulang kali.
                        Kirim <span class="text-primary-500">dropdown-close</span> setelah aksi final selesai.
                    </x-docs.lead>
                    <x-docs.demo :dark="$docsDark">
                        <div class="flex min-h-56 items-start justify-center pt-10" x-data="{ applied: false }">
                            <div class="text-center">
                                <x-ceplok::dropdown content-label="Pengaturan tampilan" :dark-mode="$docsDark">
                                    <x-slot:trigger raw :class="$docsTriggerClass">
                                        Atur tampilan
                                        {{ svg('ceplok-o-chevron-down', 'size-3.5 shrink-0', ['aria-hidden' => 'true']) }}
                                    </x-slot:trigger>
                                    <div class="p-4">
                                        <button type="button" class="rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800" x-on:click="applied = true; $dispatch('dropdown-close')">
                                            Terapkan
                                        </button>
                                    </div>
                                </x-ceplok::dropdown>
                                <p class="mt-3 text-body-sm text-gray-500" x-show="applied">Pengaturan diterapkan</p>
                            </div>
                        </div>
                    </x-docs.demo>
                    <x-docs.code>
                        @verbatim
                        <x-ceplok::dropdown⟨ dark-mode⟩ content-label="Pengaturan tampilan">
                            <x-slot:trigger raw>Atur tampilan</x-slot:trigger>
                            <button type="button" x-on:click="applyChanges(); $dispatch('dropdown-close')">
                                Terapkan
                            </button>
                        </x-ceplok::dropdown>
                        @endverbatim
                    </x-docs.code>
                </div>
                <div>
                    <h3 class="mb-2 text-heading-4 font-bold text-gray-900 dark:text-white">Menutup dari kondisi eksternal</h3>
                    <x-docs.lead>
                        Untuk penutupan dari luar Dropdown, gunakan <span class="text-primary-500">panel-id</span>
                        stabil dan kirim event window yang ditargetkan. Event tanpa <span class="text-primary-500">detail.panelId</span>
                        hanya berlaku untuk event yang berasal dari dalam Dropdown.
                    </x-docs.lead>
                    <x-docs.demo :dark="$docsDark">
                        <div class="flex min-h-56 items-start justify-center gap-3 pt-10">
                            <x-ceplok::dropdown panel-id="docs-filter-status" :dark-mode="$docsDark" :items="[['label' => 'Aktif'], ['label' => 'Tidak aktif']]">
                                <x-slot:trigger raw :class="$docsTriggerClass">
                                    Filter status
                                    {{ svg('ceplok-o-chevron-down', 'size-3.5 shrink-0', ['aria-hidden' => 'true']) }}
                                </x-slot:trigger>
                            </x-ceplok::dropdown>
                            <button
                                type="button"
                                @class([
                                    'h-10 rounded-lg border px-4 text-sm font-medium',
                                    'border-gray-50 text-gray-50 hover:bg-white hover:text-gray-700' => $docsDark,
                                    'border-primary-700 text-primary-700 hover:bg-primary-50' => ! $docsDark,
                                ])
                                x-on:click="window.dispatchEvent(new CustomEvent('dropdown-close', { detail: { panelId: 'docs-filter-status' } }))"
                            >
                                Tutup Dropdown
                            </button>
                        </div>
                    </x-docs.demo>
                    <x-docs.code>
                        @verbatim
                        <x-ceplok::dropdown⟨ dark-mode⟩ panel-id="filter-status">
                            <x-slot:trigger raw>Filter status</x-slot:trigger>
                            {{-- ... --}}
                        </x-ceplok::dropdown>

                        <button
                            type="button"
                            x-on:click="window.dispatchEvent(new CustomEvent('dropdown-close', {
                                detail: { panelId: 'filter-status' },
                            }))"
                        >
                            Tutup filter
                        </button>
                        @endverbatim
                    </x-docs.code>
                </div>
            </div>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Prop <span class="text-primary-500">dark-mode</span> membuat boundary tema gelap lokal pada root Dropdown. Tanpa prop tersebut,
                Dropdown mewarisi <span class="text-primary-500">data-theme</span> dari pembungkus, termasuk pada scope tema bertingkat.
                Panel dan item generated memakai token semantik Dropdown; trigger dan isi kustom tetap dikelola pemakai. Teruskan tema yang sama
                ke Button, Radio, atau Checkbox di dalamnya.
            </x-docs.lead>
            <x-docs.stage max-width="max-w-xl" dark>
                <div class="flex min-h-72 items-start justify-center pt-3">
                    <x-docs.dropdown-example example="icons" group-name="dark-actions" dark />
                </div>
            </x-docs.stage>
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
        </x-docs.section>

        <livewire:playground.dropdown :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Aksi Livewire dipasang lewat <span class="text-primary-500">attributes</span> tiap item, mis. <span class="text-primary-500">wire:click</span>. Bawaannya panel
                menutup setelah baris ditekan; gunakan <span class="text-primary-500">close-on-select="false"</span> bila proses asynchronous perlu menentukan waktunya.
                Isi kustom dapat mengirim event <span class="text-primary-500">dropdown-close</span> untuk menutup panel. Tanpa <span class="text-primary-500">raw</span>, slot trigger dirender sebagai Button kit, jadi
                atribut slotnya dibaca sebagai props Button. Tombol dan atribut ARIA-nya dirender server, sehingga
                tetap utuh setiap kali Livewire merender ulang komponennya.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.dropdown-actions :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code mark="'attributes'">
                @verbatim
                <x-ceplok::dropdown⟨ dark-mode⟩ :items="[
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
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Bentuk data
                <span class="text-primary-500">Item</span> dan <span class="text-primary-500">Kelompok</span> mengikuti DropdownItem dan DropdownGroup di React;
                atribut serta event khusus Livewire tetap dicantumkan sebagai adaptasi framework.
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
