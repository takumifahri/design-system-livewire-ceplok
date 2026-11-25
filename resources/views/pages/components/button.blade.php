@php
    use App\Docs\Theme;

    $sizes = ['xs', 's', 'base', 'l', 'xl'];

    $themes = [
        ['primary', 'Primary', 'digunakan untuk tindakan utama atau aksi yang paling penting dalam suatu halaman.'],
        ['green', 'Green', 'digunakan untuk tindakan yang menunjukkan keberhasilan, konfirmasi, atau penyelesaian.'],
        ['gray', 'Gray', 'digunakan untuk tindakan sekunder atau aksi dengan tingkat prioritas rendah.'],
        ['purple', 'Purple', 'digunakan untuk tindakan atau fitur khusus yang membutuhkan penekanan visual berbeda.'],
        ['orange', 'Orange', 'digunakan untuk tindakan yang membutuhkan perhatian atau bersifat peringatan.'],
        ['yellow', 'Yellow', 'digunakan untuk informasi yang perlu diperhatikan tanpa menunjukkan kondisi kritis.'],
    ];

    $props = [
        ['as', '"button" | "anchor"', 'button', 'Menentukan elemen yang dirender. Tanpa as, tombol otomatis menjadi anchor bila href diisi.'],
        ['type', '"button" | "submit" | "reset"', 'button', 'Menentukan tipe tombol bawaan HTML. Hanya dipasang pada <button>.'],
        ['icon-only', 'bool', 'false', 'Jika true, gaya tombol disesuaikan menjadi khusus ikon. Beri aria-label.'],
        ['size', '"xs" | "s" | "base" | "l" | "xl"', 'base', 'Menentukan ukuran tombol.'],
        ['variant', '"filled" | "outline"', 'filled', 'Gaya utama tombol: solid atau outline.'],
        ['dark-mode', 'bool', 'false', 'Filled dengan theme memakai palet gelapnya; filled tanpa theme dan seluruh outline menjadi putih.'],
        ['left-icon', 'Htmlable | slot', 'null', 'Ikon di sisi kiri tombol, mis. svg(\'ceplok-s-messages\', \'size-4\').'],
        ['right-icon', 'Htmlable | slot', 'null', 'Ikon di sisi kanan tombol.'],
        ['theme', '"primary" | "green" | "gray" | "purple" | "orange" | "yellow"', 'primary (light mode)', 'Warna aksen tombol. Pada dark mode, Button tanpa theme atau dengan theme di luar enam bawaan memakai fallback putih.'],
        ['wire-click', 'string', 'null', 'Memasang wire:click berisi aksi Livewire, mis. "simpan". Selama aksi berjalan tombol dinonaktifkan (wire:loading.attr="disabled", wire:target).'],
    ];

    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Button">
    <x-docs.page
        eyebrow="Components · Button"
        title="Button"
        description="Tombol untuk memicu aksi utama pada sebuah halaman atau form. Mendukung berbagai ukuran, warna, varian, dan penggunaan icon."
        :toc="[
            'button' => 'Button & Sizes',
            'icon-only' => 'Icon Only & Sizes',
            'variants' => 'Variants',
            'themes' => 'Themes',
            'dark-mode' => 'Dark mode',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="button" title="Button & Sizes">
            <p class="mb-4 text-body-sm text-gray-500 dark:text-gray-400">
                Tombol default dalam berbagai ukuran untuk membantu memilih <span class="text-primary-500 dark:text-primary-400">size</span> yang sesuai dengan
                ruang layout. Tombol ditampilkan menggunakan ikon untuk memberikan gambaran untuk tombol
                dengan ikon saat dipakai sebagai aksi utama.
            </p>

            <div class="mb-4 flex flex-wrap items-start justify-start gap-6">
                @foreach ($sizes as $size)
                    <div class="flex flex-col items-center gap-3">
                        <x-ceplok::button
                            :size="$size"
                            theme="primary"
                            :dark-mode="$docsDark"
                            :left-icon="svg('ceplok-s-messages', 'size-4')"
                            :right-icon="svg('ceplok-s-messages', 'size-4')"
                        >Button</x-ceplok::button>
                        <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">{{ $size }}</span>
                    </div>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::button
                    ⟦size⟧="base"
                    ⟨dark-mode⟩
                    :left-icon="svg('ceplok-s-messages', 'size-4')"
                    :right-icon="svg('ceplok-s-messages', 'size-4')"
                >
                    Button
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="icon-only" title="Icon Only & Sizes">
            <p class="mb-4 text-body-sm text-gray-500 dark:text-gray-400">
                Versi ringkas untuk aksi cepat seperti toolbar, floating action, menu, atau shortcut.
                Tersedia dengan beberapa ukuran dan digunakan saat label tidak diperlukan atau ruang
                layout yang sempit.
            </p>

            <div class="mb-4 flex flex-wrap items-start justify-start gap-6">
                @foreach ($sizes as $size)
                    <div class="flex flex-col items-center gap-3">
                        <x-ceplok::button :size="$size" theme="primary" :dark-mode="$docsDark" icon-only :aria-label="'Icon '.$size">
                            {{ svg('ceplok-s-messages', 'size-4') }}
                        </x-ceplok::button>
                        <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase dark:text-gray-400">{{ $size }}</span>
                    </div>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::button
                    ⟦icon-only⟧
                    ⟦size⟧="base"
                    ⟨dark-mode⟩
                    aria-label="Kirim pesan"
                >
                    {{ svg('ceplok-s-messages', 'size-4') }}
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="variants" title="Variants">
            <p class="mb-4 text-body-sm text-gray-500 dark:text-gray-400">
                Variasi tombol yang ditentukan oleh <span class="text-primary-500 dark:text-primary-400">variant</span>. Gunakan <span class="text-primary-500 dark:text-primary-400">filled</span> untuk aksi utama
                yang perlu mendapat perhatian lebih, dan <span class="text-primary-500 dark:text-primary-400">outline</span> untuk tindakan pendukung yang
                tetap terlihat, namun tidak terlalu dominan.
            </p>

            <div class="mb-6 grid gap-5 sm:grid-cols-2 sm:gap-8">
                <div>
                    <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">Filled</h3>
                    <div class="flex min-h-[130px] items-center rounded-2xl border border-border bg-surface-subtle p-8 dark:border-gray-700 dark:bg-gray-900">
                        <x-ceplok::button variant="filled" theme="primary" :dark-mode="$docsDark">Simpan Perubahan</x-ceplok::button>
                    </div>
                </div>
                <div>
                    <h3 class="mb-3 text-lg font-bold text-gray-900 dark:text-white">Outline</h3>
                    <div class="flex min-h-[130px] items-center rounded-2xl border border-border bg-surface-subtle p-8 dark:border-gray-700 dark:bg-gray-900">
                        <x-ceplok::button variant="outline" theme="primary" :dark-mode="$docsDark">Lihat Detail</x-ceplok::button>
                    </div>
                </div>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::button
                    size="base"
                    ⟦variant⟧="outline"
                    ⟨dark-mode⟩
                >
                    Lihat Detail
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="themes" title="Themes">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Tema digunakan untuk membedakan konteks dan tingkat prioritas suatu aksi.
            </p>

            <div class="mb-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($themes as [$theme, $label, $description])
                    <div>
                        <x-ceplok::button :theme="$theme" :dark-mode="$docsDark">{{ $label }}</x-ceplok::button>
                        <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                            <span class="text-primary-500 dark:text-primary-400">{{ $label }}</span> {{ $description }}
                        </p>
                    </div>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::button
                    type="button"
                    variant="filled"
                    ⟦theme⟧="green"
                    ⟨dark-mode⟩
                >
                    Green
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Gunakan <span class="text-primary-500 dark:text-primary-400">dark-mode</span> untuk menjaga kontras Button di atas permukaan gelap.
            </x-slot:lead>

            <div class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <x-docs.demo label="Filled · theme bawaan" dark>
                    <x-ceplok::button dark-mode variant="filled" theme="primary" :left-icon="svg('ceplok-s-messages', 'size-4')">
                        Simpan Perubahan
                    </x-ceplok::button>
                </x-docs.demo>

                <x-docs.demo label="Filled · fallback putih" dark>
                    <x-ceplok::button dark-mode variant="filled">Simpan Perubahan</x-ceplok::button>
                </x-docs.demo>

                <x-docs.demo label="Icon Only" dark>
                    <x-ceplok::button dark-mode variant="filled" theme="primary" icon-only aria-label="Buka pesan">
                        {{ svg('ceplok-s-messages', 'size-4') }}
                    </x-ceplok::button>
                </x-docs.demo>

                <x-docs.demo label="Outline" dark>
                    <x-ceplok::button dark-mode variant="outline" theme="purple">Lihat detail</x-ceplok::button>
                </x-docs.demo>
            </div>

            <p class="mt-5 text-sm leading-6 text-gray-600 dark:text-gray-300">
                Catatan: Filled dengan salah satu dari enam theme bawaan memakai palet gelap theme
                tersebut. Filled tanpa theme atau tanpa palet dark khusus (theme di luar enam bawaan)
                memakai fallback putih. Outline selalu memakai warna putih, apa pun theme-nya; Icon Only
                mengikuti aturan Filled.
            </p>

            <x-docs.code>
                @verbatim
                <x-ceplok::button dark-mode variant="filled" theme="primary" :left-icon="svg('ceplok-s-messages', 'size-4')">
                    Simpan Perubahan
                </x-ceplok::button>

                <x-ceplok::button dark-mode variant="filled">
                    Simpan Perubahan
                </x-ceplok::button>

                <x-ceplok::button dark-mode variant="filled" theme="primary" icon-only aria-label="Buka pesan">
                    {{ svg('ceplok-s-messages', 'size-4') }}
                </x-ceplok::button>

                <x-ceplok::button dark-mode variant="outline" theme="purple">
                    Lihat detail
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <livewire:playground.button :dark="$docsDark" />

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500 dark:text-gray-400">
                Referensi semua prop yang tersedia. Digunakan untuk bisa memahami cara kerja komponen
                tanpa harus menebak implementasinya dari kode. Prop pilihan juga menerima enum PHP dari
                <code class="text-xs font-bold text-gray-700 dark:text-gray-300">CeplokUi\DesignKit\Enums</code>, mis. <code class="text-xs font-bold text-gray-700 dark:text-gray-300">ButtonSize::Xl</code>.
            </p>

            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
