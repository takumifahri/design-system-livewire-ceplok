@php
    use App\Docs\Theme;

    $photo = '/images/avatar-sample.svg';
    $sizes = [
        ['small', 'S', '24px', '12px'],
        ['default', 'M', '32px', '16px'],
        ['large', 'L', '80px', '30px'],
    ];
    $props = [
        ['src', 'string', 'null', 'Alamat foto. Bila kosong atau gagal dimuat, Avatar menampilkan fallback.'],
        ['name', 'string', 'null', 'Nama untuk accessible name dan inisial otomatis.'],
        ['alt', 'string', 'name atau ""', 'Override accessible name. String kosong menjadikan Avatar dekoratif.'],
        ['initials', 'string | slot', 'null', 'Override isi inisial ketika foto tidak tersedia.'],
        ['fallback', '"initials" | "female" | "male"', 'initials', 'Memilih fallback secara eksplisit; initials memerlukan name atau initials.'],
        ['size', '"small" | "default" | "large"', 'default (M)', 'Diameter lingkaran: 24px, 32px, atau 80px.'],
        ['dark-mode', 'bool', 'false', 'Mengaktifkan tampilan gelap. Tanpa prop ini, Avatar mengikuti tema pembungkusnya.'],
        ['…atribut', 'HTML', '—', 'Atribut root diteruskan; role, aria-label, dan data-theme dapat meng-override nilai terhitung. Class digabungkan.'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Avatar">
    <x-docs.page
        title="Avatar"
        description="Lingkaran identitas berisi foto, inisial otomatis, atau ilustrasi profil."
        :toc="[
            'avatar' => 'Avatar',
            'inisial' => 'Inisial',
            'profile' => 'Profile fallback',
            'sizes' => 'Sizes',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'livewire' => 'Livewire',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="avatar" title="Avatar">
            <x-docs.lead>
                Foto dari <span class="text-primary-500 dark:text-primary-400">src</span> selalu menjadi pilihan pertama. Jika kosong atau gagal dimuat,
                Avatar berpindah ke fallback tanpa mengubah ukuran dan bentuk lingkarannya. Foto memakai crop tengah
                <span class="text-primary-500 dark:text-primary-400">object-cover</span>, sehingga rasio tetap terjaga.
            </x-docs.lead>
            <x-docs.demo label="Default Avatar / foto">
                <div class="flex items-center gap-4">
                    <x-ceplok::avatar :src="$photo" name="Putri Handayani" size="large" :dark-mode="$docsDark" />
                    <x-ceplok::avatar :src="$photo" name="Putri Handayani" :dark-mode="$docsDark" />
                    <x-ceplok::avatar :src="$photo" name="Putri Handayani" size="small" :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦:src⟧="$user->avatar_url" ⟦:name⟧="$user->name"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Bila nama sudah ditampilkan sebagai teks di dekat Avatar, gunakan <span class="text-primary-500 dark:text-primary-400">alt=""</span>
                agar tidak dibacakan dua kali.
            </p>
            <x-docs.demo>
                <div class="flex items-center gap-3">
                    <x-ceplok::avatar name="Putri Handayani" alt="" :dark-mode="$docsDark" />
                    <span class="text-sm text-content dark:text-white">Putri Handayani</span>
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <div class="flex items-center gap-3">
                    <x-ceplok::avatar ⟦name⟧="Putri Handayani" ⟦alt⟧=""⟨ dark-mode⟩ />
                    <span>Putri Handayani</span>
                </div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="inisial" title="Inisial">
            <x-docs.lead>
                Tanpa foto, <span class="text-primary-500 dark:text-primary-400">name</span> menghasilkan inisial otomatis. Nama satu kata memakai dua
                karakter awal; nama beberapa kata memakai karakter awal kata pertama dan terakhir.
            </x-docs.lead>
            <x-docs.demo label="Otomatis dari name">
                <div class="flex items-center gap-4">
                    <x-ceplok::avatar name="Putri Handayani" size="large" :dark-mode="$docsDark" />
                    <x-ceplok::avatar name="Putri Handayani" :dark-mode="$docsDark" />
                    <x-ceplok::avatar name="Putri Handayani" size="small" :dark-mode="$docsDark" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦name⟧="Putri Handayani"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
            <div class="mt-5">
                <x-docs.demo label="Override initials">
                    <div class="flex items-center gap-4">
                        <x-ceplok::avatar name="Putri Handayani" initials="UI" size="large" :dark-mode="$docsDark" />
                        <x-ceplok::avatar name="Putri Handayani" initials="UI" :dark-mode="$docsDark" />
                        <x-ceplok::avatar name="Putri Handayani" initials="UI" size="small" :dark-mode="$docsDark" />
                    </div>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦name⟧="Putri Handayani" ⟦initials⟧="UI"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="profile" title="Profile fallback">
            <x-docs.lead>
                <span class="text-primary-500 dark:text-primary-400">fallback="female"</span> atau <span class="text-primary-500 dark:text-primary-400">fallback="male"</span>
                menampilkan ilustrasi profil walaupun <span class="text-primary-500 dark:text-primary-400">name</span> tersedia. Varian tidak pernah ditebak dari nama.
                <span class="text-primary-500 dark:text-primary-400">fallback="initials"</span> adalah bawaan dan memerlukan name atau initials.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-3">
                <x-docs.demo label="Female">
                    <x-ceplok::avatar name="Putri Handayani" fallback="female" size="large" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Male">
                    <x-ceplok::avatar name="Rafli Arandhana" fallback="male" size="large" :dark-mode="$docsDark" />
                </x-docs.demo>
                <x-docs.demo label="Default → initials">
                    <x-ceplok::avatar name="Pengguna" size="large" :dark-mode="$docsDark" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦name⟧="Putri Handayani" ⟦fallback⟧="female"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="sizes" title="Sizes">
            <x-docs.lead>
                S berdiameter 24px, M 32px, dan L 80px. M adalah ukuran default, sehingga prop
                <span class="text-primary-500 dark:text-primary-400">size</span> tidak perlu ditulis untuk ukuran tersebut.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-3">
                @foreach ($sizes as [$size, $label, $diameter])
                    <x-docs.demo :label="$label.' — '.$diameter">
                        <div class="flex items-center gap-3">
                            <x-ceplok::avatar name="Putri Handayani" :size="$size" :dark-mode="$docsDark" />
                            <x-ceplok::avatar name="Putri Handayani" fallback="female" :size="$size" :dark-mode="$docsDark" />
                        </div>
                    </x-docs.demo>
                @endforeach
            </div>
            <p class="mt-4 text-body-sm text-gray-500 dark:text-gray-400">
                Ukuran teks inisial S, M, dan L berturut-turut {{ collect($sizes)->pluck(3)->join(', ') }}.
            </p>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦size⟧="small" name="Putri Handayani"⟨ dark-mode⟩ />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> mengubah warna inisial dan ilustrasi profil. Tanpa prop
                tersebut, Avatar mengikuti scope ambient <span class="text-primary-500 dark:text-primary-400">data-theme</span>; foto pengguna tidak
                diberi tint.
            </x-slot:lead>
            <x-docs.demo dark>
                <div class="flex flex-wrap items-center gap-4">
                    <x-ceplok::avatar name="Putri Handayani" size="large" dark-mode />
                    <x-ceplok::avatar name="Putri Handayani" fallback="female" size="large" dark-mode />
                    <x-ceplok::avatar name="Rafli Arandhana" fallback="male" size="large" dark-mode />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::avatar ⟦dark-mode⟧ name="Putri Handayani" />
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.avatar :dark="$docsDark" />

        <x-docs.section id="livewire" title="Livewire">
            <x-docs.lead>
                Nilai <span class="text-primary-500 dark:text-primary-400">src</span>, <span class="text-primary-500 dark:text-primary-400">name</span>, dan prop Avatar lainnya
                dapat berasal langsung dari properti Livewire. Saat pengguna aktif berubah, Avatar ikut dirender ulang; foto baru
                dicoba kembali dan tetap berpindah ke inisial bila gagal dimuat.
            </x-docs.lead>
            <x-docs.demo>
                <livewire:playground.avatar-livewire :dark="$docsDark" />
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <div class="flex items-center gap-3">
                    <x-ceplok::avatar
                        ⟦:src⟧="$user['avatar']"
                        ⟦:name⟧="$user['name']"
                        alt=""
                        ⟨dark-mode⟩
                    />
                    <span>{{ $user['name'] }}</span>
                </div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Seluruh prop yang diterima Avatar, beserta tipe dan nilai bawaannya. Atribut HTML
                <span class="text-primary-500 dark:text-primary-400">&lt;div&gt;</span> diteruskan ke root; role, aria-label, dan data-theme dapat
                meng-override nilai terhitung, sedangkan class digabungkan.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="52rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
