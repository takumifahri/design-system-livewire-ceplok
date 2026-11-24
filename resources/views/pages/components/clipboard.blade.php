@php
    use App\Docs\Theme;

    $props = [
        ['value', 'string', '— (wajib)', 'Nilai yang ditampilkan dan disalin.'],
        ['label', 'string | slot', 'null', 'Label di atas nilai sekaligus nama aksesibel input; gunakan aria-label atau aria-labelledby bila label tidak ditampilkan.'],
        ['helper-text', 'string | slot', 'null', 'Keterangan tambahan yang ditampilkan di bawah field.'],
        ['prefix', 'string | slot', 'null', 'Konten sebelum nilai pada tampilan bersegmen. Prefix tidak ikut disalin.'],
        ['variant', '"default" | "segmented"', 'default', 'Menentukan susunan Clipboard.'],
        ['platform', '"default" | "mobile"', 'default', 'Menentukan preset ukuran Default atau Mobile.'],
        ['disabled', 'bool', 'false', 'Menonaktifkan aksi salin dan interaksi pemilihan nilai melalui UI.'],
        ['dark-mode', 'bool', 'false', 'Mengaktifkan tampilan gelap. Tanpa prop ini, Clipboard mengikuti tema pembungkusnya.'],
        ['event', 'copy-success | copy-error', '—', 'Event DOM untuk integrasi wire:* atau x-on:*.'],
        ['class', 'string', 'null', 'Kelas CSS untuk pembungkus terluar Clipboard.'],
        ['atribut input', 'id, aria-label, aria-labelledby, aria-describedby', 'null', 'ID dibuat otomatis jika kosong; aria-describedby digabung dengan helper-text.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Clipboard">
    <x-docs.page
        eyebrow="Components · Clipboard"
        title="Clipboard"
        description="Menampilkan nilai yang dapat disalin melalui tombol aksi, dengan dukungan label dan keterangan tambahan."
        :toc="[
            'default' => 'Default',
            'platform' => 'Platform',
            'segmen' => 'Dengan segmen',
            'states' => 'States',
            'callbacks' => 'Event',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="default" title="Default">
            <x-docs.lead>Clipboard menampilkan nilai hanya-baca yang dapat disalin melalui tombol aksi. Saat aktif, nilai tetap dapat dipilih untuk disalin secara manual. Berikan nama aksesibel melalui label, aria-label, atau aria-labelledby.</x-docs.lead>
            <x-docs.demo label="Default"><div class="mx-auto w-full max-w-[384px]"><x-ceplok::clipboard value="INV-2026-001" label="Nomor referensi" helper-text="Gunakan nomor ini saat menghubungi layanan." :dark-mode="$docsDark" /></div></x-docs.demo>
            <x-docs.code>@verbatim
                <x-ceplok::clipboard ⟦value⟧="INV-2026-001" ⟦label⟧="Nomor referensi"⟨ dark-mode⟩ />
            @endverbatim</x-docs.code>
        </x-docs.section>

        <x-docs.section id="platform" title="Platform">
            <x-docs.lead>Platform menentukan preset ukuran Clipboard. Gunakan Default untuk ukuran standar dan Mobile untuk tampilan yang lebih ringkas. Preset dipilih melalui prop, bukan lebar viewport.</x-docs.lead>
            <div class="grid gap-5">
                <x-docs.demo label="Desktop"><div class="mx-auto w-full max-w-[384px]"><x-ceplok::clipboard value="npm i komdigi" label="Perintah instalasi" :dark-mode="$docsDark" /></div></x-docs.demo>
                <x-docs.demo label="Mobile"><div class="mx-auto w-full max-w-[380px]"><x-ceplok::clipboard value="npm i komdigi" label="Perintah instalasi" platform="mobile" :dark-mode="$docsDark" /></div></x-docs.demo>
            </div>
            <x-docs.code>@verbatim
                <x-ceplok::clipboard value="npm i komdigi" ⟦platform⟧="mobile" aria-label="Perintah instalasi"⟨ dark-mode⟩ />
            @endverbatim</x-docs.code>
        </x-docs.section>

        <x-docs.section id="segmen" title="Dengan segmen">
            <x-docs.lead>Susunan bersegmen menggabungkan prefix, nilai, dan aksi salin dalam satu grup. Prefix bersifat kontekstual dan tidak ikut disalin. Arahkan pointer ke ikon untuk melihat tooltip; setelah salin berhasil, ikon berubah menjadi Check tanpa pergeseran layout.</x-docs.lead>
            <div class="grid gap-5">
                <x-docs.demo label="Dengan prefix"><div class="mx-auto w-full max-w-[452px]"><x-ceplok::clipboard value="https://komdigi.go.id/" label="Alamat situs" variant="segmented" prefix="URL" :dark-mode="$docsDark" /></div></x-docs.demo>
                <x-docs.demo label="Tanpa prefix"><div class="mx-auto w-full max-w-[452px]"><x-ceplok::clipboard value="INV-2026-001" label="Nomor referensi" variant="segmented" :dark-mode="$docsDark" /></div></x-docs.demo>
            </div>
            <x-docs.code>@verbatim
                <x-ceplok::clipboard value="https://komdigi.go.id/" label="Alamat situs" ⟦variant⟧="segmented" ⟦prefix⟧="URL"⟨ dark-mode⟩ />
            @endverbatim</x-docs.code>
        </x-docs.section>

        <x-docs.section id="states" title="States">
            <x-docs.lead>Tekan aksi salin untuk melihat status Copied. Saat Nonaktif, tombol, tooltip, dan pemilihan atau penyalinan nilai melalui UI dinonaktifkan; ini bukan mekanisme keamanan data.</x-docs.lead>
            <div class="grid gap-5 lg:grid-cols-2">
                <x-docs.demo label="Copied interaktif">
                    <div class="mx-auto w-full max-w-[452px]">
                        <x-ceplok::clipboard value="https://komdigi.go.id/" variant="segmented" prefix="URL" aria-label="Alamat situs" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Disabled">
                    <div class="mx-auto w-full max-w-[384px]">
                        <x-ceplok::clipboard value="INV-2026-001" label="Nomor referensi" disabled :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
        </x-docs.section>

        <x-docs.section id="callbacks" title="Event">
            <x-docs.lead>
                Event <span class="text-primary-500 dark:text-primary-400">copy-success</span> dikirim setelah Clipboard API berhasil, sedangkan
                <span class="text-primary-500 dark:text-primary-400">copy-error</span> dikirim saat API tidak tersedia atau menolak operasi.
                Listener consumer tidak mengubah feedback internal Clipboard.
            </x-docs.lead>
            <x-docs.code>@verbatim
                <x-ceplok::clipboard
                    value="INV-2026-001"
                    label="Nomor referensi"
                    ⟦x-on:copy-success⟧="saveAudit($event.detail.value)"
                    ⟦x-on:copy-error⟧="reportCopyError($event.detail.error)"
                    ⟨dark-mode⟩
                />
            @endverbatim</x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengaktifkan palet gelap pada field, teks, prefix, dan tooltip. Tanpa prop tersebut, Clipboard mengikuti scope ambient <span class="text-primary-500 dark:text-primary-400">data-theme</span>.</x-slot:lead>
            <x-docs.demo dark><div class="mx-auto w-full max-w-[452px]"><x-ceplok::clipboard value="https://komdigi.go.id/" label="Alamat situs" helper-text="Gunakan alamat ini untuk mengunjungi situs." variant="segmented" prefix="URL" dark-mode /></div></x-docs.demo>
            <x-docs.code>@verbatim
                <x-ceplok::clipboard
                    ⟦dark-mode⟧
                    value="https://komdigi.go.id/"
                    ⟦aria-label⟧="Alamat situs"
                    variant="segmented"
                    prefix="URL"
                />
            @endverbatim</x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.clipboard :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Tangkap <span class="text-primary-500 dark:text-primary-400">copy-success</span> dan <span class="text-primary-500 dark:text-primary-400">copy-error</span>
                lewat atribut <span class="text-primary-500 dark:text-primary-400">wire:</span> untuk mencatat hasil penyalinan di state server.
            </x-docs.lead>
            <x-docs.demo>
                <livewire:playground.clipboard-livewire :dark="$docsDark" />
            </x-docs.demo>
            <x-docs.code>@verbatim
                <x-ceplok::clipboard
                    value="INV-2026-001"
                    label="Nomor referensi"
                    variant="segmented"
                    prefix="ID"
                    ⟦wire:copy-success⟧="copied($event.detail.value)"
                    ⟦wire:copy-error⟧="failed"
                    ⟨dark-mode⟩
                />
            @endverbatim</x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>Daftar prop, tipe, dan nilai bawaannya. Clipboard memerlukan salah satu dari <span class="text-primary-500 dark:text-primary-400">label</span>, <span class="text-primary-500 dark:text-primary-400">aria-label</span>, atau <span class="text-primary-500 dark:text-primary-400">aria-labelledby</span>. Hanya <span class="text-primary-500 dark:text-primary-400">id</span> serta atribut penamaan atau deskripsi aksesibel yang diteruskan ke input hanya-baca.</x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="50rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
