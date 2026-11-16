@php
    use App\Docs\Theme;

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-700'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-700'],
    ];

    $props = [
        ['label', 'string | slot', 'null', 'Teks label di atas kotak.'],
        ['hint', 'string | slot', 'null', 'Teks kecil di kanan label, mis. penghitung karakter.'],
        ['helper-text', 'string | slot', 'null', 'Caption di bawah kotak.'],
        ['type', '"default" | "editor"', 'default', 'Kotak polos, atau kotak dengan toolbar di atasnya.'],
        ['platform', '"default" | "mobile"', 'default', 'Tinggi kotak type default: 162px atau 120px.'],
        ['application', '"default" | "purple"', 'default', 'Warna garis saat difokus dan tombol kirim.'],
        ['toolbar', 'slot', 'null', 'Mengganti isi toolbar editor dengan elemen sendiri.'],
        ['wire:toolbar-action', 'event', '—', 'Dipicu saat tombol toolbar bawaan ditekan; detail.action berisi attachment, code, emoji, list, settings, date, atau download. Padanan onToolbarAction.'],
        ['submit-label', 'string | slot', 'null', 'Label tombol kirim; tombol hanya muncul bila diisi.'],
        ['wire:submit', 'event', '—', 'Dipicu saat tombol kirim ditekan; padanan onSubmit. Juga bisa x-on:submit.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: kotak dan toolbar gray-700, label putih.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <textarea> standar diteruskan (rows, maxlength, name, wire:model, …); value menjadi isi awalnya. class dan wire:key ke pembungkus terluar.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`,
    // cuplikannya menuliskannya lewat penanda ⟨…⟩, dan bagian Dark mode
    // bertukar menjadi Light mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Text Area">
    <x-docs.page
        eyebrow="Form · Input Field Form"
        title="Text Area"
        description="Isian teks banyak baris. Tersedia sebagai kotak polos atau sebagai editor dengan toolbar dan tombol kirim, memakai token warna, radius, dan jarak yang sama dengan Foundations. Tersedia juga dalam tampilan gelap."
        :toc="[
            'text-area' => 'Text Area',
            'editor' => 'Editor',
            'application' => 'Application',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="text-area" title="Text Area">
            <x-docs.lead>
                Kotak polos untuk keterangan panjang: label di atas, kotak berlatar abu muda, lalu caption di
                bawah. Tingginya mengikuti platform — 162px di desktop, 120px di mobile.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Desktop · 162px">
                    <x-ceplok::text-area label="Keterangan" placeholder="Tulis keterangan tambahan…" helper-text="Maksimal 500 karakter." :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Mobile · 120px">
                    <x-ceplok::text-area platform="mobile" label="Keterangan" placeholder="Tulis keterangan tambahan…" helper-text="Maksimal 500 karakter." :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::text-area
                    label="Keterangan"
                    placeholder="Tulis keterangan tambahan…"
                    helper-text="Maksimal 500 karakter."
                    ⟨dark-mode⟩
                />

                {{-- Mobile — kotak 120px --}}
                <x-ceplok::text-area ⟦platform⟧="mobile" label="Keterangan"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="editor" title="Editor">
            <x-docs.lead>
                Type <span class="text-primary-500 dark:text-primary-400">editor</span> menambahkan toolbar setinggi 40px di atas area isian — tujuh alat dengan garis
                pemisah setelah alat ketiga. Teks di kanan label bisa dipakai sebagai penghitung karakter, dan
                tombol kirim muncul begitu <span class="text-primary-500 dark:text-primary-400">submit-label</span> diisi.
            </x-docs.lead>
            <x-docs.demo>
                <x-ceplok::text-area type="editor" label="Isi pesan" hint="0/500" placeholder="Tulis pesan Anda…" submit-label="Submit Text" :dark-mode="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::text-area
                    ⟦type⟧="editor"
                    label="Isi pesan"
                    :hint="strlen($pesan).'/500'"
                    placeholder="Tulis pesan Anda…"
                    ⟦submit-label⟧="Submit Text"
                    wire:submit="kirim"
                    x-on:toolbar-action="console.log($event.detail.action)"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna tombol kirim — dan garis kotak saat difokus — mengikuti aplikasi yang memakainya, dengan
                token warna yang sama seperti pada Input Field.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <x-ceplok::text-area
                            type="editor"
                            :application="$a['value']"
                            label="Isi pesan"
                            placeholder="Tulis pesan Anda…"
                            :helper-text="'bg-'.$a['token']"
                            submit-label="Submit Text"
                            :dark-mode="$docsDark"
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::text-area
                    type="editor"
                    ⟦application⟧="purple"
                    label="Isi pesan"
                    submit-label="Submit Text"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section name="Text Area">
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti kotak, toolbar, dan area isian ke gray-700, dengan label dan teks
                yang diketik putih. Kotak polos tidak lagi bergaris, sedangkan bingkai editor dan garis pemisah
                toolbarnya menjadi gray-600. Tombol kirim memakai -600 (hover -700), satu tingkat lebih terang dari
                tampilan terang.
            </x-slot:lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Default" :dark="! $docsDark" :light="$docsDark">
                    <x-ceplok::text-area :dark-mode="! $docsDark" label="Keterangan" placeholder="Tulis keterangan tambahan…" helper-text="Maksimal 500 karakter." />
                </x-docs.demo>
                <x-docs.demo label="Editor" :dark="! $docsDark" :light="$docsDark">
                    <x-ceplok::text-area :dark-mode="! $docsDark" type="editor" label="Isi pesan" hint="Opsional" placeholder="Tulis pesan Anda…" submit-label="Submit Text" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::text-area
                    ⟨!⟦dark-mode⟧⟩
                    type="editor"
                    label="Isi pesan"
                    submit-label="Submit Text"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.text-area :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Tombol kirim dan tombol toolbar memicu event dari elemen textarea-nya, jadi cukup pasang
                <span class="text-primary-500 dark:text-primary-400">wire:submit</span> dan <span class="text-primary-500 dark:text-primary-400">wire:toolbar-action</span> pada tag komponennya. Tulis pesan lalu tekan
                Kirim, atau coba salah satu alat di toolbar.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.text-area-send :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::text-area
                    type="editor"
                    label="Isi pesan"
                    wire:model="pesan"
                    submit-label="Kirim"
                    ⟦wire:submit⟧="kirim"
                    ⟦wire:toolbar-action⟧="pakaiAlat($event.detail.action)"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="text-primary-500 dark:text-primary-400">&lt;textarea&gt;</span> standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="48rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
