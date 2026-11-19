@php
    use App\Docs\Theme;

    $messages = [
        'success' => 'Sukses Membuat Data!',
        'danger' => 'Gagal Membuat Data!',
        'warning' => 'Sesi Anda akan berakhir dalam 5 menit.',
        'info' => 'Data sedang diproses.',
        'purple' => 'Fitur baru sudah tersedia.',
    ];

    $props = [
        ['variant', '"success" | "danger" | "warning" | "info" | "purple"', 'info', 'Warna badge ikon; kartunya tetap netral.'],
        ['heading', 'string | slot', 'null', 'Judul singkat; bila diisi, isi tampil sebagai teks sekunder di bawahnya.'],
        ['icon', 'Htmlable | slot | false', 'null', 'Ikon kustom. false menyembunyikan badge ikon, kosong memakai bawaan variant.'],
        ['dismissible', 'bool', 'true', 'Menampilkan tombol tutup (×) di kanan atas.'],
        ['open', 'bool', 'true', 'Tampil sejak awal. Bisa dikendalikan dari luar lewat wire:model atau x-model.'],
        ['actions', 'slot', 'null', 'Tombol tindak lanjut di bawah isi — umumnya dibuat w-full.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: kartu gray-700, teks lebih terang, dan badge ikon versi gelap.'],
        ['wire:dismiss', 'event', '—', 'Dipicu saat tombol tutup diklik; padanan onDismiss. Juga bisa x-on:dismiss.'],
        ['slot', 'slot', '—', 'Isi pesan.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut <div> diteruskan (class, id, …).'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Toast">
    <x-docs.page
        title="Toast"
        description="Notifikasi sekilas yang melayang di atas konten halaman — untuk konfirmasi aksi, pembaruan status, atau ajakan tindak lanjut. Berbeda dari Alert: kartu tetap netral, hanya badge ikon yang membawa warna variant. Tersedia juga dalam tampilan gelap."
        :toc="[
            'toast' => 'Toast',
            'heading-aksi' => 'Dengan heading & aksi',
            'dark-mode' => 'Dark mode',
            'posisi' => 'Posisi & tumpukan',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="toast" title="Toast">
            <x-docs.lead>
                Kartu putih 320px beradius 8px, berisi badge ikon 32px di kiri dan tombol tutup 12px di kanan
                atas. Bentuk paling ringkasnya satu baris pesan — cocok untuk konfirmasi singkat setelah sebuah
                aksi selesai.
            </x-docs.lead>
            <x-docs.demo>
                <div class="space-y-3">
                    @foreach ($messages as $variant => $message)
                        <x-ceplok::toast :variant="$variant" :dismissible="false" :dark-mode="$docsDark">{{ $message }}</x-ceplok::toast>
                    @endforeach
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::toast ⟦variant⟧="success"⟨ dark-mode⟩>Sukses Membuat Data!</x-ceplok::toast>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="heading-aksi" title="Dengan heading & aksi">
            <x-docs.lead>
                Isi <span class="text-primary-500 dark:text-primary-400">heading</span> untuk memisahkan judul dari isi, dan <span class="text-primary-500 dark:text-primary-400">actions</span> untuk satu tombol tindak
                lanjut — umumnya dibuat <span class="text-primary-500 dark:text-primary-400">w-full</span> agar memenuhi lebar kartu. Begitu ada salah satunya, badge
                ikon berpindah ke rata atas supaya sejajar dengan baris pertama.
            </x-docs.lead>
            <x-docs.demo>
                <x-ceplok::toast variant="info" :icon="svg('ceplok-o-refresh')" heading="Update 2FA sudah tersedia!" :dark-mode="$docsDark">
                    Update Metode Password anda sekarang
                    <x-slot:actions>
                        <x-ceplok::button size="xs" class="w-full">Update 2FA</x-ceplok::button>
                    </x-slot:actions>
                </x-ceplok::toast>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::toast
                    variant="info"
                    :⟦icon⟧="svg('ceplok-o-refresh')"
                    ⟦heading⟧="Update 2FA sudah tersedia!"
                    ⟨dark-mode⟩
                >
                    Update Metode Password anda sekarang
                    <x-slot:⟦actions⟧>
                        <x-ceplok::button size="xs" class="w-full">Update 2FA</x-ceplok::button>
                    </x-slot:actions>
                </x-ceplok::toast>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengganti kartunya ke gray-700, heading dan pesan singkat ke gray-300, dan teks
                pendukung di bawah heading ke gray-400. Badge ikon ikut memakai versi gelapnya — latar gelap dengan ikon
                -400 — sedangkan tombol tutup tetap gray-400 dan tombol di <span class="text-primary-500 dark:text-primary-400">actions</span> tetap primary-700 di kedua
                tampilan.
            </x-slot:lead>
            <div class="grid gap-5 lg:grid-cols-2">
                <x-docs.demo label="Pesan singkat" dark>
                    <div class="space-y-3">
                        @foreach ($messages as $variant => $message)
                            <x-ceplok::toast dark-mode :variant="$variant" :dismissible="false">{{ $message }}</x-ceplok::toast>
                        @endforeach
                    </div>
                </x-docs.demo>
                <x-docs.demo label="Dengan heading & aksi" dark>
                    <x-ceplok::toast dark-mode variant="info" :icon="svg('ceplok-o-refresh')" heading="Update 2FA sudah tersedia!">
                        Update Metode Password anda sekarang
                        <x-slot:actions>
                            <x-ceplok::button size="xs" class="w-full">Update 2FA</x-ceplok::button>
                        </x-slot:actions>
                    </x-ceplok::toast>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::toast ⟦dark-mode⟧ variant="success">Sukses Membuat Data!</x-ceplok::toast>
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Warning dan purple belum punya versi gelap di desain, jadi keduanya mengikuti pola mayoritas: latar
                -800 dengan ikon -400.
            </p>
        </x-docs.mode-section>

        <x-docs.section id="posisi" title="Posisi & tumpukan">
            <x-docs.lead>
                Komponennya sendiri tidak memposisikan apa pun. Bungkus dengan wadah <span class="text-primary-500 dark:text-primary-400">fixed</span> di sudut yang
                Anda mau — dengan begitu beberapa Toast bisa ditumpuk dalam satu wadah ber-<span class="text-primary-500 dark:text-primary-400">space-y</span> tanpa
                mengunci satu posisi ke dalam komponen.
            </x-docs.lead>
            <x-docs.demo label="Tiga Toast dalam satu wadah">
                <div class="space-y-3">
                    <x-ceplok::toast variant="success" :dark-mode="$docsDark">Sukses Membuat Data!</x-ceplok::toast>
                    <x-ceplok::toast variant="warning" :dark-mode="$docsDark">Sesi Anda akan berakhir dalam 5 menit.</x-ceplok::toast>
                    <x-ceplok::toast variant="info" :dark-mode="$docsDark">Data sedang diproses.</x-ceplok::toast>
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                {{-- Wadah yang memposisikan; Toast-nya sendiri hanya kartu --}}
                <div class="⟦fixed bottom-4 left-4 z-50⟧ space-y-3">
                    <x-ceplok::toast variant="success"⟨ dark-mode⟩>Sukses Membuat Data!</x-ceplok::toast>
                    <x-ceplok::toast variant="warning"⟨ dark-mode⟩>Sesi Anda akan berakhir dalam 5 menit.</x-ceplok::toast>
                </div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.toast :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Ikat Toast ke properti komponen dengan <span class="text-primary-500 dark:text-primary-400">wire:model</span>, lalu munculkan dari aksi server
                dengan mengisinya true. Nilai awalnya dibaca dari properti itu, jadi Toast yang belum dipicu tidak
                sempat tampil saat halaman dimuat. Menutupnya mengembalikan properti ke false.
            </x-docs.lead>
            <x-docs.demo>
                <livewire:playground.toast-saved :dark="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::button wire:click="save">Simpan data</x-ceplok::button>

                <x-ceplok::toast variant="success" ⟦wire:model⟧="saved"⟨ dark-mode⟩>
                    Data #{{ $count }} berhasil disimpan!
                </x-ceplok::toast>
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
