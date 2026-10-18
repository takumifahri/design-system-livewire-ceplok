@php
    $photo = '/images/avatar-sample.svg';

    $sizes = [
        ['small', 'Small', '24px'],
        ['default', 'Default', '32px'],
        ['large', 'Large', '80px'],
    ];

    $props = [
        ['src', 'string', 'null', 'Alamat gambar. Bila kosong atau gagal dimuat, inisial yang tampil.'],
        ['alt', 'string', "''", 'Nama pemiliknya. Jadi alt gambar, atau nama aksesibilitas saat yang tampil hanya inisial.'],
        ['initials', 'string | slot', 'null', 'Isi lingkaran saat tidak ada gambar — umumnya satu atau dua huruf.'],
        ['size', '"small" | "default" | "large"', 'default', 'Diameter lingkaran: 24px, 32px, atau 80px.'],
        ['dark-mode', 'bool', 'false', 'Menggunakan warna fallback Avatar untuk permukaan gelap.'],
        ['…atribut', 'HTML', '—', 'Atribut <div> standar diteruskan (class, title, wire:click, …).'],
    ];
@endphp

<x-docs.layout title="Avatar">
    <x-docs.page
        title="Avatar"
        description="Lingkaran identitas berisi foto profil, atau inisial namanya bila fotonya tidak ada. Tersedia dalam tiga ukuran, dari yang menempel di daftar sampai yang jadi kepala halaman profil."
        :toc="[
            'avatar' => 'Avatar',
            'inisial' => 'Inisial',
            'sizes' => 'Sizes',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="avatar" title="Avatar">
            <x-docs.lead>
                Isinya yang menentukan bentuknya, bukan prop terpisah: begitu <span class="text-primary-500">src</span> diisi, avatarnya
                menampilkan foto; tanpa itu ia jatuh ke inisial. Fotonya selalu dipotong ke lingkaran dan
                diratakan di tengah, jadi gambar apa pun bisa masuk tanpa disiapkan lebih dulu.
            </x-docs.lead>
            <x-docs.demo label="Dengan foto">
                <div class="flex items-center gap-4">
                    <x-ceplok::avatar :src="$photo" alt="Putri Handayani" size="large" />
                    <x-ceplok::avatar :src="$photo" alt="Putri Handayani" />
                    <x-ceplok::avatar :src="$photo" alt="Putri Handayani" size="small" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦src⟧="/foto/putri.jpg" ⟦alt⟧="Putri Handayani" />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500">
                Beri <span class="text-primary-500">alt</span> berisi nama pemiliknya. Kalau namanya sudah tertulis persis di sebelah
                avatar — seperti pada daftar anggota atau menu profil — kosongkan saja: avatarnya akan
                terbaca sebagai hiasan dan pembaca layar tidak menyebut nama yang sama dua kali.
            </p>
        </x-docs.section>

        <x-docs.section id="inisial" title="Inisial">
            <x-docs.lead>
                Tanpa <span class="text-primary-500">src</span>, lingkarannya diisi teks yang Anda beri lewat <span class="text-primary-500">initials</span> di atas latar
                abu-abu. Satu atau dua huruf adalah takaran yang aman; lebih dari itu mulai berdesakan di
                ukuran kecil.
            </x-docs.lead>
            <x-docs.demo label="Tanpa foto">
                <div class="flex items-center gap-4">
                    <x-ceplok::avatar initials="PH" alt="Putri Handayani" size="large" />
                    <x-ceplok::avatar initials="PH" alt="Putri Handayani" />
                    <x-ceplok::avatar initials="PH" alt="Putri Handayani" size="small" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦initials⟧="PH" ⟦alt⟧="Putri Handayani" />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500">
                Inisial juga jadi jaring pengaman: bila <span class="text-primary-500">src</span> diisi tapi gambarnya gagal dimuat,
                avatarnya berpindah sendiri ke inisial. Alamat foto profil biasanya datang dari sistem lain
                dan bisa mati kapan saja — tanpa ini yang tersisa di halaman hanya ikon gambar rusak.
            </p>
            <x-docs.demo label="Foto rusak → jatuh ke inisial">
                <div class="flex items-center gap-4">
                    <x-ceplok::avatar src="/foto/tidak-ada.jpg" initials="PH" alt="Putri Handayani" size="large" />
                    <x-ceplok::avatar src="/foto/tidak-ada.jpg" initials="PH" alt="Putri Handayani" />
                    <x-ceplok::avatar src="/foto/tidak-ada.jpg" initials="PH" alt="Putri Handayani" size="small" />
                </div>
            </x-docs.demo>
        </x-docs.section>

        <x-docs.section id="sizes" title="Sizes">
            <x-docs.lead>
                Tiga ukuran, dan teks inisialnya ikut naik bersamanya supaya porsi isian di dalam lingkaran
                tetap terasa sama. <span class="text-primary-500">small</span> untuk baris daftar yang padat, <span class="text-primary-500">default</span> untuk navbar
                dan menu profil, <span class="text-primary-500">large</span> untuk kepala halaman profil.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-3">
                @foreach ($sizes as [$size, $label, $diameter])
                    <x-docs.demo :label="$label.' — '.$diameter">
                        <div class="flex items-center gap-3">
                            <x-ceplok::avatar :src="$photo" alt="Putri Handayani" :size="$size" />
                            <x-ceplok::avatar initials="PH" alt="Putri Handayani" :size="$size" />
                        </div>
                    </x-docs.demo>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦size⟧="large" ⟦src⟧="/foto/putri.jpg" ⟦alt⟧="Putri Handayani" />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500">
                Ukuran teks inisialnya berturut-turut <span class="text-primary-500">12px</span>, <span class="text-primary-500">16px</span>,
                <span class="text-primary-500">30px</span>. Avatar tidak pernah ikut menyusut oleh flexbox, jadi aman diletakkan di samping teks
                panjang tanpa takut gepeng.
            </p>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Prop <span class="text-primary-300">dark-mode</span> mengubah fallback menjadi gray-800 dengan inisial gray-100. Foto
                profil tetap tampil apa adanya; bila foto gagal dimuat, fallback gelap ini yang mengambil
                alih.
            </x-docs.lead>
            <x-docs.demo label="Fallback di permukaan gelap" dark>
                <div class="flex items-center gap-4">
                    <x-ceplok::avatar dark-mode initials="PH" alt="Putri Handayani" size="large" />
                    <x-ceplok::avatar dark-mode initials="PH" alt="Putri Handayani" />
                    <x-ceplok::avatar dark-mode initials="PH" alt="Putri Handayani" size="small" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦dark-mode⟧ ⟦initials⟧="PH" ⟦alt⟧="Putri Handayani" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.avatar />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima komponen, beserta tipe dan nilai bawaannya. Atribut
                <span class="text-primary-500">&lt;div&gt;</span> standar juga diteruskan apa adanya.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="48rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
