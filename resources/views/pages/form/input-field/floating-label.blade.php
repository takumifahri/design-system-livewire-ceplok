@php
    $states = [
        ['value' => 'default', 'label' => 'Default', 'desc' => 'Field kosong — label duduk di dalam field sebagai placeholder.'],
        ['value' => 'active', 'label' => 'Active', 'desc' => 'Sedang difokus/berisi — label naik ke garis atas dengan warna aksen.'],
        ['value' => 'error', 'label' => 'Error', 'desc' => 'Isian ditolak validasi — garis dan label merah.'],
    ];

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-600'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    $user = svg('ceplok-s-user', 'size-4');

    $props = [
        ['label', 'string | slot', '— (wajib)', 'Teks label; turun ke dalam field saat kosong, naik ke garis saat diisi.'],
        ['helper-text', 'string | slot', 'null', 'Caption di bawah field; jadi pesan kesalahan saat state error.'],
        ['icon', 'Htmlable | slot', 'null', 'Ikon di sisi kiri field.'],
        ['platform', '"default" | "mobile"', 'default', 'Tinggi field: 58px (default) atau 50px (mobile).'],
        ['state', '"default" | "active" | "error"', 'default', 'Mengunci tampilan label yang naik; error sekaligus memasang aria-invalid.'],
        ['application', '"default" | "purple"', 'default', 'Warna garis dan label saat field aktif.'],
        ['placeholder', 'string', 'null', 'Hanya muncul setelah label naik, agar tak bertumpuk dengan label.'],
        ['clearable', 'bool', 'false', 'Menampilkan tombol hapus (×) di kanan field yang mengosongkan isian.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: kotak gray-700 bergaris gray-600 dengan teks dan ikon gray-400.'],
        ['wire:clear', 'event', '—', 'Dipicu setelah tombol hapus mengosongkan isian; padanan onClear. Juga bisa x-on:clear.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <input> standar diteruskan (type, name, wire:model, …). class dan wire:key ke pembungkus terluar.'],
    ];
@endphp

<x-docs.layout title="Floating Label">
    <x-docs.page
        eyebrow="Form · Input Field Form"
        title="Floating Label"
        description="Isian teks yang labelnya naik ke garis atas begitu field difokus atau berisi — menghemat ruang tanpa menghilangkan keterangan field. Tersedia juga dalam tampilan gelap."
        :toc="[
            'floating-label' => 'Floating Label',
            'states' => 'States',
            'application' => 'Application',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="floating-label" title="Floating Label">
            <x-docs.lead>
                Saat kosong, label duduk di dalam field dan berperan sebagai placeholder. Begitu field difokus
                atau berisi, label menyusut ke 12px dan naik menimpa garis atas. Tinggi field mengikuti platform —
                58px di desktop, 50px di mobile.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Desktop · 58px">
                    <div class="space-y-5">
                        <x-ceplok::floating-label label="Placeholder Text" :icon="$user" clearable />
                        <x-ceplok::floating-label label="Placeholder Text" value="Text Input" state="active" :icon="$user" clearable />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Mobile · 50px">
                    <div class="space-y-5">
                        <x-ceplok::floating-label platform="mobile" label="Placeholder Text" :icon="$user" clearable />
                        <x-ceplok::floating-label platform="mobile" label="Placeholder Text" value="Text Input" state="active" :icon="$user" clearable />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Label naik sendiri saat difokus atau berisi --}}
                <x-ceplok::floating-label
                    label="Nama lengkap"
                    :icon="svg('ceplok-s-user', 'size-4')"
                />

                {{-- Mobile — field 50px --}}
                <x-ceplok::floating-label ⟦platform⟧="mobile" label="Nama lengkap" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="states" title="States">
            <x-docs.lead>
                Tiga kondisi visual field. Klik salah satu field untuk melihat label naik sendiri — <span class="text-primary-500">state</span>
                hanya perlu diisi untuk mengunci tampilan atau menandai kesalahan. State <span class="text-primary-500">error</span> memasang
                <span class="text-primary-500">aria-invalid</span> pada input.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($states as $s)
                    <x-docs.demo :label="$s['label']">
                        <x-ceplok::floating-label
                            :state="$s['value']"
                            label="Placeholder Text"
                            :value="$s['value'] === 'default' ? null : 'Text Input'"
                            :helper-text="$s['value'] === 'error' ? 'Isian tidak dapat diproses.' : $s['desc']"
                            :icon="$user"
                            clearable
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                {{-- Pesan kesalahan — error sekaligus menandai aria-invalid --}}
                <x-ceplok::floating-label
                    ⟦state⟧="error"
                    label="Nama lengkap"
                    helper-text="Isian tidak dapat diproses."
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Garis dan label saat field aktif mengikuti aplikasi yang memakainya, dengan token warna yang sama
                seperti pada Input Field.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <x-ceplok::floating-label
                            :application="$a['value']"
                            state="active"
                            label="Placeholder Text"
                            value="Text Input"
                            :helper-text="'border-'.$a['token']"
                            :icon="$user"
                            clearable
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::floating-label
                    ⟦application⟧="purple"
                    state="active"
                    label="Nama lengkap"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Prop <span class="text-primary-500">dark-mode</span> mengganti kotak ke gray-700 bergaris gray-600, dengan teks, ikon, dan tombol hapus
                gray-400. Garis aksen tetap sama, tetapi label yang naik satu tingkat lebih terang — primary-500 —
                supaya terbaca di latar gelap. Pada <span class="text-primary-500">error</span>, hanya garis dan label yang berubah merah.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($states as $s)
                    <x-docs.demo :label="$s['label']" dark>
                        <x-ceplok::floating-label
                            dark-mode
                            :state="$s['value']"
                            label="Placeholder Text"
                            :value="$s['value'] === 'default' ? null : 'Text Input'"
                            :helper-text="$s['value'] === 'error' ? 'Isian tidak dapat diproses.' : $s['desc']"
                            :icon="$user"
                            clearable
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::floating-label ⟦dark-mode⟧ label="Nama lengkap" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.floating-label />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Keterisian field ikut berubah saat nilainya diganti dari server, bukan hanya saat diketik: begitu
                properti <span class="text-primary-500">wire:model</span> terisi, labelnya naik sendiri. Di render pertama pun nilai awalnya
                sudah dirender server, jadi label langsung berada di posisi yang benar.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.floating-label-fill />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <x-ceplok::floating-label label="Kota domisili" ⟦wire:model⟧="kota" clearable />

                <x-ceplok::button wire:click="$set('kota', 'Bandung')">Isi dari profil</x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut <span class="text-primary-500">&lt;input&gt;</span>
                standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="48rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
