@php
    use App\Docs\Theme;

    // Pratinjau kartu overview tidak disertai cuplikan kode, jadi isinya ikut
    // memakai `dark-mode` di mode gelap docs dan latarnya ikut gelap.
    $docsDark = Theme::dark();
@endphp

<x-docs.layout title="Form">
    <x-docs.hero
        eyebrow="Form · Overview"
        title="Form"
        description="Komponen isian formulir — teks, pilihan, unggahan, dan kontrol pilihan. Semuanya memakai token warna, radius, dan jarak yang sama dengan Foundations."
    />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        {{-- grid-cols-1 (minmax(0,1fr)): tanpa itu kolom di bawah sm melebar mengikuti pratinjau terlebar. --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-docs.overview-card route="form.input-field" name="Input Field Form" desc="Isian teks satu baris, floating label, dan text area — lengkap dengan state dan warna per aplikasi." wide>
                {{--
                    Satu latar, dua kolom: Input Field dan Floating Label ditumpuk di
                    kiri, Text Area mengisi kolom kanan sepenuhnya. justify-between
                    mendorong kedua field kiri ke tepi atas dan bawah supaya tingginya
                    berakhir sejajar dengan Text Area di sebelahnya.

                    Floating Label sengaja diberi nilai — dalam keadaan kosong ia tak
                    terbedakan dari Input Field di atasnya, dan justru labelnya yang
                    naik itulah yang membedakan keduanya.
                --}}
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="flex flex-col justify-between gap-5">
                            <x-ceplok::input-field
                                label="Nama lengkap"
                                placeholder="Masukkan nama lengkap"
                                helper-text="Sesuai yang tertera pada KTP."
                                :icon="svg('ceplok-o-user', 'size-4')"
                                :dark-mode="$docsDark"
                            />
                            <x-ceplok::floating-label
                                label="Alamat surel"
                                type="email"
                                value="yermi@contoh.id"
                                :icon="svg('ceplok-o-envelope', 'size-4')"
                                :dark-mode="$docsDark"
                            />
                        </div>
                        <x-ceplok::text-area label="Keterangan" placeholder="Tulis keterangan tambahan di sini…" :dark-mode="$docsDark" />
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.select" name="Regular Select Form" desc="Daftar pilihan tunggal dengan tampilan yang selaras dengan Input Field.">
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <x-ceplok::select
                        label="Provinsi"
                        info="Pilih salah satu provinsi."
                        placeholder="Pilih Apapun Itu"
                        :options="[['value' => 'dki', 'label' => 'DKI Jakarta'], ['value' => 'jabar', 'label' => 'Jawa Barat']]"
                        :dark-mode="$docsDark"
                    />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.search" name="Search Form" desc="Kolom pencarian dengan tombol cari, plus varian bersama dropdown kategori.">
                {{--
                    Varian mobile yang dipakai di sini, bukan default: kartunya hanya
                    selebar setengah kisi, dan pada lebar itu varian desktop membuat
                    placeholder-nya terpotong sebelum tombol Cari.
                --}}
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <x-ceplok::search platform="mobile" placeholder="Search Civitas…" :dark-mode="$docsDark" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.upload" name="Upload Form" desc="Pemilih berkas: satu baris tombol dengan nama berkas, atau area seret-lepas." wide>
                {{--
                    Satu latar, dua kolom: baris pemilih berkas di kiri, area seret-lepas
                    di kanan — dua bentuk yang disebut di deskripsi kartunya. Barisnya
                    dipusatkan secara vertikal: tingginya cuma seperempat area attach di
                    sebelahnya, dan rata atas membuatnya terbaca menggantung di sudut.
                --}}
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div class="flex flex-col justify-center">
                            <x-ceplok::upload label="Unggah dokumen" helper-text="PDF atau JPG, maksimal 2 MB." :dark-mode="$docsDark" />
                        </div>
                        <x-ceplok::upload type="attach" :dark-mode="$docsDark" />
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.radio" name="Radio Button" desc="Pilihan tunggal dari beberapa opsi yang saling meniadakan.">
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <div class="space-y-3">
                        <x-ceplok::radio name="ds-overview-radio" label="Warga negara Indonesia" checked :dark-mode="$docsDark" />
                        <x-ceplok::radio name="ds-overview-radio" label="Warga negara asing" :dark-mode="$docsDark" />
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.toggle" name="Toggle Button" desc="Sakelar untuk menyalakan atau mematikan satu pengaturan.">
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <div class="space-y-3">
                        <x-ceplok::toggle label="Notifikasi aktif" checked :dark-mode="$docsDark" />
                        <x-ceplok::toggle label="Notifikasi nonaktif" :dark-mode="$docsDark" />
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.checkbox" name="Checkbox" desc="Pilihan ganda yang bisa dicentang secara mandiri.">
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <div class="flex flex-wrap gap-6">
                        <x-ceplok::checkbox label="Saya menyetujui syarat" checked :dark-mode="$docsDark" />
                        <x-ceplok::checkbox label="Kirim salinan ke email" :dark-mode="$docsDark" />
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.datepicker" name="Datepicker" desc="Pemilih tanggal dengan kalender: satu tanggal, pintasan periode, atau rentang dua kalender.">
                <div class="rounded-xl bg-surface-subtle p-5 dark:bg-gray-900">
                    <x-ceplok::datepicker label="Tanggal pengajuan" value="2021-06-25" :dark-mode="$docsDark" />
                </div>
            </x-docs.overview-card>
        </div>
    </div>
</x-docs.layout>
