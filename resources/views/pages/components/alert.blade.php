@php
    use App\Docs\Theme;

    $variants = ['success', 'danger', 'warning', 'info', 'purple'];
    $body = 'Ini merupakan Design system Ceplok berupa component alert. Ini merupakan Design system Ceplok berupa component alert.';

    $props = [
        ['variant', '"success" | "danger" | "warning" | "info" | "purple"', 'info', 'Warna semantik pesan.'],
        ['heading', 'string | slot', 'null', 'Judul singkat di baris pertama.'],
        ['icon', 'Htmlable | slot | false', 'null', 'Ikon kiri kustom. false menyembunyikannya, kosong memakai bawaan variant.'],
        ['dismissible', 'bool', 'true', 'Menampilkan tombol tutup (×) di kanan atas.'],
        ['open', 'bool', 'true', 'Tampil sejak awal. Bisa dikendalikan dari luar lewat wire:model atau x-model.'],
        ['actions', 'slot', 'null', 'Baris tombol tindak lanjut di bawah isi.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: latar -900 variant, ikon -500, heading dan isi pesan -50.'],
        ['wire:dismiss', 'event', '—', 'Dipicu saat tombol tutup diklik; padanan onDismiss. Juga bisa x-on:dismiss.'],
        ['slot', 'slot', '—', 'Isi pesan.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <div> diteruskan (class, id, …).'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`,
    // cuplikannya menuliskannya lewat penanda ⟨…⟩, dan bagian Dark mode
    // bertukar menjadi Light mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Alert">
    <x-docs.page
        title="Alert"
        description="Menyampaikan pesan status — sukses, error, peringatan, atau informasi — tepat di dalam alur halaman. Lima variant warna dengan elemen yang sepenuhnya opsional. Tersedia juga dalam tampilan gelap."
        :toc="[
            'alert' => 'Alert',
            'opsional' => 'Elemen opsional',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="alert" title="Alert">
            <x-docs.lead>
                Kotak beradius 8px berisi ikon status, judul, isi pesan, dan tombol tutup. Lima variant warnanya
                punya makna semantik yang sama dengan Badge dan token warna lain di design system ini.
            </x-docs.lead>
            <div class="space-y-4">
                @foreach ($variants as $variant)
                    <x-ceplok::alert :variant="$variant" heading="Ini adalah Alert" :dark-mode="$docsDark">
                        {{ $body }}
                        <x-slot:actions>
                            <x-docs.alert-action :variant="$variant" :dark="$docsDark" />
                        </x-slot:actions>
                    </x-ceplok::alert>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::alert
                    ⟦variant⟧="success"
                    heading="Ini adalah Alert"
                    ⟨dark-mode⟩
                >
                    Ini merupakan Design system Ceplok berupa component alert.
                    <x-slot:actions>
                        <button type="button" class="…">Button text</button>
                    </x-slot:actions>
                </x-ceplok::alert>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="opsional" title="Elemen opsional">
            <x-docs.lead>
                Heading, tombol aksi, ikon kiri, dan tombol tutup semuanya opsional — cukup hilangkan bagian yang
                tidak dipakai. Yang tersisa paling ringkas adalah satu baris teks.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Tanpa tombol">
                    <x-ceplok::alert variant="warning" heading="Ini adalah Alert" :dark-mode="$docsDark">{{ $body }}</x-ceplok::alert>
                </x-docs.demo>
                <x-docs.demo label="Tanpa tombol tutup">
                    <x-ceplok::alert variant="warning" heading="Ini adalah Alert" :dismissible="false" :dark-mode="$docsDark">{{ $body }}</x-ceplok::alert>
                </x-docs.demo>
                <x-docs.demo label="Tanpa ikon">
                    <x-ceplok::alert variant="warning" :icon="false" heading="Ini adalah Alert" :dismissible="false" :dark-mode="$docsDark">{{ $body }}</x-ceplok::alert>
                </x-docs.demo>
                <x-docs.demo label="Hanya pesan">
                    <x-ceplok::alert variant="warning" :dismissible="false" :dark-mode="$docsDark">
                        Ini merupakan Design system Ceplok berupa component alert.
                    </x-ceplok::alert>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Tanpa tombol: cukup hilangkan slot actions --}}
                <x-ceplok::alert variant="warning" heading="Ini adalah Alert"⟨ dark-mode⟩>…</x-ceplok::alert>

                {{-- Tanpa tombol tutup --}}
                <x-ceplok::alert variant="warning" heading="Ini adalah Alert" :⟦dismissible⟧="false"⟨ dark-mode⟩>…</x-ceplok::alert>

                {{-- Tanpa ikon kiri --}}
                <x-ceplok::alert variant="warning" :⟦icon⟧="false" heading="Ini adalah Alert"⟨ dark-mode⟩>…</x-ceplok::alert>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section name="Alert">
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti latarnya ke warna -900 dari variant itu sendiri. Ikonnya -500,
                sedangkan heading dan isi pesan sama-sama -50 sehingga tetap terang di atas latar gelap.
            </x-slot:lead>
            <x-docs.demo :dark="! $docsDark" :light="$docsDark">
                <div class="space-y-4">
                    @foreach ($variants as $variant)
                        <x-ceplok::alert :dark-mode="! $docsDark" :variant="$variant" heading="Ini adalah Alert">
                            {{ $body }}
                            <x-slot:actions>
                                <x-docs.alert-action :variant="$variant" :dark="! $docsDark" />
                            </x-slot:actions>
                        </x-ceplok::alert>
                    @endforeach
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::alert⟨! ⟦dark-mode⟧⟩ variant="success" heading="Ini adalah Alert">…</x-ceplok::alert>
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                @if ($docsDark)
                    Isi <span class="text-primary-500 dark:text-primary-400">actions</span> tetap milik Anda, jadi warnanya tidak ikut diganti. Tombol contoh di atas
                    memakai desain terangnya, sedangkan contoh lain di halaman ini mengikuti desain gelapnya: -600,
                    kecuali success dan warning yang -500.
                @else
                    Isi <span class="text-primary-500 dark:text-primary-400">actions</span> tetap milik Anda, jadi warnanya tidak ikut diganti. Tombol contoh di atas mengikuti
                    desain gelapnya: -600, kecuali success dan warning yang -500.
                @endif
            </p>
        </x-docs.mode-section>

        <livewire:playground.alert :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Ikat tampil/sembunyinya Alert ke properti komponen dengan <span class="text-primary-500 dark:text-primary-400">wire:model</span> — menutupnya
                mengubah properti itu, dan mengisinya kembali dengan true memunculkannya lagi. Event
                <span class="text-primary-500 dark:text-primary-400">dismiss</span> dari tombol tutup bisa ditangkap <span class="text-primary-500 dark:text-primary-400">wire:dismiss</span>.
            </x-docs.lead>
            <x-docs.demo>
                <livewire:playground.alert-notice :dark="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::alert
                    heading="Pemeliharaan terjadwal"
                    ⟦wire:model.live⟧="notice"
                    ⟦wire:dismiss⟧="dismissed"
                    ⟨dark-mode⟩
                >
                    Layanan tidak dapat diakses pada Sabtu pukul 22.00–24.00 WIB.
                </x-ceplok::alert>

                <x-ceplok::button wire:click="$set('notice', true)">Tampilkan lagi</x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut <span class="text-primary-500 dark:text-primary-400">&lt;div&gt;</span>
                standar juga diteruskan apa adanya.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="52rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
