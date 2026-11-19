@php
    use App\Docs\Theme;

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-500'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    // Contoh isi dropdown — dipakai berulang di seluruh halaman.
    $provinsi = [
        ['value' => 'dki', 'label' => 'DKI Jakarta'],
        ['value' => 'jabar', 'label' => 'Jawa Barat'],
        ['value' => 'jateng', 'label' => 'Jawa Tengah'],
        ['value' => 'jatim', 'label' => 'Jawa Timur'],
    ];

    // 38 provinsi, urut abjad — contoh daftar panjang untuk pencarian lewat ketikan.
    $semuaProvinsi = [
        'aceh' => 'Aceh', 'bali' => 'Bali', 'banten' => 'Banten', 'bengkulu' => 'Bengkulu',
        'diy' => 'DI Yogyakarta', 'dki' => 'DKI Jakarta', 'gorontalo' => 'Gorontalo', 'jambi' => 'Jambi',
        'jabar' => 'Jawa Barat', 'jateng' => 'Jawa Tengah', 'jatim' => 'Jawa Timur', 'kalbar' => 'Kalimantan Barat',
        'kalsel' => 'Kalimantan Selatan', 'kalteng' => 'Kalimantan Tengah', 'kaltim' => 'Kalimantan Timur', 'kaltara' => 'Kalimantan Utara',
        'babel' => 'Kepulauan Bangka Belitung', 'kepri' => 'Kepulauan Riau', 'lampung' => 'Lampung', 'maluku' => 'Maluku',
        'malut' => 'Maluku Utara', 'ntb' => 'Nusa Tenggara Barat', 'ntt' => 'Nusa Tenggara Timur', 'papua' => 'Papua',
        'pabar' => 'Papua Barat', 'pbd' => 'Papua Barat Daya', 'papeg' => 'Papua Pegunungan', 'pasel' => 'Papua Selatan',
        'pateng' => 'Papua Tengah', 'riau' => 'Riau', 'sulbar' => 'Sulawesi Barat', 'sulsel' => 'Sulawesi Selatan',
        'sulteng' => 'Sulawesi Tengah', 'sultra' => 'Sulawesi Tenggara', 'sulut' => 'Sulawesi Utara', 'sumbar' => 'Sumatera Barat',
        'sumsel' => 'Sumatera Selatan', 'sumut' => 'Sumatera Utara',
    ];

    // Ditulis di blok PHP ini karena teks <select> di nilai atribut ikut dibaca kompiler komponen.
    $description = 'Dropdown satu pilihan dengan label, ikon info, dan caption. Daftar pilihannya panel Dropdown yang sama dengan menu lain di kit ini, sedangkan nilainya tetap dibawa elemen <select> — jadi formulir, keyboard, dan pembaca layar bekerja apa adanya. Tersedia juga dalam tampilan gelap.';

    $props = [
        ['label', 'string | slot', 'null', 'Teks label di atas field.'],
        ['info', 'string', 'null', 'Keterangan pada ikon info di samping label; ikon muncul bila diisi.'],
        ['helper-text', 'string | slot', 'null', 'Caption di bawah field.'],
        ['placeholder', 'string', 'null', 'Teks saat belum ada pilihan, mis. "Pilih Apapun Itu". Menjadi baris pertama bernilai kosong.'],
        ['options', 'array', 'null', 'Daftar pilihan [value, label, disabled], pasangan nilai => label, atau kelompok [label, options]. Bila kosong, <option> dan <optgroup> di slot yang dipakai.'],
        ['value', 'string', 'null', 'Pilihan awal. Di dalam Livewire pilihan awal wire:model dibaca dari propertinya.'],
        ['state', '"default" | "inactive"', 'default', 'Inactive meredupkan tampilan sekaligus menonaktifkan kontrol.'],
        ['application', '"default" | "purple"', 'default', 'Warna ikon info dan garis saat difokus.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: field gray-700 dengan label terang; garisnya hanya terlihat saat inactive dan saat difokus. Panel daftar pilihannya tetap terang.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <select> standar diteruskan (name, required, wire:model, …). class dan wire:key ke pembungkus terluar.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Regular Select Form">
    <x-docs.page
        eyebrow="Form"
        title="Regular Select Form"
        :description="$description"
        :toc="[
            'select-input' => 'Select Input',
            'state' => 'State',
            'application' => 'Application',
            'daftar' => 'Daftar pilihan',
            'banyak-pilihan' => 'Banyak pilihan',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="select-input" title="Select Input">
            <x-docs.lead>
                Label di atas field, kotak setinggi 37px berlatar abu muda dengan panah di sisi kanan, lalu
                caption opsional di bawahnya. Ikon info di samping label muncul begitu prop <span class="text-primary-500 dark:text-primary-400">info</span> diisi.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Belum dipilih">
                    <x-ceplok::select label="Label" info="Pilih salah satu provinsi." placeholder="Pilih Apapun Itu" :options="$provinsi" helper-text="Wajib diisi." :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Sudah dipilih">
                    <x-ceplok::select label="Label" info="Pilih salah satu provinsi." placeholder="Pilih Apapun Itu" :options="$provinsi" value="jabar" helper-text="Wajib diisi." :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::select
                    label="Label"
                    ⟦info⟧="Pilih salah satu provinsi."
                    ⟦placeholder⟧="Pilih Apapun Itu"
                    :⟦options⟧="[['value' => 'dki', 'label' => 'DKI Jakarta']]"
                    helper-text="Wajib diisi."
                    ⟨dark-mode⟩
                />

                {{-- Tanpa prop options: pakai <option> sendiri --}}
                <x-ceplok::select label="Label" placeholder="Pilih Apapun Itu"⟨ dark-mode⟩>
                    <option value="dki">DKI Jakarta</option>
                </x-ceplok::select>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="state" title="State">
            <x-docs.lead>
                State <span class="text-primary-500 dark:text-primary-400">inactive</span> memakai latar gray-100 dengan teks gray-300, dan sekaligus menonaktifkan
                kontrolnya — jadi tampilan dan perilakunya tak mungkin berbeda.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Default">
                    <x-ceplok::select label="Label" info="Keterangan singkat." placeholder="Pilih Apapun Itu" :options="$provinsi" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Inactive">
                    <x-ceplok::select state="inactive" label="Label" info="Keterangan singkat." placeholder="Pilih Apapun Itu" :options="$provinsi" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::select ⟦state⟧="inactive" label="Label" placeholder="Pilih Apapun Itu" :options="$provinsi"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna ikon info — dan garis kotak saat difokus — mengikuti aplikasi yang memakainya, dengan token
                warna yang sama seperti pada Input Field.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <x-ceplok::select
                            :application="$a['value']"
                            label="Label"
                            info="Keterangan singkat."
                            placeholder="Pilih Apapun Itu"
                            :options="$provinsi"
                            :helper-text="'text-'.$a['token']"
                            :dark-mode="$docsDark"
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::select
                    ⟦application⟧="purple"
                    label="Label"
                    :options="$provinsi"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="daftar" title="Daftar pilihan">
            <x-docs.lead>
                Daftar pilihannya panel Dropdown — rupanya sama persis dengan menu Dropdown di kit ini, bukan
                popup milik sistem operasi. Pilihan ditelusuri dengan panah, Home/End, atau dengan mengetik
                namanya — lihat <span class="text-primary-500 dark:text-primary-400">Banyak pilihan</span> di bawah.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Lewat options">
                    <x-ceplok::select label="Provinsi" placeholder="Pilih provinsi" :options="$provinsi" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Lewat <option> dan <optgroup>">
                    <x-ceplok::select label="Provinsi" placeholder="Pilih provinsi" :dark-mode="$docsDark">
                        <optgroup label="Jawa">
                            <option value="jabar">Jawa Barat</option>
                            <option value="jateng">Jawa Tengah</option>
                            <option value="jatim">Jawa Timur</option>
                        </optgroup>
                        <optgroup label="Sumatera">
                            <option value="sumut">Sumatera Utara</option>
                            <option value="sumbar">Sumatera Barat</option>
                        </optgroup>
                    </x-ceplok::select>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::select label="Provinsi" placeholder="Pilih provinsi"⟨ dark-mode⟩>
                    ⟦<optgroup label="Jawa">⟧
                        <option value="jabar">Jawa Barat</option>
                        <option value="jateng">Jawa Tengah</option>
                    ⟦</optgroup>⟧
                    …
                </x-ceplok::select>
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Di balik panelnya tetap ada elemen <span class="text-primary-500 dark:text-primary-400">&lt;select&gt;</span> yang membawa nilainya, jadi
                <span class="text-primary-500 dark:text-primary-400">name</span>, <span class="text-primary-500 dark:text-primary-400">wire:model</span>, <span class="text-primary-500 dark:text-primary-400">x-model</span>, <span class="text-primary-500 dark:text-primary-400">required</span>, dan pengiriman formulir bekerja seperti
                pada <span class="text-primary-500 dark:text-primary-400">&lt;select&gt;</span> biasa. <span class="text-primary-500 dark:text-primary-400">&lt;option&gt;</span> yang Anda tulis sendiri dibaca komponen
                dan tampil di panel yang sama; label <span class="text-primary-500 dark:text-primary-400">&lt;optgroup&gt;</span> menjadi judul kelompoknya.
            </p>
        </x-docs.section>

        <x-docs.section id="banyak-pilihan" title="Banyak pilihan">
            <x-docs.lead>
                Untuk daftar panjang, buka panelnya lalu langsung ketik — fokus melompat ke pilihan yang
                diawali ketikan Anda, dan panel menggulir sendiri ke sana. Tekan Enter untuk memilihnya. Tidak
                perlu prop tambahan; pencarian ini bawaan setiap Select.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="38 provinsi">
                    <x-ceplok::select label="Provinsi" placeholder="Pilih provinsi" :options="$semuaProvinsi" helper-text="Buka, lalu ketik nama provinsinya." :dark-mode="$docsDark" />
                </x-docs.demo>
                <div class="space-y-3 text-body-sm text-gray-600 dark:text-gray-300">
                    <p class="font-bold text-gray-900 dark:text-white">Coba ketik:</p>
                    <ul class="space-y-2">
                        <li>
                            <span class="text-primary-500 dark:text-primary-400">jawa t</span> → Jawa Tengah. Ketikan dengan jeda di bawah satu detik digabung jadi satu
                            kata kunci, spasinya ikut dihitung.
                        </li>
                        <li>
                            <span class="text-primary-500 dark:text-primary-400">jakarta</span> → DKI Jakarta. Bila tidak ada pilihan yang diawali ketikan itu, yang dicari
                            awal salah satu katanya.
                        </li>
                        <li>
                            <span class="text-primary-500 dark:text-primary-400">bangka</span> → Kepulauan Bangka Belitung, dengan cara yang sama.
                        </li>
                        <li>
                            <span class="text-primary-500 dark:text-primary-400">s</span> berulang-ulang → berpindah dari satu provinsi berawalan S ke berikutnya.
                        </li>
                    </ul>
                </div>
            </div>
            <x-docs.code>
                @verbatim
                @php
                    $provinsi = [
                        'aceh' => 'Aceh',
                        'bali' => 'Bali',
                        // … 36 provinsi lainnya
                    ];
                @endphp

                <x-ceplok::select label="Provinsi" placeholder="Pilih provinsi" :⟦options⟧="$provinsi"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti field ke gray-700 dengan label terang. Pada state default garisnya
                menyatu dengan latar dan baru terlihat saat difokus (warna aplikasi); pada <span class="text-primary-500 dark:text-primary-400">inactive</span> garisnya
                gray-300, dengan teks dan panah gray-500. Panel daftar pilihannya untuk saat ini tetap terang.
            </x-slot:lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Default" dark>
                    <x-ceplok::select dark-mode label="Label" info="Keterangan singkat." placeholder="Pilih Apapun Itu" :options="$provinsi" helper-text="Wajib diisi." />
                </x-docs.demo>
                <x-docs.demo label="Inactive" dark>
                    <x-ceplok::select dark-mode state="inactive" label="Label" info="Keterangan singkat." placeholder="Pilih Apapun Itu" :options="$provinsi" helper-text="Wajib diisi." />
                </x-docs.demo>
                <x-docs.demo label="Sudah dipilih" dark>
                    <x-ceplok::select dark-mode label="Label" info="Keterangan singkat." placeholder="Pilih Apapun Itu" :options="$provinsi" value="jabar" helper-text="Wajib diisi." />
                </x-docs.demo>
                <x-docs.demo label="Purple" dark>
                    <x-ceplok::select dark-mode application="purple" label="Label" info="Keterangan singkat." placeholder="Pilih Apapun Itu" :options="$provinsi" helper-text="Wajib diisi." />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::select
                    ⟦dark-mode⟧
                    label="Label"
                    placeholder="Pilih Apapun Itu"
                    :options="$provinsi"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.select :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Pasang <span class="text-primary-500 dark:text-primary-400">wire:model</span> langsung pada tag komponennya; atribut itu diteruskan ke
                <span class="text-primary-500 dark:text-primary-400">&lt;select&gt;</span> tersembunyi, dan pilihan awal propertinya sudah dirender server. Daftar pilihannya
                boleh berganti dari server — mis. kabupaten/kota yang mengikuti provinsi — dan labelnya ikut terbaca
                dari opsi terbaru.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.select-region :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::select
                    label="Provinsi"
                    placeholder="Pilih provinsi"
                    ⟦wire:model.live⟧="provinsi"
                    :options="$daftarProvinsi"
                    ⟨dark-mode⟩
                />
                <x-ceplok::select
                    label="Kabupaten/Kota"
                    placeholder="Pilih kabupaten/kota"
                    wire:model="kota"
                    :⟦options⟧="$this->daftarKota"
                    :state="$provinsi ? 'default' : 'inactive'"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut <span class="text-primary-500 dark:text-primary-400">&lt;select&gt;</span>
                standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="48rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
