@php
    use App\Docs\Theme;

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-700'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    $berkas = [
        ['value' => 'ktp', 'label' => 'KTP elektronik'],
        ['value' => 'kk', 'label' => 'Kartu keluarga'],
        ['value' => 'npwp', 'label' => 'NPWP'],
    ];

    // Ditulis di blok PHP ini karena teks <input> di nilai atribut ikut dibaca kompiler komponen.
    $description = "Pilihan ganda yang bisa dicentang secara mandiri. Dibangun di atas <input type='checkbox'> bawaan supaya keyboard dan pembaca layar tetap berfungsi, dengan warna, ukuran, dan jarak dari Foundations. Tersedia juga dalam tampilan gelap.";

    $props = [
        ['label', 'string | slot', 'null', 'Teks di samping kotak.'],
        ['helper-text', 'string | slot', 'null', 'Caption 12px di bawah label.'],
        ['platform', '"default" | "mobile"', 'default', 'Ukuran kotak: 16px atau 14px.'],
        ['state', '"default" | "inactive"', 'default', 'Inactive meredupkan teks sekaligus menonaktifkan kontrol.'],
        ['application', '"default" | "purple"', 'default', 'Warna kotak saat tercentang.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: kotak gray-700 bergaris gray-600 dengan label putih; inactive meredupkan teksnya ke gray-500.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <input type="checkbox"> diteruskan (name, value, checked, wire:model, …). class dan wire:key ke pembungkus terluar.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Checkbox">
    <x-docs.page
        eyebrow="Form"
        title="Checkbox"
        :description="$description"
        :toc="[
            'checkbox' => 'Checkbox',
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
        <x-docs.section id="checkbox" title="Checkbox">
            <x-docs.lead>
                Kotak 16px beradius 4px, berlatar gray-50 dengan garis gray-300. Saat dicentang, kotaknya terisi
                warna aksen dan centang putih 10px muncul di tengahnya.
            </x-docs.lead>
            <x-docs.demo>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <x-ceplok::checkbox label="Belum dicentang" :dark-mode="$docsDark" />
                    <x-ceplok::checkbox label="Sudah dicentang" checked :dark-mode="$docsDark" />
                    <x-ceplok::checkbox state="inactive" label="Tidak aktif" :dark-mode="$docsDark" />
                    <x-ceplok::checkbox state="inactive" label="Tidak aktif, tercentang" checked :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::checkbox label="Belum dicentang"⟨ dark-mode⟩ />
                <x-ceplok::checkbox label="Sudah dicentang" ⟦checked⟧⟨ dark-mode⟩ />

                {{-- Inactive — teks meredup sekaligus nonaktif --}}
                <x-ceplok::checkbox ⟦state⟧="inactive" label="Tidak aktif"⟨ dark-mode⟩ />
                <x-ceplok::checkbox ⟦state⟧="inactive" label="Tidak aktif, tercentang" checked⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="caption" title="Dengan caption">
            <x-docs.lead>
                Isi <span class="text-primary-500 dark:text-primary-400">helper-text</span> untuk menerangkan pilihan — berguna saat satu centang punya konsekuensi yang
                perlu dijelaskan. Kotak tetap sejajar dengan baris pertama label.
            </x-docs.lead>
            <x-docs.demo>
                <div class="space-y-4">
                    <x-ceplok::checkbox label="Saya menyetujui syarat dan ketentuan" helper-text="Termasuk pemrosesan data pribadi sesuai kebijakan privasi." checked :dark-mode="$docsDark" />
                    <x-ceplok::checkbox label="Kirim salinan ke email" helper-text="Tanda terima dikirim setelah permohonan tersimpan." :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::checkbox
                    label="Saya menyetujui syarat dan ketentuan"
                    ⟦helper-text⟧="Termasuk pemrosesan data pribadi sesuai kebijakan privasi."
                    checked
                    ⟨dark-mode⟩
                />
                <x-ceplok::checkbox
                    label="Kirim salinan ke email"
                    ⟦helper-text⟧="Tanda terima dikirim setelah permohonan tersimpan."
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                Platform mobile memakai kotak 14px dengan label 12px; ukuran centang dan caption tetap sama di
                keduanya.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Desktop · 16px">
                    <div class="space-y-3">
                        @foreach ($berkas as $b)
                            <x-ceplok::checkbox :label="$b['label']" :checked="$b['value'] === 'ktp'" :dark-mode="$docsDark" />
                        @endforeach
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile · 14px">
                    <div class="space-y-3">
                        @foreach ($berkas as $b)
                            <x-ceplok::checkbox platform="mobile" :label="$b['label']" :checked="$b['value'] === 'ktp'" :dark-mode="$docsDark" />
                        @endforeach
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Desktop — platform bawaan, tanpa prop --}}
                <x-ceplok::checkbox label="KTP elektronik" checked⟨ dark-mode⟩ />
                <x-ceplok::checkbox label="Kartu keluarga"⟨ dark-mode⟩ />
                <x-ceplok::checkbox label="NPWP"⟨ dark-mode⟩ />

                {{-- Mobile --}}
                <x-ceplok::checkbox ⟦platform⟧="mobile" label="KTP elektronik" checked⟨ dark-mode⟩ />
                <x-ceplok::checkbox ⟦platform⟧="mobile" label="Kartu keluarga"⟨ dark-mode⟩ />
                <x-ceplok::checkbox ⟦platform⟧="mobile" label="NPWP"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna kotak saat tercentang mengikuti aplikasi yang memakainya. Kotak yang kosong — gray-50
                bergaris gray-300 — sama di kedua aplikasi.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <div class="space-y-3">
                            <x-ceplok::checkbox :application="$a['value']" label="Tercentang" :helper-text="'bg-'.$a['token']" checked :dark-mode="$docsDark" />
                            <x-ceplok::checkbox :application="$a['value']" label="Kosong" :dark-mode="$docsDark" />
                        </div>
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                {{-- Default — tanpa prop application --}}
                <x-ceplok::checkbox label="Tercentang" helper-text="bg-primary-700" checked⟨ dark-mode⟩ />
                <x-ceplok::checkbox label="Kosong"⟨ dark-mode⟩ />

                {{-- Purple --}}
                <x-ceplok::checkbox ⟦application⟧="purple" label="Tercentang" helper-text="bg-purple-500" checked⟨ dark-mode⟩ />
                <x-ceplok::checkbox ⟦application⟧="purple" label="Kosong"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti kotak ke gray-700 bergaris gray-600, dengan label putih dan caption
                gray-400. Kotak yang tercentang tetap memakai warna aplikasi. State <span class="text-primary-500 dark:text-primary-400">inactive</span> meredupkan
                label dan caption ke gray-500; kotaknya tetap, kecuali saat tercentang yang terisi gray-500.
            </x-slot:lead>
            <x-docs.demo dark>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <x-ceplok::checkbox dark-mode label="Belum dicentang" helper-text="Keterangan singkat." />
                    <x-ceplok::checkbox dark-mode label="Sudah dicentang" helper-text="Keterangan singkat." checked />
                    <x-ceplok::checkbox dark-mode state="inactive" label="Tidak aktif" helper-text="Keterangan singkat." />
                    <x-ceplok::checkbox dark-mode state="inactive" label="Tidak aktif, tercentang" helper-text="Keterangan singkat." checked />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::checkbox ⟦dark-mode⟧ label="Belum dicentang" helper-text="Keterangan singkat." />
                <x-ceplok::checkbox ⟦dark-mode⟧ label="Sudah dicentang" helper-text="Keterangan singkat." checked />

                {{-- Inactive --}}
                <x-ceplok::checkbox ⟦dark-mode⟧ state="inactive" label="Tidak aktif" helper-text="Keterangan singkat." />
                <x-ceplok::checkbox
                    ⟦dark-mode⟧
                    state="inactive"
                    label="Tidak aktif, tercentang"
                    helper-text="Keterangan singkat."
                    checked
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.checkbox :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Checkbox sekelompok yang diikat ke satu properti array membentuk daftar nilai: tiap Checkbox
                memakai <span class="text-primary-500 dark:text-primary-400">wire:model</span> yang sama dengan <span class="text-primary-500 dark:text-primary-400">value</span> masing-masing, dan yang <span class="text-primary-500 dark:text-primary-400">value</span>-nya ada
                di array itu tercentang sejak HTML pertama. Checkbox tanpa <span class="text-primary-500 dark:text-primary-400">value</span> pada properti bool cukup
                mengikuti nilai benar/salahnya.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.checkbox-livewire :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                {{-- Komponen Livewire: public array $berkas = ['ktp']; --}}
                <x-ceplok::checkbox ⟦value⟧="ktp" label="KTP elektronik" ⟦wire:model.live⟧="berkas"⟨ dark-mode⟩ />
                <x-ceplok::checkbox ⟦value⟧="kk" label="Kartu keluarga" ⟦wire:model.live⟧="berkas"⟨ dark-mode⟩ />
                <x-ceplok::checkbox ⟦value⟧="npwp" label="NPWP" ⟦wire:model.live⟧="berkas"⟨ dark-mode⟩ />
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
