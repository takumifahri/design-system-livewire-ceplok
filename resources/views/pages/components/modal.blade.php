@php
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
        ['size', '"s" | "m"', 's', 'Ukuran Default Modal. Tidak tersedia untuk Popup.'],
        ['title', 'string | slot', 'null', 'Judul Default Modal sekaligus nama aksesibel. Tidak tersedia untuk Popup.'],
        ['close-on-backdrop', 'bool', 'true', 'Tutup Modal saat klik backdrop. Tidak memengaruhi klik di dalam dialog.'],
        ['close-on-escape', 'bool', 'true', 'Tutup Modal saat menekan Escape. Jika false, cancel native dicegah.'],
        ['show-close-button', 'bool', 'true', 'Tampilkan tombol X. Jika false, tombol dihapus sepenuhnya.'],
        ['close-label', 'string', 'Tutup modal', 'Nama aksesibel tombol tutup.'],
        ['footer', 'slot', 'null', 'Area aksi Default dan Popup. x-on:click="close()" di dalamnya menutup Modal; footer Popup tidak memakai divider.'],
        ['slot', 'slot', '—', 'Isi Modal. Pada Popup, icon dan pesan disusun di sini.'],
        ['class', 'string', '—', 'Class tambahan pada elemen <dialog>.'],
        ['…atribut', 'atribut HTML', '—', 'Atribut dialog lain diteruskan ke <dialog>. wire:model, x-model, dan wire:key dipasang di pembungkusnya.'],
    ];
    $hl = 'text-primary-500';
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
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'tema-state' => 'Tema, aksi, dan state',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="modal" title="Modal">
            <x-docs.lead>
                Gunakan Default untuk informasi, form, atau konten umum. Variant dan ukuran S tidak perlu
                ditulis karena keduanya merupakan nilai bawaan.
            </x-docs.lead>

            <div class="mt-4 mb-4">
                <x-ceplok::modal title="Terms of Service">
                    <x-slot:trigger>Buka Default</x-slot:trigger>
                    <p>{{ $ceplokCopy[0] }}</p>
                    <x-slot:footer>
                        <div class="flex w-full flex-wrap justify-end gap-3">
                            <x-ceplok::button variant="outline" theme="gray" size="xs" x-on:click="close()">Batal</x-ceplok::button>
                            <x-ceplok::button size="xs" x-on:click="close()">Saya Setuju</x-ceplok::button>
                        </div>
                    </x-slot:footer>
                </x-ceplok::modal>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::modal ⟦title⟧="Terms of Service">
                    <x-slot:⟦trigger⟧>Buka Modal</x-slot:trigger>
                    ...
                    <x-slot:⟦footer⟧>
                        <div class="flex w-full flex-wrap justify-end gap-3">
                            <x-ceplok::button variant="outline" theme="gray" size="xs" x-on:click="⟦close()⟧">Batal</x-ceplok::button>
                            <x-ceplok::button size="xs" x-on:click="⟦close()⟧">Saya Setuju</x-ceplok::button>
                        </div>
                    </x-slot:footer>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="popup" title="Popup">
            <x-docs.lead>
                Gunakan Popup untuk konfirmasi atau keputusan ringkas. Susun icon dan pesan di slot bawaan,
                lalu tempatkan tombol aksi di slot footer tanpa divider.
            </x-docs.lead>

            <div data-theme="light" class="mt-4 mb-4">
                <x-docs.modal-popup />
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::modal ⟦variant⟧="popup" ⟦aria-label⟧="Konfirmasi hapus konten">
                    <x-slot:trigger>Buka Modal</x-slot:trigger>

                    <div class="text-center">
                        <div class="flex justify-center">
                            <x-ceplok::icon class="size-12! shrink-0 text-red-600">
                                {{ svg('ceplok-s-exclamation-circle', '', ['viewBox' => '2 2 20 20']) }}
                            </x-ceplok::icon>
                        </div>
                        <p class="mx-auto mt-4 max-w-xs text-body font-medium text-content-muted">
                            Apakah anda yakin ingin menghapus konten ini?
                        </p>
                    </div>

                    <x-slot:footer>
                        <div class="flex w-full flex-wrap justify-center gap-4">
                            <x-ceplok::button variant="outline" theme="gray" size="xs" x-on:click="close()">Tidak, Batalkan</x-ceplok::button>
                            <x-ceplok::button size="xs" class="bg-red-700! text-white! …" x-on:click="close()">Ya, hapus konten ini</x-ceplok::button>
                        </div>
                    </x-slot:footer>
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
                    <article class="rounded-xl border border-border bg-surface p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-black text-gray-900">Size {{ $item['label'] }}</h3>
                                <p class="mt-1 text-body-sm text-gray-500">{{ $item['description'] }}</p>
                            </div>
                            <span class="shrink-0 rounded-md bg-primary-50 px-2 py-1 text-xs font-bold text-primary-700">{{ $item['max'] }} max</span>
                        </div>
                        <div class="mt-4">
                            <x-ceplok::modal :size="$item['value']" title="Terms of Service">
                                <x-slot:trigger>Buka Modal {{ $item['label'] }}</x-slot:trigger>
                                <div class="space-y-4">
                                    @foreach ($ceplokCopy as $paragraph)
                                        <p>{{ $paragraph }}</p>
                                    @endforeach
                                </div>
                                <x-slot:footer>
                                    <x-ceplok::button size="xs" class="ml-auto" x-on:click="close()">Ya, saya setuju</x-ceplok::button>
                                </x-slot:footer>
                            </x-ceplok::modal>
                        </div>
                    </article>
                @endforeach
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::modal ⟦size⟧="m" title="Terms of Service">
                    <x-slot:trigger>Buka Modal</x-slot:trigger>
                    <div class="space-y-4">
                        <p>…</p>
                    </div>
                    <x-slot:footer>
                        <x-ceplok::button size="xs" class="ml-auto" x-on:click="close()">Ya, saya setuju</x-ceplok::button>
                    </x-slot:footer>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>

            <p class="mt-4 max-w-2xl text-body-sm text-gray-500">
                Size S digunakan secara bawaan. Gunakan <span class="{{ $hl }}">size="m"</span> untuk lebar M.
            </p>
        </x-docs.section>

        <livewire:playground.modal />

        <x-docs.section id="tema-state" title="Tema, aksi, dan state">
            <h3 class="text-sm font-black text-gray-900">Light dan Dark Mode</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
                Consumer menentukan batas tema dengan <span class="{{ $hl }}">data-theme="light"</span> atau
                <span class="{{ $hl }}">data-theme="dark"</span> pada wrapper. Modal mewarisi token semantik melalui DOM,
                termasuk saat dialog tampil di top layer. Tidak ada prop dark-mode pada Modal. Coba kedua
                mode di Playground untuk Default S/M, konten panjang, gambar, dan Popup.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500">
                Surface, judul, teks body, divider, dan kontrol tutup mengikuti tema. Konten dengan warna
                sendiri, ikon Popup, serta tombol footer tetap milik consumer. Button belum menyediakan
                variant bahaya merah bawaan, jadi aksi hapus Popup memakai override class lokal dengan merah
                red-700 pada Light maupun Dark agar kontras teks kecil tetap terjaga.
            </p>

            <h3 class="mt-8 text-sm font-black text-gray-900">Aksi footer</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
                Modal tidak mengatur proses bisnis seperti validasi atau submit. Di dalam Modal,
                <span class="{{ $hl }}">x-on:click="close()"</span> menutupnya, padanan <span class="{{ $hl }}">close()</span> dari
                render function footer di React. Untuk proses di server, tutup Modal dari komponen
                Livewire setelah prosesnya berhasil, mis. dengan mengubah properti yang diikat
                <span class="{{ $hl }}">wire:model</span>.
            </p>

            <h3 class="mt-8 text-sm font-black text-gray-900">State Modal</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
                Tanpa pengikatan, Modal mengelola visibilitas sendiri di peramban dan slot
                <span class="{{ $hl }}">trigger</span> wajib tersedia.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500">
                Gunakan <span class="{{ $hl }}">wire:model</span> ketika visibilitas perlu mengikuti state aplikasi,
                padanan <span class="{{ $hl }}">open</span> + <span class="{{ $hl }}">onOpenChange</span>. Dengan pengikatan,
                trigger opsional, mis. Modal yang dibuka dari aksi baris Table. Contoh di bawah membuka
                Modal dari tombol di luarnya dan menutupnya dari server setelah Simpan.
            </p>
            <livewire:playground.modal-livewire />
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

                <x-ceplok::modal ⟦wire:model⟧="open" title="Edit data">
                    {{-- Form milik consumer --}}
                    <x-slot:footer>
                        <x-ceplok::button size="xs" ⟦wire:click⟧="simpan">Simpan</x-ceplok::button>
                    </x-slot:footer>
                </x-ceplok::modal>
                @endverbatim
            </x-docs.code>

            <h3 class="mt-8 text-sm font-black text-gray-900">Livewire</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
                Elemen <span class="{{ $hl }}">&lt;dialog&gt;</span> diberi <span class="{{ $hl }}">wire:ignore.self</span> supaya Modal yang
                sedang terbuka tidak ditutup saat komponen Livewire di sekitarnya dirender ulang. Isi Modal
                tetap ikut dirender ulang. Bila <span class="{{ $hl }}">variant</span> atau <span class="{{ $hl }}">size</span> perlu
                berubah dari server, ganti juga <span class="{{ $hl }}">wire:key</span> Modal-nya.
            </p>

            <h3 class="mt-8 text-sm font-black text-gray-900">Accessibility</h3>
            <p class="mt-1 max-w-2xl text-body-sm text-gray-500">
                <span class="{{ $hl }}">title</span> pada Default menjadi judul visual sekaligus nama aksesibel. Untuk Popup
                atau Modal tanpa judul visual yang sesuai, berikan <span class="{{ $hl }}">aria-label</span> atau arahkan
                <span class="{{ $hl }}">aria-labelledby</span> ke elemen yang memuat namanya.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500">
                Title Default sudah dirender sebagai <span class="{{ $hl }}">&lt;h2&gt;</span>; jangan membungkus isinya dengan
                heading tambahan hanya untuk membuat judul. Modal tidak otomatis membuat
                <span class="{{ $hl }}">aria-describedby</span>; berikan atribut tersebut bila diperlukan.
            </p>
            <p class="mt-3 max-w-2xl text-body-sm text-gray-500">
                Elemen native <span class="{{ $hl }}">&lt;dialog&gt;</span> menyediakan perilaku dialog dan top layer; browser
                mengelola fokus serta membuat latar belakang tidak interaktif. Susun kontrol awal yang aman,
                misalnya Batal, dan jangan arahkan fokus awal ke aksi yang merusak data.
            </p>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="{{ $hl }}">&lt;dialog&gt;</span> yang didukung diteruskan ke dialognya.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="52rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
