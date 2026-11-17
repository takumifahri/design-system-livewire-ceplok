@php
    use App\Docs\Theme;

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-700'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    $props = [
        ['label', 'string | slot', 'null', 'Teks label di atas kontrol.'],
        ['helper-text', 'string | slot', 'null', 'Caption di bawah kontrol.'],
        ['button-label', 'string', 'Pilih File', 'Label tombol pilih berkas.'],
        ['replace-label', 'string', 'Replace file', 'Label tombol yang sama setelah ada berkas terpilih.'],
        ['remove-label', 'string', 'Remove', 'Label tombol hapus berkas.'],
        ['placeholder', 'string', 'Belum ada file yang dipilih', 'Teks kolom nama berkas saat masih kosong.'],
        ['type', '"default" | "attach"', 'default', 'Bentuk kontrol: satu baris tombol + nama berkas, atau area seret-lepas.'],
        ['platform', '"default" | "mobile"', 'default', 'Tinggi dan ukuran teks baris pemilih berkas. Tidak berpengaruh pada type attach.'],
        ['application', '"default" | "purple"', 'default', 'Warna tombol; pada type attach hanya terlihat saat berkas sedang diseret.'],
        ['attach-label', 'string', 'Click to upload or drag and drop', 'Baris ajakan di dalam area seret-lepas.'],
        ['attach-hint', 'string', 'SVG, PNG, JPG or GIF (MAX. 800x400px)', 'Baris keterangan format di dalam area seret-lepas.'],
        ['wire:files-change', 'event', '—', 'Dipicu setiap berkas berganti, lewat dialog maupun seret-lepas, dengan detail.files; padanan onFilesChange. Juga bisa x-on:files-change.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: warna latar gray-800 dan teks putih/abu-abu.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <input type="file"> diteruskan (accept, required, name, wire:model, …), kecuali multiple: unggahannya selalu tunggal.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`,
    // cuplikannya menuliskannya lewat penanda ⟨…⟩, dan bagian Dark mode
    // bertukar menjadi Light mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Upload Form">
    <x-docs.page
        eyebrow="Form"
        title="Upload Form"
        description="Pemilih berkas dalam dua bentuk: satu baris tombol dengan nama berkas di sampingnya, atau area seret-lepas yang mengambil ruang lebih besar. Keduanya membungkus input berkas bawaan, jadi dialog, penyaringan accept, dan pengiriman formulir bekerja apa adanya."
        :toc="[
            'upload-form' => 'Upload Form',
            'platform' => 'Platform',
            'attach-form' => 'Attach Form',
            'application' => 'Application',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="upload-form" title="Upload Form">
            <x-docs.lead>
                Bentuk yang paling hemat tempat: tombol pilih berkas di kiri, nama berkas yang terpilih di
                kanan. Tingginya sama dengan satu baris isian biasa, jadi pakai ini ketika unggahan hanyalah
                salah satu isian di antara isian lain dan tidak perlu menonjol.
            </x-docs.lead>
            <x-docs.demo label="Default">
                <x-ceplok::upload label="Unggah dokumen" helper-text="PDF atau JPG, maksimal 2 MB." :dark-mode="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::upload
                    label="Unggah dokumen"
                    helper-text="PDF atau JPG, maksimal 2 MB."
                    name="dokumen"
                    accept="application/pdf,image/jpeg"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Yang dibungkus komponen ini <span class="text-primary-500 dark:text-primary-400">&lt;input type="file"&gt;</span> sungguhan — ia hanya
                disembunyikan secara visual, bukan diganti tombol tiruan. Cukup beri <span class="text-primary-500 dark:text-primary-400">name</span> seperti
                isian lain lalu kirim formulirnya seperti biasa, atau pasang <span class="text-primary-500 dark:text-primary-400">wire:model</span> untuk unggahan
                Livewire; event <span class="text-primary-500 dark:text-primary-400">files-change</span> baru diperlukan kalau Anda memang ingin membaca berkasnya
                di sisi klien, misalnya untuk pratinjau.
            </p>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                Dua ukuran untuk bentuk ini. <span class="text-primary-500 dark:text-primary-400">default</span> setinggi 44px dengan teks 14px; <span class="text-primary-500 dark:text-primary-400">mobile</span>
                setinggi 40px dengan teks 12px. Label dan captionnya tidak ikut mengecil, jadi yang berubah
                hanya barisnya sendiri.
            </x-docs.lead>
            <div class="grid gap-5">
                <x-docs.demo label="Default — 44px">
                    <x-ceplok::upload label="Unggah dokumen" helper-text="PDF atau JPG, maksimal 2 MB." :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Mobile — 40px">
                    <div class="max-w-95.5">
                        <x-ceplok::upload platform="mobile" label="Unggah dokumen" helper-text="PDF atau JPG, maksimal 2 MB." :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::upload ⟦platform⟧="mobile" label="Unggah dokumen"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="attach-form" title="Attach Form">
            <x-docs.lead>
                Bentuk kedua mengambil ruang jauh lebih besar: kotak setinggi 230px bergaris putus-putus,
                berisi ikon unggah dan dua baris keterangan. Pakai ini kalau mengunggah berkas memang
                pekerjaan utama di halaman atau modal tersebut — kotak selebar ini mengundang orang
                menyeret berkasnya ke dalam, dan jalan pintas itu memang tersedia.
            </x-docs.lead>
            <div class="grid gap-5">
                <x-docs.demo label="Default — 538px">
                    <div class="max-w-134.5">
                        <x-ceplok::upload type="attach" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile — 382px">
                    <div class="max-w-95.5">
                        <x-ceplok::upload type="attach" platform="mobile" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::upload
                    ⟦type⟧="attach"
                    accept="image/png,image/jpeg"
                    attach-label="Klik untuk memilih atau seret berkas ke sini"
                    attach-hint="PNG atau JPG, maksimal 2 MB"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Isi kotaknya sama persis di kedua platform — ikon 20px, ajakan 14px, keterangan format 12px.
                Yang berbeda hanya lebarnya, dan lebar itu datang dari wadahnya: komponen ini selebar ruang
                yang Anda beri, sama seperti isian form lain. Kedua demo di atas hanya membatasi wadahnya di
                538px dan 382px.
            </p>
            <p class="mt-3 text-body-sm text-gray-500 dark:text-gray-400">
                Berkas yang dijatuhkan dititipkan ke input aslinya, bukan sekadar disimpan di state, jadi ia
                ikut terkirim saat formulirnya di-submit — dan ikut diunggah bila input-nya diikat
                <span class="text-primary-500 dark:text-primary-400">wire:model</span>. Begitu ada berkas terpilih, namanya menggantikan baris ajakan supaya jelas
                bahwa areanya sudah terpakai. Dua baris teks di dalamnya bisa diganti lewat
                <span class="text-primary-500 dark:text-primary-400">attach-label</span> dan <span class="text-primary-500 dark:text-primary-400">attach-hint</span>.
            </p>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna tombol mengikuti aplikasi yang memakainya: <span class="text-primary-500 dark:text-primary-400">primary-700</span> untuk default dan
                <span class="text-primary-500 dark:text-primary-400">purple-500</span> untuk purple. Pilih sekali di tingkat halaman, lalu biarkan sama untuk
                seluruh form di aplikasi itu.
            </x-docs.lead>
            <div class="grid gap-5">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        {{--
                            Kedua bentuk berdampingan, susunan yang sama dengan kartu di
                            halaman Overview: baris pemilih berkas di kiri, area seret-lepas
                            di kanan. Barisnya dipusatkan vertikal karena tingginya jauh di
                            bawah area attach di sebelahnya.
                        --}}
                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="flex flex-col justify-center">
                                <x-ceplok::upload :application="$a['value']" label="Unggah dokumen" :helper-text="'bg-'.$a['token']" :dark-mode="$docsDark" />
                            </div>
                            <x-ceplok::upload type="attach" :application="$a['value']" :dark-mode="$docsDark" />
                        </div>
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::upload ⟦application⟧="purple" label="Unggah dokumen"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Kedua kotak attach di atas memang terlihat sama persis, dan itu bukan kekeliruan: dalam
                keadaan diam bentuk ini tidak punya satu pun elemen berwarna aksen. Warnanya baru muncul
                saat berkas ditahan di atas areanya — garis dan latarnya menyala mengikuti aplikasi yang
                sama. Seret sebuah berkas ke salah satunya untuk melihat bedanya.
            </p>
        </x-docs.section>

        <x-docs.mode-section name="Upload">
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> dapat digunakan untuk mengaktifkan warna gelap secara manual. Komponen secara otomatis menyesuaikan warna latar, batas, dan teks.
            </x-slot:lead>
            <div class="grid gap-5">
                <x-docs.demo label="Upload" :dark="! $docsDark" :light="$docsDark">
                    <x-ceplok::upload label="Upload File" helper-text="PDF atau JPG, maksimal 2 MB." :dark-mode="! $docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Attach — 538px" :dark="! $docsDark" :light="$docsDark">
                    <div class="max-w-134.5">
                        <x-ceplok::upload type="attach" :dark-mode="! $docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::upload
                    ⟦label⟧="Upload File"
                    ⟦helper-text⟧="PDF atau JPG, maksimal 2 MB."
                    ⟨!⟦dark-mode⟧⟩
                />

                <x-ceplok::upload type="attach"⟨! ⟦dark-mode⟧⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.upload :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Untuk unggahan Livewire, pasang <span class="text-primary-500 dark:text-primary-400">wire:model</span> pada tag komponennya dan
                <span class="text-primary-500 dark:text-primary-400">WithFileUploads</span> di komponen Livewire-nya. Berkas — termasuk yang dijatuhkan ke area
                attach — langsung diunggah sementara dan bisa divalidasi saat itu juga; tombol Remove ikut
                mengosongkan propertinya. Coba pilih berkas PDF atau JPG di bawah.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.upload-livewire :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::upload
                    label="Unggah dokumen"
                    accept="application/pdf,image/jpeg"
                    ⟦wire:model⟧="dokumen"
                    :helper-text="$errors->first('dokumen') ?: 'PDF atau JPG, maksimal 2 MB.'"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="text-primary-500 dark:text-primary-400">&lt;input type="file"&gt;</span> standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="52rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
