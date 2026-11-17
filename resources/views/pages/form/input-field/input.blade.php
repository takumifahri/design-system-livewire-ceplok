@php
    use App\Docs\Theme;

    $states = [
        ['value' => 'default', 'label' => 'Default', 'desc' => 'Belum disentuh — garis abu-abu netral.'],
        ['value' => 'typing', 'label' => 'Typing', 'desc' => 'Sedang diisi/difokus — garis memakai warna aplikasi.'],
        ['value' => 'inactive', 'label' => 'Inactive', 'desc' => 'Tidak bisa diisi; seluruh teks meredup.'],
        ['value' => 'failed', 'label' => 'Failed', 'desc' => 'Isian ditolak validasi — latar dan teks merah.'],
    ];

    $applications = [
        ['value' => 'default', 'label' => 'Default', 'token' => 'primary-500'],
        ['value' => 'purple', 'label' => 'Purple', 'token' => 'purple-500'],
    ];

    // Contoh password: desktop dan mobile, lalu failed. Bagian Dark mode memakai daftar yang sama, tanpa mobile.
    $passwordDemos = [
        ['label' => 'Desktop', 'platform' => 'default', 'state' => 'default'],
        ['label' => 'Mobile', 'platform' => 'mobile', 'state' => 'default'],
        ['label' => 'Failed', 'platform' => 'default', 'state' => 'failed'],
    ];

    $user = svg('ceplok-s-user', 'size-4');

    $props = [
        ['label', 'string | slot', 'null', 'Teks label di atas field.'],
        ['helper-text', 'string | slot', 'null', 'Caption di bawah field; jadi pesan error saat state failed.'],
        ['icon', 'Htmlable | slot | false', 'null', 'Ikon di sisi kiri field. Pada type password bawaannya gembok; :icon="false" menghilangkannya.'],
        ['type', '"text" | "password" | …', 'text', '"password" menyamarkan isian, memasang ikon gembok, dan menambahkan tombol mata untuk menampilkannya.'],
        ['platform', '"default" | "mobile"', 'default', 'Tinggi field: 52px (default) atau 40px (mobile).'],
        ['state', '"default" | "typing" | "inactive" | "failed"', 'default', 'Kondisi visual field. inactive menonaktifkan input, failed memasang aria-invalid.'],
        ['application', '"default" | "purple"', 'default', 'Warna garis aksen saat field aktif.'],
        ['clearable', 'bool', 'false', 'Menampilkan tombol hapus (×) di kanan field yang mengosongkan isian.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: field gray-700, label putih, garis baru terlihat saat typing dan failed.'],
        ['wire:clear', 'event', '—', 'Dipicu setelah tombol hapus mengosongkan isian; padanan onClear. Juga bisa x-on:clear.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <input> standar diteruskan (name, wire:model, autocomplete, …). class dan wire:key ke pembungkus terluar.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`,
    // cuplikannya menuliskannya lewat penanda ⟨…⟩, dan bagian Dark mode
    // bertukar menjadi Light mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Input Field">
    <x-docs.page
        eyebrow="Form · Input Field Form"
        title="Input Field"
        description="Isian teks satu baris dengan label di atas field. Tinggi, warna, dan jaraknya memakai token yang sama dengan Foundations. Tersedia juga versi kata sandi dan tampilan gelap."
        :toc="[
            'input-field' => 'Input Field',
            'states' => 'States',
            'application' => 'Application',
            'password' => 'Password',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="input-field" title="Input Field">
            <x-docs.lead>
                Label di atas, field dengan ikon opsional dan tombol hapus, lalu caption di bawah. Tinggi field
                mengikuti platform — 52px di desktop, 40px di mobile.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Desktop · 52px">
                    <x-ceplok::input-field label="Nama lengkap" placeholder="Masukkan nama lengkap" helper-text="Sesuai yang tertera pada KTP." :icon="$user" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Mobile · 40px">
                    <x-ceplok::input-field platform="mobile" label="Nama lengkap" placeholder="Masukkan nama lengkap" helper-text="Sesuai yang tertera pada KTP." :icon="$user" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::input-field
                    label="Nama lengkap"
                    placeholder="Masukkan nama lengkap"
                    helper-text="Sesuai yang tertera pada KTP."
                    :icon="svg('ceplok-s-user', 'size-4')"
                    ⟨dark-mode⟩
                />

                {{-- Mobile — field 40px --}}
                <x-ceplok::input-field ⟦platform⟧="mobile" label="Nama lengkap"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="states" title="States">
            <x-docs.lead>
                Empat kondisi visual field. <span class="text-primary-500 dark:text-primary-400">inactive</span> otomatis menonaktifkan input dan <span class="text-primary-500 dark:text-primary-400">failed</span>
                menandainya <span class="text-primary-500 dark:text-primary-400">aria-invalid</span>, jadi tampilan dan makna aksesibilitasnya selalu sejalan.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($states as $s)
                    <x-docs.demo :label="$s['label']">
                        <x-ceplok::input-field
                            :state="$s['value']"
                            label="Nama lengkap"
                            placeholder="Masukkan nama lengkap"
                            :helper-text="$s['value'] === 'failed' ? 'Nama lengkap wajib diisi.' : $s['desc']"
                            :icon="$user"
                            :dark-mode="$docsDark"
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                {{-- Pesan error — failed sekaligus menandai aria-invalid --}}
                <x-ceplok::input-field
                    ⟦state⟧="failed"
                    label="Nama lengkap"
                    helper-text="Nama lengkap wajib diisi."
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="application" title="Application">
            <x-docs.lead>
                Warna garis saat field aktif mengikuti aplikasi yang memakainya — memakai token warna yang sama
                dengan palet Foundations.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($applications as $a)
                    <x-docs.demo :label="$a['label']">
                        <x-ceplok::input-field
                            :application="$a['value']"
                            state="typing"
                            label="Nama lengkap"
                            placeholder="Masukkan nama lengkap"
                            :helper-text="'border-'.$a['token']"
                            :icon="$user"
                            :dark-mode="$docsDark"
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::input-field
                    ⟦application⟧="purple"
                    state="typing"
                    label="Nama lengkap"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="password" title="Password">
            <x-docs.lead>
                Dengan <span class="text-primary-500 dark:text-primary-400">type="password"</span> isiannya disamarkan, ikon gembok tampil di kiri, dan tombol mata di
                kanan menampilkan atau menyembunyikan kata sandi. Gembok bisa diganti lewat <span class="text-primary-500 dark:text-primary-400">icon</span>, atau
                dihilangkan dengan <span class="text-primary-500 dark:text-primary-400">:icon="false"</span>. Sesuai desainnya, di mobile label, isian, dan caption-nya
                12px. Saat <span class="text-primary-500 dark:text-primary-400">failed</span>, garis, latar, gembok, dan caption-nya memerah seperti type teks, tetapi
                tombol matanya tetap abu-abu. Isi <span class="text-primary-500 dark:text-primary-400">autocomplete</span> sesuai pemakaiannya —
                <span class="text-primary-500 dark:text-primary-400">current-password</span> untuk masuk, <span class="text-primary-500 dark:text-primary-400">new-password</span> untuk mendaftar — supaya pengelola kata
                sandi browser ikut bekerja.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($passwordDemos as $d)
                    <x-docs.demo :label="$d['label']">
                        <x-ceplok::input-field
                            type="password"
                            :platform="$d['platform']"
                            :state="$d['state']"
                            label="Kata sandi"
                            placeholder="••••••••"
                            :helper-text="$d['state'] === 'failed' ? 'Kata sandi minimal 8 karakter.' : 'Gunakan minimal 8 karakter.'"
                            autocomplete="current-password"
                            :dark-mode="$docsDark"
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::input-field
                    ⟦type⟧="password"
                    label="Kata sandi"
                    placeholder="••••••••"
                    helper-text="Gunakan minimal 8 karakter."
                    autocomplete="current-password"
                    ⟨dark-mode⟩
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section name="Input Field">
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti field ke gray-700 dengan label putih. Garisnya menyatu dengan latar
                dan baru terlihat saat <span class="text-primary-500 dark:text-primary-400">typing</span> (warna aplikasi) atau <span class="text-primary-500 dark:text-primary-400">failed</span> (red-500). Teks yang
                diketik putih, sedangkan placeholder, ikon, dan caption abu-abu. Tombol hapus (×) dan tombol mata
                gray-400. Pada type password saat <span class="text-primary-500 dark:text-primary-400">failed</span>, gembok, placeholder, serta caption-nya red-500.
            </x-slot:lead>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($states as $s)
                    <x-docs.demo :label="$s['label']" :dark="! $docsDark" :light="$docsDark">
                        <x-ceplok::input-field
                            :dark-mode="! $docsDark"
                            :state="$s['value']"
                            label="Nama lengkap"
                            placeholder="Masukkan nama lengkap"
                            :helper-text="$s['value'] === 'failed' ? 'Nama lengkap wajib diisi.' : $s['desc']"
                            :icon="$user"
                            clearable
                        />
                    </x-docs.demo>
                @endforeach
                @foreach ($passwordDemos as $d)
                    @continue($d['platform'] === 'mobile')
                    <x-docs.demo :label="'Password · '.$d['label']" :dark="! $docsDark" :light="$docsDark">
                        <x-ceplok::input-field
                            type="password"
                            :dark-mode="! $docsDark"
                            :platform="$d['platform']"
                            :state="$d['state']"
                            label="Kata sandi"
                            placeholder="••••••••"
                            :helper-text="$d['state'] === 'failed' ? 'Kata sandi minimal 8 karakter.' : 'Gunakan minimal 8 karakter.'"
                            autocomplete="current-password"
                        />
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::input-field
                    ⟨!⟦dark-mode⟧⟩
                    label="Nama lengkap"
                    placeholder="Masukkan nama lengkap"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.input-field :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Pasang <span class="text-primary-500 dark:text-primary-400">wire:model</span> langsung pada tag komponennya; atribut itu diteruskan ke
                <span class="text-primary-500 dark:text-primary-400">&lt;input&gt;</span>, dan nilai awal propertinya sudah dirender server. Hasil validasi tinggal
                dipetakan ke <span class="text-primary-500 dark:text-primary-400">state="failed"</span> dan <span class="text-primary-500 dark:text-primary-400">helper-text</span>. Kosongkan isian lalu tekan Simpan untuk
                melihatnya.
            </x-docs.lead>
            <x-docs.example>
                <livewire:playground.input-field-validate :dark="$docsDark" />
            </x-docs.example>
            <x-docs.code>
                @verbatim
                <form wire:submit="simpan" class="space-y-4">
                    <x-ceplok::input-field
                        label="Nama lengkap"
                        ⟦wire:model⟧="nama"
                        :⟦state⟧="$errors->has('nama') ? 'failed' : 'default'"
                        :⟦helper-text⟧="$errors->first('nama') ?: 'Sesuai yang tertera pada KTP.'"
                        clearable
                        ⟨dark-mode⟩
                    />
                    <x-ceplok::button type="submit">Simpan</x-ceplok::button>
                </form>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut <span class="text-primary-500 dark:text-primary-400">&lt;input&gt;</span>
                standar juga diteruskan apa adanya.
            </x-docs.lead>
            <div class="[&_td:first-child]:whitespace-nowrap">
                <x-docs.props-table :rows="$props" min-width="46rem" />
            </div>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
