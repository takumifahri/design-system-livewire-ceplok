@php
    use App\Docs\Theme;

    // Empat varian lebar. `ratio` menggambar bilah perbandingan — skalanya relatif
    // terhadap varian terlebar (1440px) supaya selisihnya langsung terlihat.
    $sizes = [
        ['prose', '720px', 720 / 1440, 'Teks panjang & formulir', 'Baris berhenti di sekitar 75 karakter, jarak yang paling nyaman dibaca. Pakai untuk artikel, syarat layanan, dan formulir satu kolom.'],
        ['default', '1126px', 1126 / 1440, 'Bawaan — halaman layanan', 'Angka dari spesifikasi desain. Dipakai bila tidak ada alasan khusus untuk berbeda, supaya semua halaman berhenti di garis yang sama.'],
        ['wide', '1440px', 1, 'Tabel & dasbor', 'Ruang tambahan untuk tabel berkolom banyak atau grid empat kartu ke atas yang terasa sesak di 1126px.'],
        ['full', 'tanpa batas', 1, 'Mengisi penuh induk', 'Tidak ada batas lebar — yang tersisa hanya padding kiri-kanan. Untuk hero berwarna, peta, atau kanvas yang memang harus melebar.'],
    ];

    // Tiga hal yang benar-benar dikerjakan Container di balik layar.
    $howItWorks = [
        ['Membatasi lebar', 'max-w-[380px] · @[640px]:max-w-[1126px]', 'Konten berhenti di angka itu. Di ruang yang lebih lebar, sisanya dibiarkan kosong supaya baris teks tidak melar dan tetap enak dibaca.'],
        ['Memusatkan', 'mx-auto · w-full', 'Margin kiri-kanan otomatis membagi sisa ruang rata. Selama ruangnya masih lebih sempit dari batas, container ikut menyusut penuh.'],
        ['Memberi padding', 'px-5 → @[1280px]:px-14', 'Jarak ke tepi tumbuh bertahap mengikuti ambang, jadi konten tidak pernah menempel di pinggir pada layar kecil.'],
    ];

    $specs = [
        ['Sempit', '< 640px', 'max-w 380px · rounded-xl · padding kiri-kanan 20px (kecuali size="full")'],
        ['Sedang', '≥ 640px', 'max-w mengikuti varian — bawaan 1126px · sudut rata · padding 32px'],
        ['Lebar', '≥ 1024px / 1280px', 'max-w varian tetap · padding 48px, lalu 56px di ≥ 1280px'],
        ['Lebar isi', 'Semua ukuran', 'w-full sampai batas max-w — tidak pernah melebihi ruangnya'],
        ['Posisi', 'Semua ukuran', 'mx-auto — selalu terpusat di ruang yang tersedia'],
        ['Tinggi', 'Semua ukuran', 'auto — mengikuti tinggi konten, tidak pernah dipaksa'],
    ];

    $props = [
        ['size', '"prose" | "default" | "wide" | "full"', 'default', 'Batas lebar konten: 720px, 1126px, 1440px, atau tanpa batas. Hanya angka itu yang berubah antar varian.'],
        ['as', 'string', 'div', 'Elemen HTML pembungkus. Pakai section, main, atau footer agar struktur halaman tetap semantik.'],
        ['padded', 'bool', 'true', 'Padding horizontal bawaan (20px → 56px). Matikan bila elemen induk sudah punya padding sendiri.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: latar gray-700 dan teks gray-300 di elemen dalam. Container di dalam pembungkus data-theme="dark" juga ikut gelap. Tanpa keduanya Container transparan.'],
        ['class', 'string', '—', 'Kelas untuk elemen luar: jarak vertikal (py-*), flex-1, atau max-w-* bila ingin batas yang lebih sempit.'],
        ['slot', 'slot', '—', 'Konten yang dibatasi lebarnya — teks, grid, maupun komponen lain.'],
        ['…atribut', 'HTML', '—', 'Seluruh atribut HTML standar diteruskan ke elemen luar (id, aria-*, data-*, wire:*, …).'],
    ];

    // Contoh di halaman ini mengikuti mode docs: pratinjau memakai `dark-mode`
    // dan cuplikannya menuliskannya lewat penanda ⟨…⟩. Bagian Dark mode tetap
    // gelap di kedua mode (lihat App\Docs\Theme).
    $docsDark = Theme::dark();
@endphp

{{--
    Kotak contoh: "ruang yang tersedia" dan isi berbingkai putus-putus, terang
    atau gelap. Isi gelap transparan supaya latar Container terlihat utuh di
    belakangnya, bukan tertutup kotak isi dan tinggal pita di kiri-kanan.
--}}
@php
    $room = fn (bool $dark = false) => $dark
        ? ['stage' => 'border-gray-800 bg-gray-900', 'room' => 'bg-gray-900 ring-gray-700', 'isi' => 'border-primary-400/70 bg-transparent', 'isiLabel' => 'text-primary-300']
        : ['stage' => 'border-border bg-surface-subtle', 'room' => 'bg-primary-50 ring-primary-100', 'isi' => 'border-primary-300 bg-surface', 'isiLabel' => 'text-primary-700'];
@endphp

<x-docs.layout title="Container">
    <x-docs.page
        title="Container"
        description="Pembungkus paling luar untuk isi halaman. Tugasnya satu: menjaga konten berhenti di lebar yang sama di mana pun ia dipakai — 380px saat ruangnya sempit dan 1126px saat lapang — lalu memusatkannya. Tingginya selalu mengikuti konten."
        :toc="[
            'size' => 'size',
            'cara-kerja' => 'Cara kerja',
            'slot' => 'slot',
            'as' => 'as',
            'padded' => 'padded',
            'class' => 'class',
            'dark-mode' => 'Dark mode',
            'spesifikasi' => 'Angka per ambang',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="size" title="size">
            <x-docs.lead>
                Memilih sampai mana konten boleh melar di ruang lapang — itu saja yang
                berbeda antar varian. Pemusatan, padding, dan perilaku di ruang sempit
                persis sama di keempatnya. Bilah di bawah digambar dengan skala yang
                sama, 1440px sebagai patokan penuh.
            </x-docs.lead>
            <ul class="ds-card divide-y divide-border">
                @foreach ($sizes as [$value, $max, $ratio, $headline, $use])
                    <li class="grid gap-3 p-5 sm:grid-cols-[11rem_1fr] sm:gap-6">
                        <div>
                            <p class="text-sm font-black text-gray-900 dark:text-white">
                                size="{{ $value }}"
                                @if ($value === 'default')
                                    <span class="ml-2 align-middle text-[10px] font-bold tracking-wide text-gray-500 dark:text-gray-400 uppercase">bawaan</span>
                                @endif
                            </p>
                            <p class="mt-0.5 text-xs font-bold text-primary-700 dark:text-primary-400">{{ $max }}</p>
                            <p class="mt-0.5 text-body-sm text-gray-500 dark:text-gray-400">{{ $headline }}</p>
                        </div>
                        <div>
                            {{-- Bilah perbandingan: dekoratif, angkanya sudah ditulis di kiri. --}}
                            <div aria-hidden="true" class="h-8 w-full overflow-hidden rounded-lg bg-surface-subtle ring-1 ring-border dark:bg-gray-900 dark:ring-gray-700">
                                <div
                                    @class([
                                        'h-full rounded-lg ring-1 ring-primary-300',
                                        $value === 'full' ? 'bg-primary-100 dark:bg-primary-800 dark:ring-primary-600' : 'bg-primary-50 dark:bg-primary-900 dark:ring-primary-700',
                                    ])
                                    style="width: {{ round($ratio * 100, 4) }}%"
                                ></div>
                            </div>
                            <p class="mt-2 text-body-sm leading-6 text-gray-500 dark:text-gray-400">{{ $use }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
            <p class="mt-3 max-w-2xl text-body-sm leading-6 text-gray-500 dark:text-gray-400">
                <span class="text-primary-500 dark:text-primary-400">wide</span> dan <span class="text-primary-500 dark:text-primary-400">full</span> menggambar bilah yang sama panjang karena
                patokannya 1440px — bedanya baru terasa di ruang yang lebih lebar dari
                itu: <span class="text-primary-500 dark:text-primary-400">wide</span> berhenti, <span class="text-primary-500 dark:text-primary-400">full</span> terus mengikuti induknya.
                Cobalah di Playground dengan lebar Custom di atas 1440px.
            </p>
            <x-docs.code>
                @verbatim
                {{-- Bawaan — size="default" tidak perlu ditulis --}}
                <x-ceplok::container⟨ dark-mode⟩>
                    ...
                </x-ceplok::container>

                {{-- Teks panjang & formulir — berhenti di 720px --}}
                <x-ceplok::container ⟦*size="prose"⟧⟨ dark-mode⟩>
                    ...
                </x-ceplok::container>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="cara-kerja" title="Cara kerja">
            <x-docs.lead>
                Tiga hal ini sudah menempel di komponen — tidak perlu ditulis ulang
                setiap kali dipakai. Di luar ketiganya Container tidak menyentuh apa
                pun: tidak ada grid, flex, warna, atau jarak antar-anak, jadi isinya
                tetap mengatur dirinya sendiri.
            </x-docs.lead>
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($howItWorks as [$title, $classes, $detail])
                    <article class="ds-card p-5">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white">{{ $title }}</h3>
                        <p class="mt-1 text-xs font-bold text-primary-700 dark:text-primary-400">{{ $classes }}</p>
                        <p class="mt-1.5 text-body-sm leading-6 text-gray-500 dark:text-gray-400">{{ $detail }}</p>
                    </article>
                @endforeach
            </div>
        </x-docs.section>

        <x-docs.section id="slot" title="slot">
            <x-docs.lead>
                Isi yang lebarnya dibatasi — teks, grid, maupun komponen lain. Karena
                Container tidak mengatur tata letak isinya, apa pun yang dimasukkan
                tetap memegang susunannya sendiri; yang berubah cuma di mana ia
                berhenti.
            </x-docs.lead>
            <div @class(['rounded-2xl border p-4 sm:p-6', $room($docsDark)['stage']])>
                <div @class(['rounded-xl ring-1', $room($docsDark)['room']])>
                    <x-ceplok::container class="py-6" :dark-mode="$docsDark">
                        <div @class(['rounded-lg border-2 border-dashed px-4 py-6 text-center', $room($docsDark)['isi']])>
                            <p @class(['text-sm font-black', $room($docsDark)['isiLabel']])>slot</p>
                        </div>
                    </x-ceplok::container>
                </div>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::container⟨ dark-mode⟩>
                    <h1>Pendaftaran Penyelenggara Sistem Elektronik</h1>
                    <p>Lengkapi data pemohon dan sistem elektronik yang didaftarkan.</p>
                </x-ceplok::container>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="as" title="as">
            <x-docs.lead>
                Mengganti elemen HTML terluar tanpa mengubah perilaku apa pun.
                Bawaannya <span class="text-primary-500 dark:text-primary-400">div</span>; pakai <span class="text-primary-500 dark:text-primary-400">header</span>, <span class="text-primary-500 dark:text-primary-400">main</span>, <span class="text-primary-500 dark:text-primary-400">section</span>,
                atau <span class="text-primary-500 dark:text-primary-400">footer</span> supaya struktur halaman tetap terbaca oleh pembaca
                layar dan mesin pencari — bukan tumpukan div tanpa makna.
            </x-docs.lead>
            <div @class(['rounded-2xl border p-4 sm:p-6', $room($docsDark)['stage']])>
                <div @class(['rounded-xl ring-1', $room($docsDark)['room']])>
                    <x-ceplok::container as="section" class="py-6" :dark-mode="$docsDark">
                        <div @class(['rounded-lg border-2 border-dashed px-4 py-6 text-center', $room($docsDark)['isi']])>
                            <p @class(['text-sm font-black', $room($docsDark)['isiLabel']])>as="section"</p>
                        </div>
                    </x-ceplok::container>
                </div>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::container ⟦*as⟧="section"⟨ dark-mode⟩>
                    ...
                </x-ceplok::container>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="padded" title="padded">
            <x-docs.lead>
                Mematikan padding kiri-kanan bawaan. Dipakai saat elemen induk sudah
                punya padding sendiri — kalau tidak, jaraknya jadi dobel dan konten
                menyempit tanpa disadari. Jangan menimpanya dengan <span class="text-primary-500 dark:text-primary-400">px-0</span> lewat
                <span class="text-primary-500 dark:text-primary-400">class</span>: paddingnya hidup di elemen dalam, bukan elemen luar
                yang menerima <span class="text-primary-500 dark:text-primary-400">class</span>.
            </x-docs.lead>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ([[true, 'padded (bawaan)'], [false, ':padded="false"']] as [$padded, $label])
                    <div @class(['rounded-2xl border p-4 sm:p-6', $room($docsDark)['stage']])>
                        <div @class(['rounded-xl ring-1', $room($docsDark)['room']])>
                            <x-ceplok::container :padded="$padded" class="py-6" :dark-mode="$docsDark">
                                <div @class(['rounded-lg border-2 border-dashed px-4 py-6 text-center', $room($docsDark)['isi']])>
                                    <p @class(['text-sm font-black', $room($docsDark)['isiLabel']])>{{ $label }}</p>
                                </div>
                            </x-ceplok::container>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::container as="section" ⟦*:padded="false"⟧⟨ dark-mode⟩>
                    ...
                </x-ceplok::container>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="class" title="class">
            <x-docs.lead>
                Menempel di elemen luar, bukan elemen yang memegang lebar. Tiga
                pemakaian yang wajar: jarak vertikal (<span class="text-primary-500 dark:text-primary-400">py-*</span>) karena Container
                sengaja tidak punya jarak atas-bawah bawaan; warna latar yang ingin
                melebar penuh; dan <span class="text-primary-500 dark:text-primary-400">max-w-*</span> bila batasnya perlu lebih sempit dari
                bawaan.
            </x-docs.lead>
            <div @class(['rounded-2xl border p-4 sm:p-6', $room($docsDark)['stage']])>
                <div @class(['rounded-xl ring-1', $room($docsDark)['room']])>
                    <x-ceplok::container class="max-w-180 py-10" :dark-mode="$docsDark">
                        <div @class(['rounded-lg border-2 border-dashed px-4 py-6 text-center', $room($docsDark)['isi']])>
                            <p @class(['text-sm font-black', $room($docsDark)['isiLabel']])>class="max-w-[720px] py-10"</p>
                        </div>
                    </x-ceplok::container>
                </div>
            </div>
            <x-docs.code>
                @verbatim
                {{-- Jarak vertikal — Container tidak punya bawaannya --}}
                <x-ceplok::container class="⟦py-12 lg:py-16⟧"⟨ dark-mode⟩>
                    ...
                </x-ceplok::container>

                {{-- Batas lebih sempit dari bawaan --}}
                <x-ceplok::container class="⟦*max-w-[720px]⟧"⟨ dark-mode⟩>
                    ...
                </x-ceplok::container>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.mode-section>
            <x-slot:lead>
                Prop <span class="text-primary-500 dark:text-primary-400">dark-mode</span> memberi elemen dalam latar dari token
                <span class="text-primary-500 dark:text-primary-400">--color-container</span> (gray-700) dan teks bawaan gray-300. Lebar,
                padding, dan pemusatan tidak berubah. Di ruang sempit sudut rounded-xl
                ikut terlihat karena sekarang ada latarnya. Tanpa <span class="text-primary-500 dark:text-primary-400">dark-mode</span>,
                Container tetap transparan.
            </x-slot:lead>

            {{-- Keterangan tiga lapis di contoh, supaya jelas bidang mana Container-nya. --}}
            <ul class="mb-2 flex flex-wrap gap-x-6 gap-y-2 text-xs text-gray-500 dark:text-gray-400">
                <li class="inline-flex items-center gap-2">
                    <span class="size-3.5 rounded-sm bg-gray-900 ring-1 ring-gray-700"></span>
                    Halaman (panggung)
                </li>
                <li class="inline-flex items-center gap-2">
                    <span class="size-3.5 rounded-sm bg-container"></span>
                    Container
                </li>
                <li class="inline-flex items-center gap-2">
                    <span class="size-3.5 rounded-sm border-2 border-dashed border-primary-400"></span>
                    Isi (slot)
                </li>
            </ul>

            {{-- Bertumpuk, bukan berdampingan: berdampingan, tiap kotak cuma ±440px, jadi keduanya jatuh ke mode sempit. --}}
            <div class="grid gap-4">
                @foreach ([['Ruang lapang (≥ 640px)', null, 'sudut lurus, padding 32–56px, maks. 1126px'], ['Ruang sempit (< 640px)', 'mx-auto max-w-[380px]', 'rounded-xl, padding 20px, maks. 380px']] as [$caption, $narrow, $note])
                    <div>
                        <p class="mb-2 text-xs font-bold text-gray-500 dark:text-gray-400">{{ $caption }}</p>
                        <div @class(['rounded-2xl border p-4 sm:p-6', $room(true)['stage']])>
                            <div @class(['rounded-xl ring-1', $room(true)['room']])>
                                <div @class(['py-6', $narrow])>
                                    <x-ceplok::container dark-mode>
                                        <p class="pt-4 pb-3 text-[11px] font-bold tracking-[0.12em] text-gray-400 uppercase">
                                            Container <span class="font-medium tracking-normal normal-case">· {{ $note }}</span>
                                        </p>
                                        <div class="pb-4">
                                            <div class="rounded-lg border-2 border-dashed border-primary-400/70 bg-transparent px-4 py-6 text-center">
                                                <p class="text-sm font-black text-primary-300">Konten</p>
                                            </div>
                                        </div>
                                    </x-ceplok::container>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::container ⟦*dark-mode⟧ class="py-12">
                    ...
                </x-ceplok::container>
                @endverbatim
            </x-docs.code>
        </x-docs.mode-section>

        <x-docs.section id="spesifikasi" title="Angka per ambang">
            <x-docs.lead>
                Nilai pasti yang dipakai komponen, diukur dari lebar ruang yang
                tersedia — bukan lebar jendela, karena Container memakai
                <em>container query</em> seperti Footer dan Hero. Di halaman biasa
                hasilnya sama saja, tapi di dalam kolom sempit atau split view,
                Container tetap tahu diri.
            </x-docs.lead>
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($specs as [$title, $range, $detail])
                    <article class="ds-card p-5">
                        <h3 class="text-sm font-black text-gray-900 dark:text-white">{{ $title }}</h3>
                        <p class="mt-1 text-xs font-bold text-primary-700 dark:text-primary-400">{{ $range }}</p>
                        <p class="mt-1.5 text-body-sm leading-6 text-gray-500 dark:text-gray-400">{{ $detail }}</p>
                    </article>
                @endforeach
            </div>
        </x-docs.section>

        <livewire:playground.container :dark="$docsDark" />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Container merender elemen luar sebagai titik ukur <span class="text-primary-500 dark:text-primary-400">@container</span>,
                lalu elemen dalam yang memegang lebar dan padding. <span class="text-primary-500 dark:text-primary-400">class</span> dan
                atribut HTML menempel di elemen luar.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" min-width="44rem" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
