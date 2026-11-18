# Changelog

- Docs Avatar: menambahkan section dan contoh integrasi state pengguna dengan Livewire.

Semua perubahan penting pada `ceplok-ui/design-kit-livewire` dicatat di sini.
Formatnya mengikuti [Keep a Changelog](https://keepachangelog.com/id-ID/1.1.0/)
dan versinya mengikuti [Semantic Versioning](https://semver.org/lang/id/).

## [Unreleased]

### Ditambahkan

- Docs `popover`: menambahkan preview statis untuk empat posisi, contoh kode,
  dan section integrasi state `wire:model`/`wire:open-change` dengan Livewire.
- `clipboard`: nilai hanya-baca dengan variant default dan segmented, preset
  desktop/mobile, label, helper, prefix visual, disabled, tema ambient,
  feedback aksesibel, event `copy-success`/`copy-error`, Playground, dan docs,
  setara kit React.
- `avatar`: prop `name` untuk accessible name dan inisial otomatis, serta
  `fallback` `initials`, `female`, atau `male` dengan ilustrasi profil terang
  dan gelap, setara kit React.
- `list-view`: prop `action-disabled` pada varian simple desktop menyembunyikan
  tombol aksi dan memindahkan `action-href`, `action-wire-click`,
  `action-click`, serta `action-attributes` ke seluruh kartu.
- `sidebar`: kunci `attributes` pada `user` meneruskan atribut ke area
  profil, mis. `['wire:click' => 'bukaProfil']`, padanan `user.onClick` di
  kit React.

### Diubah

- `search`: dark mode dan ukuran varian berkategori diselaraskan dengan kit
  React staging. Field polos kini gray-700; kategori reguler dikunci 113px
  dengan tombol 42x37px, sedangkan kategori compact 128px dengan tombol
  37x37px. Search berkategori compact kini tetap menampilkan tombol bawaan.
- `upload`: state dark mode varian default mengikuti desain terbaru: field
  kosong memakai `#1F2A37`, field terisi gray-700, garis tombol mengikuti
  warna aplikasi, dan teks tombol Remove gray-400. Seluruh ukurannya tetap.
- `clipboard` kini mewajibkan `value` dan nama aksesibel melalui `label`,
  `aria-label`, atau `aria-labelledby`; docs, Playground, dan contoh Livewire
  diselaraskan dengan kontrak terbaru kit React.
- `avatar` kini memakai token `avatar-surface` dan `avatar-content`, sehingga
  mengikuti scope ambient `data-theme`; prop `dark-mode` tetap tersedia sebagai
  alias deprecated untuk kompatibilitas.
- README `spinner` kini mendokumentasikan ukuran 50px/100px, atribut SVG yang
  diteruskan, reduced motion, serta pola aksesibel standalone dan bersama teks
  loading, setara README kit React.
- `badge` mengikuti ukuran desain, sama dengan kit React: `sm` kini tinggi
  22px (sebelumnya 24px) dan `lg` 25px (sebelumnya 28px). Jarak ikon ke teks
  jadi 4px, dan ikon `lg` jadi 18px. Badge di tata letak yang ukurannya
  dikunci bisa sedikit bergeser.
- `sidebar`: pendengar event `collapse`, mis. `wire:collapse`, kini sudah
  menampilkan tombol collapse dalam keadaan aktif, seperti `onCollapse` di
  kit React. Tanpa pendengar, `show-collapse-button` tetap menampilkannya
  nonaktif.
- Contoh Portal Dashboard kini memakai komponen `sidebar` yang sama pada
  desktop dan viewport sempit. Pada viewport sempit, `drawer` hanya menjadi
  shell off-canvas; data menu, profil, logo, status aktif, dan aksinya tetap
  dirender oleh `sidebar`.

### Diperbaiki

- `popover` kini memakai token surface, border, dan content yang sama dengan
  React sehingga mengikuti boundary ambient `data-theme`. Cleanup listener,
  observer, dan frame fokus dilengkapi; perubahan `side` saat panel terbuka
  memicu pengukuran ulang; trigger anchor ditolak. Handler trigger consumer
  kini dirangkai sebelum toggle internal dan dapat membatalkannya dengan
  `preventDefault()`. Docs, Playground, kontrak aksesibilitas, dan Properties
  juga diselaraskan dengan React.
- `clipboard` mempertahankan write-lock sampai operasi Clipboard API selesai,
  mengabaikan hasil lama setelah nilai berubah atau menjadi disabled, serta
  melepas fokus dan seleksi input ketika dinonaktifkan. Hasil operasi yang
  selesai setelah komponen dihancurkan juga diabaikan, dan error listener
  event tidak lagi dapat mengubah feedback copy. Prefix pada variant default
  kini dilaporkan sebagai kombinasi prop yang tidak valid.
- `avatar`: URL foto yang pernah gagal kini dicoba kembali ketika `src`
  berubah lalu kembali ke URL tersebut. Docs dan Playground juga diselaraskan
  dengan React: snippet initials memakai `UI`, nama kosong tetap menghasilkan
  props valid, serta preview dan kode Penggunaan mempunyai semantik aksesibel
  yang sama. Fallback `initials` tanpa `name`, `initials`, atau slot kini
  dilaporkan sebagai prop tidak valid; production menampilkan `?` alih-alih
  lingkaran kosong.
- `sidebar`: area profil tanpa `href` tidak lagi melompat ke `#` saat
  diklik.
- `sidebar`: tombol collapse yang nonaktif kini berkursor `not-allowed`,
  sama dengan kit React.

## [0.2.0] - 2026-11-03

### Ditambahkan

- `drawer`: panel off-canvas berbasis `<dialog>`, setara `Drawer` di kit
  React. Menu disusun lewat `items`, dengan submenu di kunci `children`;
  posisi empat sisi, ukuran `s` hingga `full`, `dark-mode`, `wire:model`,
  serta event `close`. Subitem dengan `href` berupa tautan dan tanpa `href`
  berupa tombol; subitem nonaktif tidak punya tujuan tautan dan memakai
  `aria-disabled`.
- `sidebar`: group menu, submenu, logo/mark ringkas, profil pengguna,
  separator, `dark-mode`, dan event `collapse` untuk integrasi state Livewire.
  Submenu yang aktif selalu terbuka; saat Sidebar ringkas, parent submenu
  hanya membuka Sidebar bila event `collapse` didengarkan.
- `list-view` simple dan article: ukuran responsif, metadata, ikon, dekorasi,
  badge, aksi, `dark-mode`, dan `action-wire-click` agar tombol aksi item
  dapat dikendalikan Livewire tanpa membuat seluruh kartu dapat diklik.
- `timeline`: riwayat kejadian berurutan dengan avatar di sepanjang satu
  garis, setara `Timeline` di kit React. `platform` `desktop` atau `mobile`,
  `size` `large` atau `small`, `labels`, dan `dark-mode`. Tiap item memuat
  `name`, `role`, `time`, `elapsed`, `description`, `details`, `attachments`
  (dengan `href`, `target`, dan `download`), `status`, `avatar`, dan
  `initials`. `time` menerima string waktu ISO 8601 dengan jam atau
  `DateTimeInterface`, dan selalu ditulis dengan format desain, mis.
  `01 Jan 2026 · 09:05`, di zona waktu nilainya; singkatan bulannya diganti
  lewat `labels['months']`. Avatar dan status memakai `<x-ceplok::avatar>`
  dan `<x-ceplok::badge>`; kunci `attributes` pada status diteruskan ke
  Badge, mis. `['wire:dismiss' => 'tutupStatus(2)']`.
- `table-of-contents` setara `TableOfContents` di kit React: daftar isi "On
  this page" 208px dengan scroll-spy, garis brand 2px yang meluncur ke entri
  aktif, `max-height` yang mengikuti entri aktif, `scroll-container`
  (selector wadah gulir), `anchor`, `get-href`, `active-id`, dan
  `dark-mode`. `wire:model` mengikat id entri aktif, dengan event
  `active-change` dan `item-click`. Judul disembunyikan dengan
  `:title="false"` (padanan `title={null}`).
  - `layout` `auto` (bawaan), `list`, atau `drawer`. Dengan `auto`, layar
    1024px ke atas menampilkan daftar 12px, dan layar yang lebih sempit
    menampilkan tombol berisi bagian aktif yang membuka Drawer dari kanan
    dengan entri 14px dan baris 40px. Drawer-nya `<x-ceplok::drawer>`;
    klik entri menutup Drawer dulu, baru berpindah ke bagiannya.
  - `drawer-contained`: Drawer berupa panel di dalam pembungkus `relative`
    terdekat, bukan `<dialog>` yang menutupi layar, untuk pratinjau di
    dalam bingkai. Escape dan klik latar menutupnya, dan fokus kembali ke
    tombol pembukanya.
  - Berbeda dari React, daftar di Drawer selalu dirender, tidak menunggu
    Drawer pertama kali dibuka, supaya markup dari server sama di setiap
    render Livewire.
- `button`: prop `wire-click` memasang `wire:click` dan menonaktifkan tombol
  selama aksi itu berjalan (`wire:loading.attr="disabled"` dengan
  `wire:target` yang sama).
- Token ketebalan huruf `--font-weight-*` (`thin` 100 sampai `black` 900)
  yang dipakai utility `font-thin` sampai `font-black`.
- Token komponen `--color-toc-surface`, `--color-timeline-*`, dan
  `--color-text-area-frame`, yang dipakai komponennya masing-masing.
- Token `--color-dropdown-*`, `--shadow-dropdown`, `--color-popover-*`,
  `--color-avatar-*`, `--avatar-profile-*-display`, dan `--color-clipboard-*`,
  disalin dari kit React. Komponen Livewire belum memakainya, tetapi semuanya
  sudah tersedia di `:root` untuk aplikasi pemakai.
- Plugin Alpine `ceplokDrawer`, `ceplokSidebar`, `ceplokSidebarSubmenu`, dan
  `ceplokTableOfContents`, juga diekspor bernama dari `resources/js/index.js`.

### Diubah

- `text-area` type `editor`: bingkai dan garis pemisah toolbar kini gray-600
  di tema gelap (sebelumnya gray-300), sesuai desain. Warnanya dari token
  baru `--color-text-area-frame`, yang tetap gray-300 di tema terang.
- `button` kini memakai kursor pointer.

### Diperbaiki

- `font-medium` dan `font-semibold` kini tampil 500 dan 600 di Lato. Lato
  2.0 ikut paket (`resources/fonts`, lisensi OFL), salinan persis dari kit
  React, dengan kesembilan ketebalan; Lato dari Google Fonts hanya punya
  100, 300, 400, 700, dan 900, jadi sebelumnya keduanya tampil 400 dan 700.
  `styles.css` tidak lagi memuat Lato dari Google Fonts; Vite menyalin berkas
  `.woff2`-nya ke `public/build`.
- `button` dengan `href` kosong kini dirender sebagai `<button>`, bukan `<a>`
  tanpa tujuan.

## [0.1.1] - 2026-10-26

### Diubah

- README paket: petunjuk install dari Packagist, constraint `^0.1` selama
  versi masih `0.x`, dan cara memakai salinan lokal lewat path repository.

## [0.1.0] - 2026-10-26

Rilis pertama.

### Ditambahkan

- Komponen Blade setara kit React: `alert`, `avatar`, `badge`, `breadcrumb`,
  `button`, `card`, `container`, `dropdown`, `footer`, `hero`, `icon`,
  `pagination`, `spinner`, dan `toast`. Markup, class, dan atribut ARIA-nya
  diuji sama dengan `@ceplok-ui/design-kit-react` di browser.
- Elemen form setara kit React: `checkbox`, `datepicker`, `floating-label`,
  `input-field`, `radio`, `search`, `select`, `text-area`, `toggle`, dan
  `upload`. Kontrol aslinya (`<input>`, `<textarea>`, `<select>`) tetap ada,
  jadi `name`, `required`, pengiriman formulir, keyboard, dan pembaca layar
  bekerja apa adanya.
- Dropdown sebagai menu aksi atau daftar pilihan (`listbox`) dengan Popover
  API: penelusuran panah, Home/End, dan ketikan; bentuk `attached` yang
  menempel pada tombolnya. Daftar pilihan Select dan kategori Search memakai
  panel yang sama.
- Table (`table`, belum stabil: prop dan markup-nya masih bisa berubah di
  rilis minor) setara kit React: toolbar pencarian, filter kolom, dan slot
  aksi; kolom bertingkat, kolom menempel, baris zebra, size `compact`,
  pengurutan per kolom (`sort-value`, `sort-fn`, `sort-directions`,
  `sort-resettable`, `sort-icon`), seleksi baris dengan "pilih semua", tombol
  aksi baris, kolom gambar dan berkas dengan pratinjau, serta pagination
  dengan pilihan jumlah baris. Komponen pendukungnya `table-image` dan
  `table-file`.
  - Urutan dan halaman dikelola server: judul kolom dan tombol halaman
    mengirim event `sort-change`, `page-change`, dan `page-size-change`.
    Tanpa `manual` Table mengurutkan dan memotong `data` sendiri; paginator
    Laravel (`LengthAwarePaginator`) otomatis dianggap `manual`.
  - Baris yang dicentang diikat `wire:model` (event `selection-change`);
    kolom yang disembunyikan diurus Alpine (event `hidden-columns-change`).
- Popover (`popover`) setara kit React: panel informasi non-modal dengan
  panah, Popover API untuk top layer, Escape, dan light dismiss; posisi
  `side` yang berbalik dan dijepit di dalam layar; `dark-mode`; `wire:model`
  dan event `open-change`.
- Modal (`modal`) setara kit React: `<dialog>` bawaan dengan variant
  `default` dan `popup`, size `s` dan `m`, slot `trigger` dan `footer`,
  `close-on-backdrop`, `close-on-escape`, `show-close-button`, `wire:model`
  dan event `open-change`. `<dialog>`-nya `wire:ignore.self` supaya tidak
  ditutup morph Livewire saat komponen di sekitarnya dirender ulang.
- Token CSS (`resources/css/tokens.css`) dan entry stylesheet
  (`resources/css/styles.css`), disalin dari kit React.
- 411 ikon outline dan 340 ikon solid Flowbite (`ceplok-o-*`, `ceplok-s-*`)
  dari `flowbite-react-icons` 1.3.1, plus 30 logo brand (`brand-*`) yang
  bawaannya `aria-hidden="true"`.
- Plugin Alpine (`resources/js/index.js`): `ceplokDismissible`,
  `ceplokAvatar`, `ceplokPagination`, `ceplokDropdown`, `ceplokSelect`,
  `ceplokSearch`, `ceplokDatepicker`, `ceplokUpload`, `ceplokFloatingLabel`,
  `ceplokInputField`, `ceplokTextArea`, `ceplokTable`, `ceplokTablePreview`,
  `ceplokPopover`, dan `ceplokModal`.
- Enum untuk setiap prop berpilihan (`CeplokUi\DesignKit\Enums`) dan
  pemeriksa prop saat render: error yang jelas saat `app.debug` aktif, nilai
  bawaan plus laporan ke log di production.
- Integrasi Livewire:
  - `wire:model` dan event `dismiss` pada Badge, Alert, dan Toast, dengan nilai
    awal yang dibaca dari properti Livewire.
  - `wire:model` dan event `page-change` pada Pagination, serta mode tautan
    lewat prop `url`.
  - Atribut tambahan per item Breadcrumb, per item Dropdown, dan per tombol
    Hero, mis. `wire:navigate` atau `wire:click`.
  - `wire:model` pada seluruh elemen form, dengan nilai awal yang dirender
    server: isian teks (kecuali kata sandi), pilihan Select dan kategori
    Search, tanggal Datepicker, serta centang Checkbox, Radio, dan Toggle
    menurut aturan Livewire. Upload mendukung unggahan Livewire, termasuk
    berkas yang dijatuhkan ke area attach.
  - Event padanan callback React: `clear`, `submit`, `toolbar-action`,
    `search`, `category-change`, `change` (Datepicker), dan `files-change`,
    untuk ditangkap `wire:*` atau `x-on:*`.
  - Keadaan Alpine bertahan saat Livewire merender ulang: ekspresi `x-data`
    tidak memuat nilai yang ikut berganti, opsi Select berkunci `wire:key`,
    dan panel yang sedang terbuka ditempatkan lagi setelah atribut `style`-nya
    diganti morph.
- ID kontrol form yang stabil antar-render (`FieldId`), diturunkan dari
  `wire:model`, `x-model`, atau `name`, dan tetap unik bila satu field muncul
  lebih dari sekali.
- Kompilasi Blaze untuk semua komponen, dan
  `php artisan vendor:publish --tag=ceplok-views` untuk menyesuaikannya.

### Migrasi dari `tpl/design-kit`

Paket ini menggantikan `tpl/design-kit` (dan paket npm `@tpl/design-kit`).

- Ganti `composer require tpl/design-kit` dengan
  `ceplok-ui/design-kit-livewire`, dan awalan komponen `<x-dk::…>` dengan
  `<x-ceplok::…>`.
- Props mengikuti kit React. Contohnya, Button memakai `variant` `filled`/
  `outline` ditambah `theme` dan `tone`; varian lama `primary`, `secondary`,
  `danger`, dan `ghost` tidak ada lagi.
- Ikon `themesberg/flowbite-blade-icons` (`flowbite-o-*`, `flowbite-s-*`) diganti set
  Flowbite milik kit (`ceplok-o-*`, `ceplok-s-*`), yang path SVG-nya sama
  dengan kit React.
- Paket npm tidak dibutuhkan lagi: perilaku Alpine ikut paket Composer dan
  didaftarkan dengan `Alpine.plugin(ceplok)`.
