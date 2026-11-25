@php
    $docsDark = \App\Docs\Theme::dark();
    $ceplokCopy = [
        'Ceplok merupakan platform digital yang dirancang untuk mempermudah akses layanan pemerintah dengan pendekatan yang lebih modern, efisien, dan ramah pengguna. Melalui penyederhanaan alur layanan, Ceplok berfokus pada kemudahan pengguna',
        'Dalam pengembangannya, Ceplok mengadopsi prinsip desain berbasis kebutuhan pengguna (user-centered design), memastikan setiap fitur menjawab masalah nyata di lapangan. Elemen seperti navigasi, form input, dan komponen notifikasi ditampilkan secara sederhana tanpa detail visual berlebihan',
    ];
    $sizes = [
        ['value' => 's', 'label' => 'S', 'max' => '416px', 'description' => 'Untuk informasi singkat atau form sederhana.'],
        ['value' => 'm', 'label' => 'M', 'max' => '640px', 'description' => 'Untuk konten panjang, form lebih besar, atau konten dengan gambar.'],
    ];
    $props = [
        ['trigger', 'slot', 'wajib*', 'Tombol pembuka. Atribut slot menjadi props Button kit, atau <button> polos dengan raw. Wajib bila Modal tidak diikat wire:model/x-model.'],
        ['wire:model', 'atribut', '—', 'Mengikat visibilitas ke properti boolean Livewire (atau x-model untuk Alpine), padanan open + onOpenChange. Dengan ini trigger opsional.'],
        ['wire:open-change', 'event', '—', 'Dipicu saat Modal minta dibuka atau ditutup (trigger, tombol tutup, Escape, backdrop, atau close() di footer), dengan $event.detail.open.'],
        ['open', 'bool', 'false', 'Membuka Modal sejak halaman dimuat, tanpa pengikatan.'],
        ['variant', '"default" | "popup"', 'default', 'Menentukan struktur Modal. Popup digunakan untuk dialog konfirmasi ringkas.'],
        ['icon', 'string | Htmlable | false', 'tanda seru (Popup)', 'Ganti ikon bawaan Popup, atau gunakan false untuk menyembunyikannya. Hanya tersedia untuk Popup.'],
        ['size', '"s" | "m"', 's', 'Ukuran Default Modal. Tidak tersedia untuk Popup.'],
        ['dark-mode', 'bool', 'false', 'Menerapkan boundary tema gelap lokal pada dialog. data-theme eksplisit menjadi override.'],
        ['title', 'string | slot', 'null', 'Judul Default Modal sekaligus nama aksesibel. Tidak tersedia untuk Popup.'],
        ['close-on-backdrop', 'bool', 'true', 'Tutup Modal saat klik backdrop. Tidak memengaruhi klik di dalam dialog.'],
        ['close-on-escape', 'bool', 'true', 'Tutup Modal saat menekan Escape. Jika false, cancel native dicegah.'],
        ['show-close-button', 'bool', 'true', 'Tampilkan tombol X. Jika false, tombol dihapus sepenuhnya.'],
        ['close-label', 'string', 'Tutup modal', 'Nama aksesibel tombol tutup.'],
        ['actions', 'array', 'null', 'Aksi standar secondary, primary, dan danger. Tiap action menerima label, attributes, dan buttonProps.'],
        ['footer', 'slot', 'null', 'Override penuh area aksi; tidak dapat digunakan bersama actions. x-on:click="close()" menutup Modal.'],
        ['slot', 'slot', '—', 'Isi Modal. Pada Popup, susun pesannya di sini; ikon diatur melalui prop icon.'],
        ['class', 'string', '—', 'Class tambahan pada elemen <dialog>.'],
        ['…atribut', 'atribut HTML', '—', 'Atribut dialog lain diteruskan ke <dialog>. wire:model, x-model, dan wire:key dipasang di pembungkusnya.'],
    ];
    $hl = 'text-primary-500 dark:text-primary-400';
@endphp

<x-docs.layout title="Modal">
    <x-docs.page
        eyebrow="Components · Modal"
        title="Modal"
        description="Dialog untuk informasi atau tindakan yang perlu diselesaikan sebelum kembali ke halaman utama. Gunakan Default untuk konten umum dan Popup untuk konfirmasi ringkas."
        :toc="[
            'modal' => 'Modal',
            'popup' => 'Popup',
            'sizes' => 'Sizes',
            'aksi-footer' => 'Aksi Footer',
            'state-modal' => 'State Modal',
            'dark-mode' => 'Dark Mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="modal" title="Modal">
            <x-docs.lead>
                Gunakan Default untuk informasi, form, atau konten umum. Variant dan ukuran S tidak perlu
                ditulis karena keduanya merupakan nilai bawaan.
            </x-docs.lead>

            <div class="mt-4 mb-4">
                <x-ceplok::modal title="Terms of Service" :dark-mode="$docsDark" :actions="[
                    'secondary' => ['label' => 'Batal', 'attributes' => ['x-on:click' => 'close()']],
                    'primary' => ['label' => 'Saya Setuju', 'attributes' => ['x-on:click' => 'close()']],
                ]">
                    <x-slot:trigger :tone="$docsDark ? 'dark' : 'light'">Buka Default</x-slot:trigger>
                    <p>{{ $ceplokCopy[0] }}</p>
                </x-ceplok::modal>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::modal⟨ dark-mode⟩ ⟦title⟧="Terms of Service" :⟦actions⟧="[
                    'secondary' => ['label' => 'Batal', 'attributes' => ['x-on:click' => 'close()']],
                    'primary' => ['label' => 'Saya Setuju', 'attributes' => ['x-on:click' => 'close()']],
                ]">
                    <x-slot:⟦trigger⟧⟨ tone="dark"⟩>Buka Modal</x-slot:trigger>
                    ...
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                <span class="{{ $hl }}">title</span> pada Default menjadi judul visual sekaligus nama aksesibel.
                Untuk Popup atau Modal tanpa judul visual yang sesuai, berikan
                <span class="{{ $hl }}">aria-label</span> atau arahkan
                <span class="{{ $hl }}">aria-labelledby</span> ke elemen yang memuat namanya.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Title Default sudah dirender sebagai <span class="{{ $hl }}">&lt;h2&gt;</span>; jangan
                membungkus isinya dengan heading tambahan hanya untuk membuat judul. Modal tidak otomatis
                membuat <span class="{{ $hl }}">aria-describedby</span>; berikan atribut tersebut bila diperlukan.
            </p>
        </x-docs.section>

        <x-docs.section id="popup" title="Popup">
            <x-docs.lead>
                Gunakan Popup untuk konfirmasi atau keputusan ringkas. Tanda seru tampil secara bawaan;
                ganti melalui <span class="{{ $hl }}">icon</span> atau hilangkan dengan
                <span class="{{ $hl }}">:icon="false"</span>. Modal mengatur posisi dan jarak ikon;
                consumer menentukan jenis, ukuran, dan warnanya. Susun pesan di slot bawaan dan gunakan
                <span class="{{ $hl }}">actions</span> untuk tombol standar. Footer Popup tidak memakai divider.
            </x-docs.lead>

            <div @if ($docsDark) data-theme="dark" @endif class="mt-4 mb-4">
                <x-docs.modal-popup :dark-mode="$docsDark" />
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::modal⟨ dark-mode⟩
                    ⟦variant⟧="popup"
                    ⟦aria-label⟧="Konfirmasi hapus konten"
                    :⟦actions⟧="[
                        'secondary' => ['label' => 'Tidak, Batalkan', 'attributes' => ['x-on:click' => 'close()']],
                        'danger' => ['label' => 'Ya, hapus konten ini', 'attributes' => ['x-on:click' => 'close()']],
                    ]"
                >
                    <x-slot:trigger⟨ tone="dark"⟩>Buka Modal</x-slot:trigger>

                    <p class="mx-auto max-w-xs text-center text-body font-medium text-content-muted">
                        Apakah anda yakin ingin menghapus konten ini?
                    </p>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Contoh berikut mengganti tanda seru dengan ikon informasi. Gunakan
                <span class="{{ $hl }}">:icon="false"</span> untuk menyembunyikan ikon bawaan.
            </p>
            <x-docs.code>
                @verbatim
                <x-ceplok::modal
                    variant="popup"
                    icon="ceplok-o-info-circle"
                    aria-label="Informasi layanan"
                >
                    <p>Periksa kembali informasi layanan sebelum melanjutkan.</p>
                </x-ceplok::modal>

                <x-ceplok::modal variant="popup" :icon="false" aria-label="Konfirmasi">
                    <p>Dialog ini tidak menampilkan ikon.</p>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="sizes" title="Sizes">
            <x-docs.lead>
                Ukuran S dan M hanya berlaku pada Default Modal. Popup menggunakan ukuran bawaannya
                sendiri. Kedua ukuran Default tetap menyisakan jarak pada layar sempit.
            </x-docs.lead>

            <div class="mb-4 grid gap-4 sm:grid-cols-2">
                @foreach ($sizes as $item)
                    <article @if ($docsDark) data-theme="dark" @endif class="rounded-xl border border-border bg-surface p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-black text-gray-900 dark:text-white">Size {{ $item['label'] }}</h3>
                                <p class="mt-1 text-body-sm text-gray-500 dark:text-gray-400">{{ $item['description'] }}</p>
                            </div>
                            <span class="shrink-0 rounded-md bg-primary-50 px-2 py-1 text-xs font-bold text-primary-700">{{ $item['max'] }} max</span>
                        </div>
                        <div class="mt-4">
                            <x-ceplok::modal
                                :size="$item['value']"
                                title="Terms of Service"
                                :dark-mode="$docsDark"
                                :actions="['primary' => ['label' => 'Ya, saya setuju', 'attributes' => ['x-on:click' => 'close()']]]"
                            >
                                <x-slot:trigger :tone="$docsDark ? 'dark' : 'light'">Buka Modal {{ $item['label'] }}</x-slot:trigger>
                                <div class="space-y-4">
                                    @foreach ($ceplokCopy as $paragraph)
                                        <p>{{ $paragraph }}</p>
                                    @endforeach
                                </div>
                            </x-ceplok::modal>
                        </div>
                    </article>
                @endforeach
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::modal
                    ⟨dark-mode⟩
                    ⟦size⟧="m"
                    title="Terms of Service"
                    :actions="['primary' => ['label' => 'Ya, saya setuju', 'attributes' => ['x-on:click' => 'close()']]]"
                >
                    <x-slot:trigger⟨ tone="dark"⟩>Buka Modal</x-slot:trigger>
                    <div class="space-y-4">
                        <p>…</p>
                    </div>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>

            <p class="mt-4 max-w-2xl text-body-sm text-gray-500">
                Size S digunakan secara bawaan. Gunakan <span class="{{ $hl }}">size="m"</span> untuk lebar M.
            </p>
        </x-docs.section>

        <x-docs.section id="aksi-footer" title="Aksi Footer">
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Gunakan <span class="{{ $hl }}">actions</span> untuk tombol standar yang konsisten pada
                Default dan Popup. Peran aksi menentukan makna dan tampilan tombol, bukan proses yang dijalankan:
            </p>
            <ul class="mt-2 max-w-2xl list-disc space-y-1 pl-5 text-body-sm text-gray-500 dark:text-gray-400">
                <li><span class="{{ $hl }}">primary</span> untuk aksi utama, misalnya Simpan atau Lanjutkan.</li>
                <li><span class="{{ $hl }}">secondary</span> untuk aksi alternatif, sering kali Batal atau Kembali.</li>
                <li><span class="{{ $hl }}">danger</span> untuk aksi destruktif atau sulit dibatalkan, misalnya Hapus.</li>
            </ul>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Consumer menentukan label, event, proses, dan responsnya. Peran memberi tampilan serta urutan
                standar—secondary, primary, lalu danger. Modal meratakan aksi ke kanan pada Default dan ke tengah
                pada Popup. Aksi tidak menutup Modal otomatis; panggil <span class="{{ $hl }}">close()</span>
                setelah proses berhasil.
            </p>
            <x-docs.code>
                @verbatim
                <x-ceplok::modal
                    title="Edit data"
                    :⟦actions⟧="[
                        'secondary' => ['label' => 'Batal', 'attributes' => ['x-on:click' => 'close()']],
                        'primary' => ['label' => 'Simpan', 'attributes' => ['wire:click' => 'simpan']],
                    ]"
                >
                    {{-- Form milik consumer --}}
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Sesuaikan tombol per aksi lewat <span class="{{ $hl }}">buttonProps</span>, misalnya size,
                variant, theme, tone, disabled, type, atau class. Mengisi theme pada aksi danger mengganti
                preset destruktif Modal dengan theme Button yang dipilih. Gunakan
                <span class="{{ $hl }}">attributes</span> untuk event atau atribut HTML.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Gunakan slot <span class="{{ $hl }}">footer</span> jika perlu mengganti seluruh area footer
                dengan layout atau konten kustom. Pilih salah satu: actions atau footer. Tanpa keduanya,
                area footer tidak dirender.
            </p>
            <x-docs.code>
                @verbatim
                <x-ceplok::modal title="Pilih jadwal">
                    ...
                    <x-slot:footer>
                        <div class="flex items-center justify-between">
                            <p>Perubahan belum disimpan</p>
                            <x-ceplok::button x-on:click="close()">Tutup</x-ceplok::button>
                        </div>
                    </x-slot:footer>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="state-modal" title="State Modal">

            <p class="mt-1 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Tanpa pengikatan, Modal mengelola visibilitas sendiri di peramban dan slot
                <span class="{{ $hl }}">trigger</span> wajib tersedia.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Gunakan <span class="{{ $hl }}">wire:model</span> ketika visibilitas perlu mengikuti state aplikasi,
                padanan <span class="{{ $hl }}">open</span> + <span class="{{ $hl }}">onOpenChange</span>. Dengan pengikatan,
                trigger opsional, mis. Modal yang dibuka dari aksi baris Table. Contoh di bawah membuka
                Modal dari tombol di luarnya dan menutupnya dari server setelah Simpan.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Event <span class="{{ $hl }}">wire:open-change</span> adalah permintaan perubahan state,
                bukan event native close. Lifecycle native dialog tetap dimiliki Modal.
            </p>

            <h3 class="mt-8 text-sm font-black text-gray-900 dark:text-white">Kontrol penutupan</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Backdrop, Escape, dan tombol X aktif secara bawaan. Atur masing-masing melalui
                <span class="{{ $hl }}">close-on-backdrop</span>,
                <span class="{{ $hl }}">close-on-escape</span>, dan
                <span class="{{ $hl }}">show-close-button</span>. Jika semuanya false, sediakan aksi
                eksplisit untuk menutup Modal.
            </p>
            <div class="mt-4">
                <x-ceplok::modal
                    :dark-mode="$docsDark"
                    title="Konfirmasi"
                    :close-on-backdrop="false"
                    :close-on-escape="false"
                    :show-close-button="false"
                    :actions="['primary' => ['label' => 'Saya Mengerti', 'attributes' => ['x-on:click' => 'close()']]]"
                >
                    <x-slot:trigger :tone="$docsDark ? 'dark' : 'light'">Buka Modal</x-slot:trigger>
                    <p>Baca informasi sebelum melanjutkan.</p>
                </x-ceplok::modal>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::modal
                    title="Konfirmasi"
                    :close-on-backdrop="false"
                    :close-on-escape="false"
                    :show-close-button="false"
                    :actions="['primary' => ['label' => 'Saya Mengerti', 'attributes' => ['x-on:click' => 'close()']]]"
                >
                    <x-slot:trigger>Buka Modal</x-slot:trigger>
                    <p>Baca informasi sebelum melanjutkan.</p>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>

            <h3 class="mt-8 text-sm font-black text-gray-900 dark:text-white">State terkontrol</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Pada contoh ini, consumer menjadi sumber kebenaran melalui
                <span class="{{ $hl }}">wire:model</span>.
            </p>
            <livewire:playground.modal-livewire :dark="$docsDark" />
            <x-docs.code>
                @verbatim
                {{-- Komponen Livewire --}}
                public bool $open = false;

                public function simpan(): void
                {
                    // … simpan data
                    $this->open = false;
                }

                {{-- View-nya --}}
                <x-ceplok::button wire:click="$set('open', true)">Edit data</x-ceplok::button>

                <x-ceplok::modal⟨ dark-mode⟩ ⟦wire:model⟧="open" title="Edit data">
                    {{-- Form milik consumer --}}
                    <x-slot:footer>
                        <x-ceplok::button size="xs" ⟦wire:click⟧="simpan">Simpan</x-ceplok::button>
                    </x-slot:footer>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>

            <h3 class="mt-8 text-sm font-black text-gray-900 dark:text-white">Modal bertingkat</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500 dark:text-gray-400">
                Gunakan state terpisah untuk Modal pertama dan Popup konfirmasi. Popup yang dibuka
                terakhir berada di top layer; menutupnya tidak menutup Modal pertama dan fokus kembali
                ke tombol pembukanya.
            </p>
            <div class="mt-4" x-data="{ defaultOpen: false, popupOpen: false }">
                <x-ceplok::button :tone="$docsDark ? 'dark' : 'light'" x-on:click="defaultOpen = true">Buka Modal Bertingkat</x-ceplok::button>
                <x-ceplok::modal x-model="defaultOpen" :dark-mode="$docsDark" title="Kelola konten">
                    <p class="mb-4">Buka konfirmasi untuk menghapus konten tanpa menutup Modal ini.</p>
                    <x-ceplok::button size="xs" x-on:click="popupOpen = true">Hapus konten</x-ceplok::button>
                </x-ceplok::modal>
                <x-ceplok::modal
                    x-model="popupOpen"
                    :dark-mode="$docsDark"
                    variant="popup"
                    aria-label="Konfirmasi hapus konten"
                    :actions="[
                        'secondary' => ['label' => 'Tidak, Batalkan', 'attributes' => ['x-on:click' => 'close()']],
                        'danger' => ['label' => 'Ya, hapus konten ini', 'attributes' => ['x-on:click' => 'close()']],
                    ]"
                >
                    <p class="text-center">Apakah anda yakin ingin menghapus konten ini?</p>
                </x-ceplok::modal>
            </div>
            <x-docs.code>
                @verbatim
                <div x-data="{ defaultOpen: false, popupOpen: false }">
                    <x-ceplok::modal x-model="defaultOpen" title="Kelola konten">...</x-ceplok::modal>
                    <x-ceplok::modal x-model="popupOpen" variant="popup" aria-label="Konfirmasi">...</x-ceplok::modal>
                </div>
                @endverbatim
            </x-docs.code>

            <h3 class="mt-8 text-sm font-black text-gray-900 dark:text-white">Livewire</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
                Elemen <span class="{{ $hl }}">&lt;dialog&gt;</span> diberi <span class="{{ $hl }}">wire:ignore.self</span> supaya Modal yang
                sedang terbuka tidak ditutup saat komponen Livewire di sekitarnya dirender ulang. Isi Modal
                tetap ikut dirender ulang. Bila <span class="{{ $hl }}">variant</span> atau <span class="{{ $hl }}">size</span> perlu
                berubah dari server, ganti juga <span class="{{ $hl }}">wire:key</span> Modal-nya.
            </p>

            <p class="mt-3 max-w-2xl text-body-sm text-gray-500">
                Elemen native <span class="{{ $hl }}">&lt;dialog&gt;</span> menyediakan perilaku dialog dan top layer; browser
                mengelola fokus serta membuat latar belakang tidak interaktif. Susun kontrol awal yang aman,
                misalnya Batal, dan jangan arahkan fokus awal ke aksi yang merusak data.
            </p>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark Mode">
            <x-docs.lead>
                Gunakan <span class="{{ $hl }}">dark-mode</span> untuk menerapkan token gelap pada dialog
                dan Button action di dalamnya. Atribut <span class="{{ $hl }}">data-theme</span> eksplisit
                menjadi override dan memakai tema efektif yang sama. Trigger milik consumer mengatur temanya
                sendiri. Default memakai surface gray-700, sedangkan Popup memakai gray-600 dengan teks
                gray-300 dan warna action sesuai desain.
            </x-docs.lead>
            <div class="grid gap-5 lg:grid-cols-2">
                <x-docs.demo label="Default Dark" dark>
                    <div class="grid min-h-[440px] place-items-center p-4">
                        <x-ceplok::modal dark-mode title="Terms of Service" :actions="['primary' => ['label' => 'Saya Setuju', 'attributes' => ['x-on:click' => 'close()']]]">
                            <x-slot:trigger tone="dark">Buka Default</x-slot:trigger>
                            <p>{{ $ceplokCopy[0] }}</p>
                        </x-ceplok::modal>
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Popup Dark" dark>
                    <div class="grid min-h-[440px] place-items-center p-4">
                        <x-docs.modal-popup dark-mode />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::modal ⟦dark-mode⟧ title="Terms of Service">
                    <x-slot:trigger tone="dark">Buka Modal</x-slot:trigger>
                    ...
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.modal :dark="$docsDark" />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="{{ $hl }}">&lt;dialog&gt;</span> yang didukung diteruskan. Modal memiliki
                kontrak state sendiri melalui <span class="{{ $hl }}">wire:model</span> dan event
                <span class="{{ $hl }}">wire:open-change</span>; lifecycle native close dan cancel tetap
                dimiliki komponen. Prop title diatur khusus untuk Default.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="52rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
