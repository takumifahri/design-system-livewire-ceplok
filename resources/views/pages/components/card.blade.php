@php
    $image = '/images/card-sample.svg';
    $title = 'Ceplok Design System Card Desktop';
    $titleMobile = 'Ceplok Design System Card Mobile';
    $description = 'Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.';

    $props = [
        ['image', 'string | null', 'null', 'URL gambar. Dikosongkan untuk varian tanpa gambar.'],
        ['image-alt', 'string', "''", 'Teks alternatif gambar.'],
        ['title', 'string | slot', 'null', 'Judul kartu.'],
        ['description', 'string | slot', 'null', 'Deskripsi singkat.'],
        ['href', 'string | null', 'null', 'Mengaktifkan varian tautan bila diisi.'],
        ['link-label', 'string | null', 'Selengkapnya', 'Teks tautan, dipakai bersama href.'],
        ['actions', 'slot', '—', 'Slot untuk satu atau beberapa tombol.'],
        ['dark-mode', 'bool', 'false', 'Tampilan gelap: kartu gray-700, judul putih, deskripsi gray-300, tautan primary-400. Tombol di actions tidak ikut diubah.'],
        ['slot', 'slot', '—', 'Isi tambahan di akhir badan kartu.'],
    ];
@endphp

<x-docs.layout title="Card">
    <x-docs.page
        title="Card"
        description="Wadah ringkas untuk menampilkan satu unit informasi: gambar, judul, deskripsi, dan aksi. Setiap bagian bersifat opsional, sehingga seluruh varian pada desain terbentuk dari komponen yang sama."
        :toc="[
            'varian' => 'Varian',
            'image' => 'image · image-alt',
            'title' => 'title · description',
            'href' => 'href · link-label',
            'actions' => 'actions',
            'dark-mode' => 'Dark mode',
            'lebar' => 'Lebar kartu',
            'playground' => 'Playground',
            'penggunaan' => 'Penggunaan',
            'properties' => 'Properties',
        ]"
    >
        <x-docs.section id="varian" title="Varian">
            <x-docs.lead>
                Keempatnya komponen yang sama — tidak ada prop varian sama sekali. Yang
                membedakan hanya bagian mana yang diisi: lepas <span class="text-primary-500">image</span> untuk kartu
                tanpa gambar, lepas <span class="text-primary-500">actions</span> untuk kartu tanpa tombol, isi
                <span class="text-primary-500">href</span> untuk mengganti tombol dengan satu tautan. Bagian yang
                tidak diisi tidak dirender, bukan disembunyikan lewat CSS.
            </x-docs.lead>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Default">
                    <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description">
                        <x-slot:actions><x-docs.card-button /></x-slot:actions>
                    </x-ceplok::card>
                </x-docs.demo>
                <x-docs.demo label="Tanpa gambar">
                    <x-ceplok::card :title="$title" :description="$description">
                        <x-slot:actions><x-docs.card-button /></x-slot:actions>
                    </x-ceplok::card>
                </x-docs.demo>
                <x-docs.demo label="Tanpa tombol">
                    <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description" />
                </x-docs.demo>
                <x-docs.demo label="Dengan tautan">
                    <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description" href="#" link-label="See our guideline" />
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::card image="…" title="…" description="…">
                    <x-slot:actions><x-ceplok::button size="s">…</x-ceplok::button></x-slot:actions>
                </x-ceplok::card>

                <x-ceplok::card title="…" description="…">
                    <x-slot:actions><x-ceplok::button size="s">…</x-ceplok::button></x-slot:actions>
                </x-ceplok::card>

                <x-ceplok::card image="…" title="…" description="…" />

                <x-ceplok::card image="…" title="…" description="…" href="/panduan" />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="image" title="image · image-alt">
            <x-docs.lead>
                <span class="text-primary-500">image</span> berisi URL gambar yang mengisi bagian atas kartu; rasionya
                mengikuti lebar kartu — 16:9 di kolom lebar, 5:4 di bawah 320px.
                <span class="text-primary-500">image-alt</span> jadi teks alternatifnya, dan boleh string kosong bila
                gambarnya murni dekoratif. Tanpa <span class="text-primary-500">image</span>, kartu langsung dimulai
                dari judul.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Dengan gambar">
                    <x-ceplok::card :image="$image" image-alt="Suasana kerja tim" :title="$title" :description="$description" />
                </x-docs.demo>
                <x-docs.demo label="Tanpa gambar">
                    <x-ceplok::card :title="$title" :description="$description" />
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::card
                    ⟦*image⟧="/images/card-sample.svg"
                    ⟦*image-alt⟧="Suasana kerja tim"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="title" title="title · description">
            <x-docs.lead>
                Dua-duanya bisa berupa prop atau slot (<span class="text-primary-500">&lt;x-slot:title&gt;</span>), jadi boleh disisipi elemen.
                Ukuran judul mengikuti lebar kartu: 20px di kolom lebar, turun ke 16px di kolom
                sempit. Deskripsi bersifat opsional — kartu dengan judul saja tetap
                rapi karena jaraknya diatur per bagian, bukan lewat margin tetap.
            </x-docs.lead>
            <x-docs.demo>
                <div class="mx-auto w-full max-w-[384px]">
                    <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::card
                    ⟦*title⟧="Ceplok Design System Card Desktop"
                    ⟦*description⟧="Here are the biggest enterprise technology acquisitions of 2021."
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="href" title="href · link-label">
            <x-docs.lead>
                Mengisi <span class="text-primary-500">href</span> mengubah bagian bawah kartu jadi satu tautan, bukan
                deretan tombol. <span class="text-primary-500">link-label</span> menentukan teksnya dan berlaku hanya
                bersama <span class="text-primary-500">href</span>; bila dikosongkan, teksnya jatuh ke bawaan
                <span class="text-primary-500">Selengkapnya</span>.
            </x-docs.lead>
            <x-docs.demo>
                <div class="mx-auto w-full max-w-[384px]">
                    <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description" href="#" link-label="See our guideline" />
                </div>
            </x-docs.demo>
            <x-docs.code>
                @verbatim
                <x-ceplok::card
                    ⟦*href⟧="/panduan"
                    ⟦*link-label⟧="See our guideline"
                />
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="actions" title="actions">
            <x-docs.lead>
                Slot bebas di bagian bawah kartu — isi satu tombol, dua tombol, atau
                elemen lain. Card tidak mengatur ukuran maupun varian tombolnya, jadi
                keputusan itu tetap di tangan halaman yang memakainya. Bila kosong,
                bagian bawah kartu ditiadakan.
            </x-docs.lead>
            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Satu tombol">
                    <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description">
                        <x-slot:actions><x-docs.card-button /></x-slot:actions>
                    </x-ceplok::card>
                </x-docs.demo>
                <x-docs.demo label="Dua tombol">
                    <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description">
                        <x-slot:actions>
                            <x-docs.card-button variant="secondary" />
                            <x-docs.card-button />
                        </x-slot:actions>
                    </x-ceplok::card>
                </x-docs.demo>
            </div>
            <x-docs.code>
                @verbatim
                <x-ceplok::card>
                    <x-slot:⟦*actions⟧>
                        <x-ceplok::button size="s">Button text</x-ceplok::button>
                        <x-ceplok::button size="s">Button text</x-ceplok::button>
                    </x-slot:actions>
                </x-ceplok::card>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="dark-mode" title="Dark mode">
            <x-docs.lead>
                Isi <span class="text-primary-500">dark-mode</span> untuk halaman berlatar gelap. Kartu berubah ke
                gray-700, judul jadi putih, deskripsi gray-300, dan tautan primary-400
                supaya tetap terbaca. Gambar sedikit diredupkan. Semua varian di atas
                tetap berlaku, termasuk ukuran mobile. Tombol di <span class="text-primary-500">actions</span> tidak
                ikut diubah. Tombol outline perlu diberi warna primary-400 sendiri,
                karena primary-700 bawaannya hampir tidak terlihat di kartu gelap.
            </x-docs.lead>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-docs.demo label="Dua tombol" dark>
                    <x-ceplok::card dark-mode :image="$image" image-alt="" :title="$title" :description="$description">
                        <x-slot:actions>
                            <x-docs.card-button variant="secondary" dark />
                            <x-docs.card-button />
                        </x-slot:actions>
                    </x-ceplok::card>
                </x-docs.demo>
                <x-docs.demo label="Satu tombol" dark>
                    <x-ceplok::card dark-mode :image="$image" image-alt="" :title="$title" :description="$description">
                        <x-slot:actions><x-docs.card-button /></x-slot:actions>
                    </x-ceplok::card>
                </x-docs.demo>
                <x-docs.demo label="Dengan tautan" dark>
                    <x-ceplok::card dark-mode :image="$image" image-alt="" :title="$title" :description="$description" href="#" link-label="See our guideline" />
                </x-docs.demo>
                <x-docs.demo label="Tanpa gambar" dark>
                    <x-ceplok::card dark-mode :title="$title" :description="$description">
                        <x-slot:actions><x-docs.card-button /></x-slot:actions>
                    </x-ceplok::card>
                </x-docs.demo>
                <x-docs.demo label="Tanpa tombol" dark>
                    <x-ceplok::card dark-mode :image="$image" image-alt="" :title="$title" :description="$description" />
                </x-docs.demo>
            </div>

            <div class="mt-5">
                <x-docs.demo label="Mobile · kolom 238px" dark>
                    <div class="flex flex-wrap items-start justify-center gap-5">
                        @foreach ([[true, 'link'], [false, 'one'], [true, 'none'], [false, 'none']] as [$withImage, $action])
                            <div class="w-59.5 max-w-full">
                                <x-ceplok::card
                                    dark-mode
                                    :image="$withImage ? $image : null"
                                    image-alt=""
                                    :title="$titleMobile"
                                    :description="$description"
                                    :href="$action === 'link' ? '#' : null"
                                    :link-label="$action === 'link' ? 'See our guideline' : null"
                                >
                                    @if ($action === 'one')
                                        <x-slot:actions><x-docs.card-button /></x-slot:actions>
                                    @endif
                                </x-ceplok::card>
                            </div>
                        @endforeach
                    </div>
                </x-docs.demo>
            </div>

            <x-docs.code>
                @verbatim
                <x-ceplok::card
                    ⟦*dark-mode⟧
                    image="…"
                    title="…"
                    description="…"
                >
                    <x-slot:actions>
                        <x-ceplok::button variant="outline" size="s"
                            class="border-primary-400! text-primary-400! hover:bg-primary-400/10!">
                            Button text
                        </x-ceplok::button>
                        <x-ceplok::button size="s">Button text</x-ceplok::button>
                    </x-slot:actions>
                </x-ceplok::card>
                @endverbatim
            </x-docs.code>

            <h3 class="mt-10 mb-2 text-heading-4 font-bold text-gray-900">Ikut tema pembungkus</h3>
            <x-docs.lead>
                Tanpa <span class="text-primary-500">dark-mode</span>, kartu mengikuti tema elemen di sekitarnya. Pasang
                <span class="text-primary-500">data-theme="dark"</span> sekali di pembungkus, semua kartu di dalamnya ikut gelap.
                Di dalam area gelap, <span class="text-primary-500">data-theme="light"</span> mengembalikan satu bagian ke terang.
            </x-docs.lead>

            <x-docs.demo label='Pembungkus data-theme="dark", kartu tanpa prop' dark>
                <div data-theme="dark" class="grid gap-5 sm:grid-cols-3">
                    <x-ceplok::card :title="$title" :description="$description" href="#" link-label="See our guideline" />
                    <x-ceplok::card :title="$title" :description="$description" />
                    <div data-theme="light">
                        <x-ceplok::card title="Tetap terang" description='Dibungkus data-theme="light".' />
                    </div>
                </div>
            </x-docs.demo>

            <x-docs.code>
                @verbatim
                <section ⟦*data-theme="dark"⟧>
                    <x-ceplok::card title="…" />       {{-- gelap --}}
                    <x-ceplok::card title="…" />       {{-- gelap --}}
                    <div ⟦*data-theme="light"⟧>
                        <x-ceplok::card title="…" />   {{-- terang --}}
                    </div>
                </section>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="lebar" title="Lebar kartu">
            <x-docs.lead>
                Card sengaja tidak punya prop ukuran dan tidak diberi lebar bawaan —
                lebarnya urusan tata letak. Empat kartu di bawah ini ditulis dengan
                props yang sama persis; yang berbeda hanya lebar kolomnya, dan kartu
                menyesuaikan diri lewat <em>container query</em>: di bawah 320px
                gambarnya jadi 5:4, padding menyusut ke 16px, judul turun ke 16px; di
                atasnya gambar 16:9, padding 24px, judul 20px.
            </x-docs.lead>

            <div class="rounded-2xl border border-border bg-surface-subtle p-5 sm:p-8">
                <div class="flex flex-wrap items-start justify-center gap-5">
                    @foreach ([240, 300, 384, 520] as $width)
                        <div style="width: {{ $width }}px" class="max-w-full">
                            <p class="mb-2 text-xs font-bold text-primary-700">kolom {{ $width }}px</p>
                            <x-ceplok::card :image="$image" image-alt="" :title="$title" :description="$description">
                                <x-slot:actions><x-docs.card-button /></x-slot:actions>
                            </x-ceplok::card>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="mt-4 text-body-sm text-gray-500">
                Di halaman formulir kartu perlu selebar kolomnya, sementara di daftar ringkas ia perlu menyempit
                mengikuti grid. Menentukan lebar adalah tugas tata letak, bukan tugas
                kartu.
            </p>
        </x-docs.section>

        <livewire:playground.card />

        <x-docs.section id="properties" title="Properties">
            <x-docs.lead>
                Rangkuman seluruh prop. Semuanya opsional — bagian yang tidak diisi
                tidak dirender, bukan disembunyikan lewat CSS.
            </x-docs.lead>
            <x-docs.props-table :rows="$props" />
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
