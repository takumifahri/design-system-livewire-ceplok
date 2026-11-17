@php
    use App\Docs\Theme;

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-700'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    // Ditulis di blok PHP ini karena teks <input> di nilai atribut ikut dibaca kompiler komponen.
    $description = "Sakelar untuk menyalakan atau mematikan satu pengaturan, berlaku seketika tanpa tombol simpan. Dibangun di atas <input type='checkbox'> dengan role='switch' supaya keyboard dan pembaca layar tetap berfungsi. Tersedia juga dalam tampilan gelap.";

    $props = [
        ['label', 'string | slot', 'null', 'Teks di samping sakelar.'],
        ['helper-text', 'string | slot', 'null', 'Caption 12px di bawah label.'],
        ['platform', '"default" | "mobile"', 'default', 'Ukuran sakelar: 40×20px atau 36×18px.'],
        ['state', '"default" | "inactive"', 'default', 'Inactive meredupkan tampilan sekaligus menonaktifkan kontrol.'],
        ['application', '"default" | "purple"', 'default', 'Warna jalur saat sakelar menyala.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: jalur gray-600 dengan bulatan gray-400 yang memutih saat menyala, dan label putih; inactive meredupkan label ke gray-500.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <input type="checkbox"> diteruskan (name, value, checked, wire:model, …). class dan wire:key ke pembungkus terluar.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`,
    // cuplikannya menuliskannya lewat penanda ⟨…⟩, dan bagian Dark mode
    // bertukar menjadi Light mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Toggle Button">
    <x-docs.page
        eyebrow="Form"
        title="Toggle Button"
        :description="$description"
        :toc="[
            'toggle-button' => 'Toggle Button',
            'caption' => 'Dengan caption',
            'platform' => 'Platform',
            'application' => 'Application',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="toggle-button" title="Toggle Button">
            <x-docs.lead>
                Jalur 40×20px dengan bulatan putih 16px yang menyisakan 2px di tiap sisi. Saat mati jalurnya
                gray-200; saat menyala ia berganti ke warna aksen dan bulatannya bergeser 20px ke kanan.
            </x-docs.lead>
            <x-docs.demo>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <x-ceplok::toggle label="Mati" :dark-mode="$docsDark" />
                    <x-ceplok::toggle label="Menyala" checked :dark-mode="$docsDark" />
                    <x-ceplok::toggle state="inactive" label="Tidak aktif" :dark-mode="$docsDark" />
                    <x-ceplok::toggle state="inactive" label="Tidak aktif, menyala" checked :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::toggle label="Mati"⟨ dark-mode⟩ />
                {{-- Menyala sejak awal --}}
                <x-ceplok::toggle label="Menyala" ⟦checked⟧⟨ dark-mode⟩ />

                {{-- Inactive — meredup sekaligus nonaktif --}}
                <x-ceplok::toggle ⟦state⟧="inactive" label="Tidak aktif"⟨ dark-mode⟩ />
                <x-ceplok::toggle ⟦state⟧="inactive" label="Tidak aktif, menyala" checked⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="caption" title="Dengan caption">
            <x-docs.lead>
                Isi <span class="text-primary-500 dark:text-primary-400">helper-text</span> untuk menerangkan akibat dari menyalakan pengaturan — berguna karena sakelar
                berlaku langsung tanpa konfirmasi.
            </x-docs.lead>
            <x-docs.demo>
                <div class="space-y-4">
                    <x-ceplok::toggle label="Notifikasi email" helper-text="Kirim ringkasan permohonan baru setiap pagi." checked :dark-mode="$docsDark" />
                    <x-ceplok::toggle label="Tampilkan data sensitif" helper-text="NIK dan nomor telepon ditampilkan penuh pada tabel." :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::toggle
                    label="Notifikasi email"
                    ⟦helper-text⟧="Kirim ringkasan permohonan baru setiap pagi."
                    checked
                    ⟨dark-mode⟩
                />
                <x-ceplok::toggle
                    label="Tampilkan data sensitif"
                    ⟦helper-text⟧="NIK dan nomor telepon ditampilkan penuh pada tabel."
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                Platform mobile memakai sakelar 36×18px dengan bulatan 14px dan label 12px; ukuran caption tetap
                12px di keduanya.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Desktop · 40×20px">
                    <div class="space-y-3">
                        <x-ceplok::toggle label="Notifikasi aktif" checked :dark-mode="$docsDark" />
                        <x-ceplok::toggle label="Notifikasi nonaktif" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile · 36×18px">
                    <div class="space-y-3">
                        <x-ceplok::toggle platform="mobile" label="Notifikasi aktif" checked :dark-mode="$docsDark" />
                        <x-ceplok::toggle platform="mobile" label="Notifikasi nonaktif" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Desktop — platform bawaan, tanpa prop --}}
                <x-ceplok::toggle label="Notifikasi aktif" checked⟨ dark-mode⟩ />
                <x-ceplok::toggle label="Notifikasi nonaktif"⟨ dark-mode⟩ />

                {{-- Mobile --}}
                <x-ceplok::toggle ⟦platform⟧="mobile" label="Notifikasi aktif" checked⟨ dark-mode⟩ />
                <x-ceplok::toggle ⟦platform⟧="mobile" label="Notifikasi nonaktif"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna jalur saat menyala mengikuti aplikasi yang memakainya. Jalur yang mati (gray-200) dan yang
                inactive (gray-300) sama di kedua aplikasi.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <div class="space-y-3">
                            <x-ceplok::toggle :application="$a['value']" label="Menyala" :helper-text="'bg-'.$a['token']" checked :dark-mode="$docsDark" />
                            <x-ceplok::toggle :application="$a['value']" label="Mati" :dark-mode="$docsDark" />
                        </div>
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                {{-- Default — tanpa prop application --}}
                <x-ceplok::toggle label="Menyala" helper-text="bg-primary-700" checked⟨ dark-mode⟩ />
                <x-ceplok::toggle label="Mati"⟨ dark-mode⟩ />

                {{-- Purple --}}
                <x-ceplok::toggle ⟦application⟧="purple" label="Menyala" helper-text="bg-purple-500" checked⟨ dark-mode⟩ />
                <x-ceplok::toggle ⟦application⟧="purple" label="Mati"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section name="Toggle">
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti jalur yang mati ke gray-600 dengan bulatan gray-400, label putih, dan
                caption gray-400. Saat menyala, jalurnya tetap memakai warna aplikasi dan bulatannya berubah
                putih. State <span class="text-primary-500 dark:text-primary-400">inactive</span> hanya meredupkan label ke gray-500 — jalur dan bulatannya tetap
                seperti saat mati, meski sakelarnya menyala.
            </x-slot:lead>
            <x-docs.demo :dark="! $docsDark" :light="$docsDark">
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <x-ceplok::toggle :dark-mode="! $docsDark" label="Mati" helper-text="Keterangan singkat." />
                    <x-ceplok::toggle :dark-mode="! $docsDark" label="Menyala" helper-text="Keterangan singkat." checked />
                    <x-ceplok::toggle :dark-mode="! $docsDark" state="inactive" label="Tidak aktif" helper-text="Keterangan singkat." />
                    <x-ceplok::toggle :dark-mode="! $docsDark" state="inactive" label="Tidak aktif, menyala" helper-text="Keterangan singkat." checked />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::toggle⟨! ⟦dark-mode⟧⟩ label="Mati" helper-text="Keterangan singkat." />
                <x-ceplok::toggle⟨! ⟦dark-mode⟧⟩ label="Menyala" helper-text="Keterangan singkat." checked />

                {{-- Inactive --}}
                <x-ceplok::toggle⟨! ⟦dark-mode⟧⟩ state="inactive" label="Tidak aktif" helper-text="Keterangan singkat." />
                <x-ceplok::toggle
                    ⟨!⟦dark-mode⟧⟩
                    state="inactive"
                    label="Tidak aktif, menyala"
                    helper-text="Keterangan singkat."
                    checked
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.toggle :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Pasang <span class="text-primary-500 dark:text-primary-400">wire:model</span> pada tag komponennya; keadaan awal propertinya sudah dirender server,
                jadi sakelar tampil menyala atau mati dengan benar sejak HTML pertama. Karena sakelar berlaku
                seketika, <span class="text-primary-500 dark:text-primary-400">wire:model.live</span> biasanya yang tepat: setiap ketukan langsung disimpan.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.toggle-livewire :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::toggle
                    label="Notifikasi email"
                    helper-text="Kirim ringkasan permohonan baru setiap pagi."
                    ⟦wire:model.live⟧="notifikasi"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="text-primary-500 dark:text-primary-400">&lt;input type="checkbox"&gt;</span> standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="48rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
