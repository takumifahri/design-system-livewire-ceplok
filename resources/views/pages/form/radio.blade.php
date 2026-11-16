@php
    use App\Docs\Theme;

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-700'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    $kewarganegaraan = [
        ['value' => 'wni', 'label' => 'Warga negara Indonesia'],
        ['value' => 'wna', 'label' => 'Warga negara asing'],
    ];

    // Ditulis di blok PHP ini karena teks <input> di nilai atribut ikut dibaca kompiler komponen.
    $description = "Pilihan tunggal dari beberapa opsi yang saling meniadakan. Dibangun di atas <input type='radio'> bawaan supaya navigasi panah dan pembaca layar tetap berfungsi, dengan warna, ukuran, dan jarak dari Foundations. Tersedia juga dalam tampilan gelap.";

    $props = [
        ['label', 'string | slot', 'null', 'Teks di samping lingkaran.'],
        ['helper-text', 'string | slot', 'null', 'Caption 12px di bawah label.'],
        ['platform', '"default" | "mobile"', 'default', 'Ukuran lingkaran: 16px atau 14px.'],
        ['state', '"default" | "inactive"', 'default', 'Inactive meredupkan tampilan sekaligus menonaktifkan kontrol.'],
        ['application', '"default" | "purple"', 'default', 'Warna cincin saat pilihan dipilih.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: lingkaran gray-300 bergaris gray-400 dengan label terang; inactive gray-700 bergaris gray-800 dengan teks gray-600.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <input type="radio"> diteruskan (name, value, checked, wire:model, …). class dan wire:key ke pembungkus terluar.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`,
    // cuplikannya menuliskannya lewat penanda ⟨…⟩, dan bagian Dark mode
    // bertukar menjadi Light mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Radio Button">
    <x-docs.page
        eyebrow="Form"
        title="Radio Button"
        :description="$description"
        :toc="[
            'radio-button' => 'Radio Button',
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
        <x-docs.section id="radio-button" title="Radio Button">
            <x-docs.lead>
                Lingkaran 16px berlatar gray-50 dengan garis gray-300. Saat dipilih, garisnya menebal jadi 3,5px
                berwarna aksen — sisa ruang di tengah (9px) itulah yang tampak sebagai titik. State
                <span class="text-primary-500 dark:text-primary-400">inactive</span> memakai latar gray-100 dan cincin gray-400, sekaligus mematikan kontrolnya.
            </x-docs.lead>
            <x-docs.demo>
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <x-ceplok::radio name="ds-radio-dasar" label="Belum dipilih" :dark-mode="$docsDark" />
                    <x-ceplok::radio name="ds-radio-dasar" label="Sedang dipilih" checked :dark-mode="$docsDark" />
                    <x-ceplok::radio name="ds-radio-nonaktif" state="inactive" label="Tidak aktif" :dark-mode="$docsDark" />
                    <x-ceplok::radio name="ds-radio-nonaktif-terpilih" state="inactive" label="Tidak aktif, terpilih" checked :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                {{-- Sekelompok pilihan: samakan name-nya --}}
                <x-ceplok::radio ⟦name⟧="pilihan" label="Belum dipilih"⟨ dark-mode⟩ />
                <x-ceplok::radio ⟦name⟧="pilihan" label="Sedang dipilih" checked⟨ dark-mode⟩ />

                {{-- Inactive — meredup sekaligus nonaktif --}}
                <x-ceplok::radio name="pilihan-nonaktif" ⟦state⟧="inactive" label="Tidak aktif"⟨ dark-mode⟩ />
                <x-ceplok::radio name="pilihan-nonaktif" ⟦state⟧="inactive" label="Tidak aktif, terpilih" checked⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="caption" title="Dengan caption">
            <x-docs.lead>
                Isi <span class="text-primary-500 dark:text-primary-400">helper-text</span> untuk menambahkan keterangan 12px di bawah label — berguna saat pilihannya
                perlu penjelasan. Lingkaran tetap sejajar dengan baris pertama label.
            </x-docs.lead>
            <x-docs.demo>
                <div class="space-y-4">
                    <x-ceplok::radio name="ds-radio-caption" label="Warga negara Indonesia" helper-text="Wajib melampirkan KTP elektronik yang masih berlaku." checked :dark-mode="$docsDark" />
                    <x-ceplok::radio name="ds-radio-caption" label="Warga negara asing" helper-text="Wajib melampirkan paspor dan izin tinggal." :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::radio
                    name="kewarganegaraan"
                    label="Warga negara Indonesia"
                    ⟦helper-text⟧="Wajib melampirkan KTP elektronik yang masih berlaku."
                    checked
                    ⟨dark-mode⟩
                />
                <x-ceplok::radio
                    name="kewarganegaraan"
                    label="Warga negara asing"
                    ⟦helper-text⟧="Wajib melampirkan paspor dan izin tinggal."
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>
                Platform mobile memakai lingkaran 14px dengan label 12px; ukuran caption tetap 12px di keduanya.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Desktop · 16px">
                    <div class="space-y-3">
                        @foreach ($kewarganegaraan as $o)
                            <x-ceplok::radio name="ds-radio-desktop" :label="$o['label']" :checked="$o['value'] === 'wni'" :dark-mode="$docsDark" />
                        @endforeach
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile · 14px">
                    <div class="space-y-3">
                        @foreach ($kewarganegaraan as $o)
                            <x-ceplok::radio platform="mobile" name="ds-radio-mobile" :label="$o['label']" :checked="$o['value'] === 'wni'" :dark-mode="$docsDark" />
                        @endforeach
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Desktop — platform bawaan, tanpa prop --}}
                <x-ceplok::radio name="wn-desktop" label="Warga negara Indonesia" checked⟨ dark-mode⟩ />
                <x-ceplok::radio name="wn-desktop" label="Warga negara asing"⟨ dark-mode⟩ />

                {{-- Mobile --}}
                <x-ceplok::radio ⟦platform⟧="mobile" name="wn-mobile" label="Warga negara Indonesia" checked⟨ dark-mode⟩ />
                <x-ceplok::radio ⟦platform⟧="mobile" name="wn-mobile" label="Warga negara asing"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna cincin saat dipilih mengikuti aplikasi yang memakainya. Lingkaran yang belum dipilih —
                gray-50 bergaris gray-300 — sama di kedua aplikasi.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <div class="space-y-3">
                            <x-ceplok::radio :name="'ds-radio-'.$a['value']" :application="$a['value']" label="Dipilih" :helper-text="'border-'.$a['token']" checked :dark-mode="$docsDark" />
                            <x-ceplok::radio :name="'ds-radio-'.$a['value']" :application="$a['value']" label="Belum dipilih" :dark-mode="$docsDark" />
                        </div>
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                {{-- Default — tanpa prop application --}}
                <x-ceplok::radio name="pilihan-default" label="Dipilih" helper-text="border-primary-700" checked⟨ dark-mode⟩ />
                <x-ceplok::radio name="pilihan-default" label="Belum dipilih"⟨ dark-mode⟩ />

                {{-- Purple --}}
                <x-ceplok::radio
                    name="pilihan-purple"
                    ⟦application⟧="purple"
                    label="Dipilih"
                    helper-text="border-purple-500"
                    checked
                    ⟨dark-mode⟩
                />
                <x-ceplok::radio name="pilihan-purple" ⟦application⟧="purple" label="Belum dipilih"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section name="Radio">
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti lingkaran ke gray-300 bergaris gray-400, dengan label terang dan
                caption gray-400. Cincin pilihan aktif tetap memakai warna aplikasi. State <span class="text-primary-500 dark:text-primary-400">inactive</span> memakai
                lingkaran gray-700 bergaris gray-800 dengan teks gray-600.
            </x-slot:lead>
            <x-docs.demo :dark="! $docsDark" :light="$docsDark">
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <x-ceplok::radio :dark-mode="! $docsDark" name="ds-radio-gelap" label="Belum dipilih" helper-text="Keterangan singkat." />
                    <x-ceplok::radio :dark-mode="! $docsDark" name="ds-radio-gelap" label="Sedang dipilih" helper-text="Keterangan singkat." checked />
                    <x-ceplok::radio :dark-mode="! $docsDark" name="ds-radio-gelap-nonaktif" state="inactive" label="Tidak aktif" helper-text="Keterangan singkat." />
                    <x-ceplok::radio :dark-mode="! $docsDark" name="ds-radio-gelap-nonaktif-terpilih" state="inactive" label="Tidak aktif, terpilih" helper-text="Keterangan singkat." checked />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::radio⟨! ⟦dark-mode⟧⟩ name="pilihan-gelap" label="Belum dipilih" helper-text="Keterangan singkat." />
                <x-ceplok::radio
                    ⟨!⟦dark-mode⟧⟩
                    name="pilihan-gelap"
                    label="Sedang dipilih"
                    helper-text="Keterangan singkat."
                    checked
                />

                {{-- Inactive --}}
                <x-ceplok::radio
                    ⟨!⟦dark-mode⟧⟩
                    name="pilihan-gelap-nonaktif"
                    state="inactive"
                    label="Tidak aktif"
                    helper-text="Keterangan singkat."
                />
                <x-ceplok::radio
                    ⟨!⟦dark-mode⟧⟩
                    name="pilihan-gelap-nonaktif"
                    state="inactive"
                    label="Tidak aktif, terpilih"
                    helper-text="Keterangan singkat."
                    checked
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.radio :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Pasang <span class="text-primary-500 dark:text-primary-400">wire:model</span> pada setiap Radio sekelompok, masing-masing dengan <span class="text-primary-500 dark:text-primary-400">value</span>-nya.
                Radio yang value-nya sama dengan properti itu langsung tercentang di HTML pertama, tanpa menunggu
                JavaScript. Dengan <span class="text-primary-500 dark:text-primary-400">wire:model.live</span>, pilihan langsung sampai ke server — di bawah, daftar
                dokumennya ikut berganti.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.radio-livewire :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::radio name="kewarganegaraan" ⟦value⟧="wni" label="Warga negara Indonesia" ⟦wire:model.live⟧="kewarganegaraan"⟨ dark-mode⟩ />
                <x-ceplok::radio name="kewarganegaraan" ⟦value⟧="wna" label="Warga negara asing" ⟦wire:model.live⟧="kewarganegaraan"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="text-primary-500 dark:text-primary-400">&lt;input type="radio"&gt;</span> standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="48rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
