@php
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
        ['tone', '"light" | "dark"', 'light', 'Menyetel kontras warna untuk variant atau theme.'],
        ['left-icon', 'Htmlable | slot', 'null', 'Ikon di sisi kiri tombol, mis. svg(\'ceplok-s-messages\', \'size-4\').'],
        ['right-icon', 'Htmlable | slot', 'null', 'Ikon di sisi kanan tombol.'],
        ['theme', '"primary" | "green" | "gray" | "purple" | "orange" | "yellow"', 'primary', 'Warna aksen tombol.'],
    ];
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
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="button" title="Button & Sizes">
            <p class="mb-4 text-body-sm text-gray-500">
                Tombol default dalam berbagai ukuran untuk membantu memilih <span class="text-primary-500">size</span> yang sesuai dengan
                ruang layout. Tombol ditampilkan menggunakan ikon untuk memberikan gambaran untuk tombol
                dengan ikon saat dipakai sebagai aksi utama.
            </p>

            <div class="mb-4 flex flex-wrap items-start justify-start gap-6">
                @foreach ($sizes as $size)
                    <div class="flex flex-col items-center gap-3">
                        <x-ceplok::button
                            :size="$size"
                            :left-icon="svg('ceplok-s-messages', 'size-4')"
                            :right-icon="svg('ceplok-s-messages', 'size-4')"
                        >Button</x-ceplok::button>
                        <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase">{{ $size }}</span>
                    </div>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::button
                    ⟦size⟧="base"
                    :left-icon="svg('ceplok-s-messages', 'size-4')"
                    :right-icon="svg('ceplok-s-messages', 'size-4')"
                >
                    Button
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="icon-only" title="Icon Only & Sizes">
            <p class="mb-4 text-body-sm text-gray-500">
                Versi ringkas untuk aksi cepat seperti toolbar, floating action, menu, atau shortcut.
                Tersedia dengan beberapa ukuran dan digunakan saat label tidak diperlukan atau ruang
                layout yang sempit.
            </p>

            <div class="mb-4 flex flex-wrap items-start justify-start gap-6">
                @foreach ($sizes as $size)
                    <div class="flex flex-col items-center gap-3">
                        <x-ceplok::button :size="$size" icon-only :aria-label="'Icon '.$size">
                            {{ svg('ceplok-s-messages', 'size-4') }}
                        </x-ceplok::button>
                        <span class="text-xs font-semibold tracking-wide text-gray-500 uppercase">{{ $size }}</span>
                    </div>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::button
                    ⟦icon-only⟧
                    ⟦size⟧="base"
                    aria-label="Kirim pesan"
                >
                    {{ svg('ceplok-s-messages', 'size-4') }}
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="variants" title="Variants">
            <p class="mb-4 text-body-sm text-gray-500">
                Variasi tombol yang ditentukan oleh <span class="text-primary-500">variant</span>. Gunakan <span class="text-primary-500">filled</span> untuk aksi utama
                yang perlu mendapat perhatian lebih, dan <span class="text-primary-500">outline</span> untuk tindakan pendukung yang
                tetap terlihat, namun tidak terlalu dominan.
            </p>

            <div class="mb-4 grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Filled">
                    <x-ceplok::button variant="filled">Simpan Perubahan</x-ceplok::button>
                </x-docs.demo>
                <x-docs.demo label="Outline">
                    <x-ceplok::button variant="outline">Lihat Detail</x-ceplok::button>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::button
                    size="base"
                    ⟦variant⟧="outline"
                >
                    Lihat Detail
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="themes" title="Themes">
            <p class="mb-6 text-body-sm text-gray-500">
                Tema digunakan untuk membedakan konteks dan tingkat prioritas suatu aksi.
            </p>

            <div class="mb-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($themes as [$theme, $label, $description])
                    <div>
                        <x-ceplok::button :theme="$theme">{{ $label }}</x-ceplok::button>
                        <p class="mt-3 text-sm text-gray-600">
                            <span class="text-primary-500">{{ $label }}</span> {{ $description }}
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
                    ⟦tone⟧="light"
                >
                    Green
                </x-ceplok::button>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <livewire:playground.button />

        <x-docs.section id="properties" title="Properties">
            <p class="mb-6 text-body-sm text-gray-500">
                Referensi semua prop yang tersedia. Digunakan untuk bisa memahami cara kerja komponen
                tanpa harus menebak implementasinya dari kode. Prop pilihan juga menerima enum PHP dari
                <code class="text-xs font-bold text-gray-700">CeplokUi\DesignKit\Enums</code>, mis. <code class="text-xs font-bold text-gray-700">ButtonSize::Xl</code>.
            </p>

            <x-docs.props-table :rows="$props" min-width="46rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
