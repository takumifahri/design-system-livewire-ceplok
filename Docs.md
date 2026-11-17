# Aplikasi dokumentasi

Situs dokumentasi Ceplok Design Kit versi Livewire. Isinya port dokumentasi
kit React (`react-design-system/src/docs`) ke Laravel 13 dan Livewire 4:
halaman, urutan, teks, dan demonya mengikuti docs React, dengan contoh kode
Blade alih-alih JSX dan bagian **Livewire** tambahan di halaman komponen yang
punya integrasi Livewire.

```text
app/Docs/Navigation.php                  sumber tunggal rail, panel samping, pencarian, menu footer
app/Docs/Code.php                        pengolah cuplikan kode: dedent, sorotan, nilai hidup
routes/web.php                           Route::view untuk setiap halaman
resources/views/pages/                   home, components/*, form/*, foundations/*
resources/views/components/docs/         komponen situs: <x-docs.layout>, <x-docs.page>, …
resources/views/components/playground/   Playground Livewire satu berkas (⚡nama.blade.php)
resources/js/docs/                       perilaku situs: salin, layout, pencarian, TOC, motion, Playground Dropdown
resources/css/docs.css                   gaya khusus situs, dimuat setelah stylesheet kit
public/images/                           aset contoh, disalin dari docs React
```

## Menambah halaman

1. Daftarkan rute di [routes/web.php](routes/web.php), mis.
   `Route::view('/select', 'pages.components.select')->name('components.select')`.
2. Tambahkan item ke `Navigation::sidebars()` beserta kata kunci berbahasa
   Indonesia untuk pencarian, mis. `'keywords' => 'pilihan dropdown'`. Rail,
   panel samping, pencarian Ctrl/⌘K, dan menu footer otomatis mengikuti.
   Sub-halaman ditulis di `children` induknya, mis. Input Field di bawah
   Input Field Form: panel samping menariknya masuk di bawah induknya,
   pencarian menulis letaknya "Form › Input Field Form", dan angka di Home
   menghitung sub-halaman, bukan induknya.
3. Buat halamannya:

   ```blade
   <x-docs.layout title="Select">
       <x-docs.page
           eyebrow="Components · Select"
           title="Select"
           description="…"
           :toc="['sizes' => 'Sizes', 'properties' => 'Properties']"
       >
           <x-docs.section id="sizes" title="Sizes">
               <x-docs.demo label="sm">…</x-docs.demo>
               <x-docs.code>…</x-docs.code>
           </x-docs.section>
       </x-docs.page>
   </x-docs.layout>
   ```

4. Tambahkan halamannya ke data provider di `tests/Feature/Docs/PagesTest.php`.
   `NavigationTest` gagal bila rute baru belum masuk navigasi.

## Cuplikan kode

`<x-docs.code>` menampilkan kode dengan tombol salin. Kodenya dari prop `code`
atau dari slot:

```blade
<x-docs.code>
    @verbatim
    <x-ceplok::badge ⟦variant⟧="success">Aktif</x-ceplok::badge>
    @endverbatim
</x-docs.code>
```

- Isi slot **wajib** dibungkus `@verbatim`, supaya tag `<x-…>` dan `{{ }}` di
  dalamnya tidak ikut dikompilasi.
- Indentasi yang sama di semua baris dibuang, jadi kode boleh ditulis
  menjorok mengikuti markup di sekitarnya.
- `⟦…⟧` menyorot bagian kode; `⟦*…⟧` menyorot tebal, untuk prop yang sedang
  dikendalikan Playground.
- `⟪ekspresi|cadangan⟫` menjadi nilai hidup: ekspresi Alpine mengisi angkanya
  di browser, dan teks cadangan dirender server lebih dulu.
- `⟨…⟩` hanya tampil di mode gelap docs dan `⟨!…⟩` hanya di mode terang.
  Baris yang tinggal berisi penanda yang dibuang ikut dihapus. Lihat bagian
  "Mode gelap" di bawah.
- Di dalam ekspresi PHP, mis. kunci array pada `:items`, sorotan ditulis
  lewat prop `mark="'attributes'"`, bukan `⟦…⟧`. Ekstensi PHP di editor tetap
  mem-parse nilai atribut itu walau dibungkus `@verbatim`, dan penanda di
  tengah ekspresi membuatnya melaporkan error sintaks.

Dua jebakan Blade yang perlu diingat saat menulis halaman:

- Teks tag komponen dikompilasi di mana pun ia muncul, termasuk di dalam
  nilai atribut. Teks seperti `<x-ceplok::nama>` untuk prop `title` atau tabel
  props ditulis di blok `@php`, yang diekstrak sebelum kompilasi.
- Teks direktif seperti `@svg(...)` juga dikompilasi, bahkan di dalam
  `{!! !!}`. Rakit teks semacam itu di blok `@php` juga.

## Playground

Playground adalah komponen Livewire satu berkas di
`resources/views/components/playground/⚡nama.blade.php`, dipasang dengan
`<livewire:playground.nama />`.

- Setiap kontrol mengubah satu properti publik. Properti berpilihan memakai
  enum kit (`public ButtonVariant $variant`), jadi nilai yang tidak sah ditolak
  Livewire.
- `<x-docs.segmented model="variant" :value="$variant" :options="…">`
  mengubah properti lewat `$set`. Dengan `alpine`, pilihannya berganti di
  browser tanpa request, untuk kontrol yang murni tampilan.
- Kode di blok Penggunaan dirakit di metode `#[Computed] usage()` dengan
  penanda sorotan yang sama.
- Pasang `wire:key` yang memuat state yang relevan bila komponen kit perlu
  dipasang ulang dengan state Alpine yang baru, mis. Toast yang dimunculkan
  lagi.
- `$wire.nama` mendahulukan properti di atas metode yang namanya sama. Beri
  nama yang berbeda, mis. properti `$closed` untuk metode `dismissed()`.
- `wire:key` juga dibutuhkan bila kontrol mengganti bentuk komponen kit,
  mis. type Upload atau varian kategori Search: pendengar Alpine-nya dipasang
  sekali pada elemen yang lama.
- Contoh di bagian **Livewire** tiap halaman adalah komponen satu berkas juga
  (`⚡nama-livewire`, `⚡select-region`, …), jadi perilakunya ikut diuji
  `tests/Feature/Docs/FormPlaygroundTest.php`.

## Perilaku di browser

- Panel samping desktop bisa ditutup. Keadaannya disimpan di cookie
  `ceplok_docs_panel` yang ditulis JavaScript, jadi cookie ini dikecualikan
  dari enkripsi di [bootstrap/app.php](bootstrap/app.php). Server membacanya,
  sehingga halaman pertama sudah tampil dengan lebar panel yang benar.
- Pencarian halaman dibuka dengan Ctrl/⌘K atau `/`, memakai `<dialog>`
  bawaan browser.
- Semua tautan navigasi memakai `wire:navigate`, jadi berpindah halaman tidak
  memuat ulang aset.
- Animasi di Home (reveal, parallax, tilt, dan lainnya) dimatikan bila
  pengguna memilih `prefers-reduced-motion`.

## Mode gelap

Mode gelap situs dokumentasi masih uji coba dan baru dipasang di sebagian
halaman, mengikuti docs React. Daftar rutenya ada di
[app/Docs/Theme.php](app/Docs/Theme.php). Di halaman lain situs tetap terang
dan tombol bulan/matahari di rail serta top bar mobile diredupkan.

- Pilihan pembaca disimpan di cookie `ceplok_docs_theme` yang ditulis
  JavaScript ([resources/js/docs/theme.js](resources/js/docs/theme.js)), jadi
  cookie ini juga dikecualikan dari enkripsi. Server membacanya dan memasang
  `data-docs-theme="dark"` di `<html>` sejak HTML pertama.
- Varian `dark:` situs mengikuti atribut itu (lihat
  [resources/css/docs.css](resources/css/docs.css)), bukan
  `prefers-color-scheme`. Sengaja bukan `data-theme`: atribut itu milik
  komponen kit dan mengganti token untuk seluruh isinya.
- Tombolnya memuat ulang halaman setelah cookie ditulis, karena pratinjau
  dirender server dengan `dark-mode`. Muat ulang juga membuang snapshot
  wire:navigate yang masih memakai mode lama.
- Portal Dashboard ikut memakai pilihan yang sama. Tombol tema di header-nya
  hanya mengirim event `dark-mode-change`; `<x-docs.app-frame>` yang
  menyimpannya.

Menambah halaman ke mode gelap:

1. Beri elemen halamannya varian `dark:`, mis. `text-gray-500 dark:text-gray-400`.
2. Ambil `$docsDark = Theme::dark();` di blok `@php` halaman, lalu beri
   `:dark-mode="$docsDark"` pada komponen kit di pratinjau.
3. Tulis `dark-mode` di cuplikannya lewat penanda: `⟨ dark-mode⟩` untuk tag
   satu baris, atau baris `⟨dark-mode⟩` untuk prop yang ditulis satu per baris.
4. Ganti bagian Dark mode dengan `<x-docs.mode-section name="…">`. Bagian ini
   bertukar menjadi Light mode selama docs gelap: komponennya memakai
   `:dark-mode="! $docsDark"` di dalam
   `<x-docs.demo :dark="! $docsDark" :light="$docsDark">`, dan cuplikannya
   memakai `⟨! ⟦dark-mode⟧⟩` (atau baris `⟨!⟦dark-mode⟧⟩`). Label di daftar
   isinya ditukar `<x-docs.page>`.
5. Pasang `:dark="$docsDark"` pada Playground dan contoh Livewire-nya, supaya
   mode awalnya mengikuti docs.
6. Daftarkan rutenya di `Theme::PAGES`.

Button kit Livewire belum punya prop `dark-mode` seperti di kit React, jadi
tombol di pratinjau tetap memakai warna terang.

## Tes

```bash
php artisan test
```

| Folder | Isi |
| --- | --- |
| `tests/Feature/Kit` | Markup dan class setiap komponen kit, pemeriksa prop, set ikon, Blaze, nilai awal `wire:model`, ID kontrol form, dan `x-data` yang tetap antar-render |
| `tests/Feature/Docs` | Setiap halaman docs (termasuk id yang unik per halaman), navigasi bertingkat, dan Playground Livewire |
| `tests/Unit/Docs` | Pengolah cuplikan kode |
| `tests/Unit/Kit` | Pembantu kit: daftar opsi Select, tanggal Datepicker, dan class yang diikat Alpine |

Tes komponen memeriksa markup dengan parser HTML5 bawaan PHP 8.4
(`Dom\HTMLDocument`) lewat helper di [tests/TestCase.php](tests/TestCase.php),
bukan mencocokkan potongan string.
