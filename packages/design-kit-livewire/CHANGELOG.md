# Changelog

Semua perubahan penting pada `ceplok-ui/design-kit-livewire` dicatat di sini.
Formatnya mengikuti [Keep a Changelog](https://keepachangelog.com/id-ID/1.1.0/)
dan versinya mengikuti [Semantic Versioning](https://semver.org/lang/id/).

## [Unreleased]

Rilis pertama, direncanakan sebagai `0.1.0`.

### Ditambahkan

- Komponen Blade setara kit React: `alert`, `avatar`, `badge`, `breadcrumb`,
  `button`, `card`, `container`, `drawer`, `dropdown`, `footer`, `hero`, `icon`,
  `list-view`, `pagination`, `sidebar`, `spinner`, dan `toast`. Markup, class,
  dan atribut ARIA-nya diuji sama dengan `@ceplok-ui/design-kit-react` di
  browser.
- `Drawer` dalam satu berkas komponen: menu disusun lewat `items`, dengan
  submenu di kunci `children`; posisi empat sisi, ukuran `s` hingga `full`,
  dark mode, `wire:model`, serta event `close`.
- `Sidebar` dengan group menu, submenu, logo/mark ringkas, profil pengguna,
  separator, dark mode, dan event `collapse` untuk integrasi state Livewire.
- `List View` simple dan article: ukuran responsif, metadata, ikon, dekorasi,
  badge, aksi, dark mode, dan `action-wire-click` agar aksi item dapat
  dikendalikan Livewire tanpa membuat seluruh kartu dapat diklik.
- `timeline`: riwayat kejadian
  berurutan dengan avatar di sepanjang satu garis, setara `Timeline` di kit
  React. `platform` `desktop` atau `mobile`, `size` `large` atau `small`,
  `labels`, dan `dark-mode`. Tiap item memuat `name`, `role`, `time`,
  `elapsed`, `description`, `details`, `attachments` (dengan `href`,
  `target`, dan `download`), `status`, `avatar`, dan `initials`. `time`
  menerima string waktu ISO 8601 dengan jam atau `DateTimeInterface`, dan
  selalu ditulis dengan format desain, mis. `01 Jan 2026 · 09:05`, di zona
  waktu nilainya; singkatan bulannya diganti lewat `labels['months']`. Avatar dan
  status memakai `<x-ceplok::avatar>` dan `<x-ceplok::badge>`; kunci
  `attributes` pada status diteruskan ke Badge, mis.
  `['wire:dismiss' => 'tutupStatus(2)']`.
- Elemen form setara kit React: `checkbox`, `datepicker`, `floating-label`,
  `input-field`, `radio`, `search`, `select`, `text-area`, `toggle`, dan
  `upload`. Kontrol aslinya (`<input>`, `<textarea>`, `<select>`) tetap ada,
  jadi `name`, `required`, pengiriman formulir, keyboard, dan pembaca layar
  bekerja apa adanya.
- Dropdown sebagai menu aksi atau daftar pilihan (`listbox`) dengan Popover
  API: penelusuran panah, Home/End, dan ketikan; bentuk `attached` yang
  menempel pada tombolnya. Daftar pilihan Select dan kategori Search memakai
  panel yang sama.
- Table (`table`) setara kit React: toolbar pencarian, filter kolom, dan slot
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
- Table of Contents (`table-of-contents`) setara layout `list` di kit React:
  daftar isi "On this page" 208px dengan scroll-spy, garis brand 2px yang
  meluncur ke entri aktif, `max-height` yang mengikuti entri aktif,
  `scroll-container` (selector wadah gulir), `anchor`, `get-href`,
  `active-id`, dan `dark-mode`. `wire:model` mengikat id entri aktif, dengan
  event `active-change` dan `item-click`. Layout `auto` dan `drawer` belum
  di-port, dan judul disembunyikan dengan `:title="false"` (padanan
  `title={null}`).
- Token CSS (`resources/css/tokens.css`) dan entry stylesheet
  (`resources/css/styles.css`), disalin dari kit React, termasuk token
  ketebalan huruf `--font-weight-*` (`thin` 100 sampai `black` 900) yang
  dipakai utility `font-thin` sampai `font-black`.
- Font Lato 2.0 ikut paket (`resources/fonts`, lisensi OFL), salinan persis
  dari kit React, dengan kesembilan ketebalan. `font-medium` dan
  `font-semibold` kini tampil 500 dan 600; Lato dari Google Fonts hanya punya
  100, 300, 400, 700, dan 900, jadi sebelumnya keduanya tampil 400 dan 700.
  `styles.css` tidak lagi memuat Lato dari Google Fonts; Vite menyalin berkas
  `.woff2`-nya ke `public/build`.
- 411 ikon outline dan 340 ikon solid Flowbite (`ceplok-o-*`, `ceplok-s-*`)
  dari `flowbite-react-icons` 1.3.1, plus 30 logo brand (`brand-*`) yang
  bawaannya `aria-hidden="true"`.
- Plugin Alpine (`resources/js/index.js`): `ceplokDismissible`, `ceplokDrawer`,
  `ceplokSidebar`, `ceplokSidebarSubmenu`, `ceplokAvatar`, `ceplokPagination`,
  `ceplokDropdown`, `ceplokSelect`, `ceplokSearch`, `ceplokDatepicker`,
  `ceplokUpload`, `ceplokFloatingLabel`, `ceplokInputField`, `ceplokTextArea`,
  `ceplokTable`, `ceplokTablePreview`, `ceplokPopover`, `ceplokModal`, dan
  `ceplokTableOfContents`.
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

### Diperbaiki

- `Drawer` dan `ListView` kini masing-masing memakai satu berkas komponen.
  API menu Drawer disusun melalui `items` dan `children`, sedangkan ListView
  menerima konfigurasi item melalui `items` atau props komponen utamanya;
  subkomponen `drawer.*` dan `list-view.*` telah dihapus.
- `ListView`: `action-wire-click` meneruskan event Livewire hanya ke tombol
  aksi bawaan, sehingga interaksi `Open` tidak menjadikan seluruh kartu sebagai
  target klik.
- `ListView`: ukuran simple dan article kini mengikuti referensi React pada
  setiap varian. Typography metadata, wadah serta ukuran ikon, padding,
  tinggi minimum, dan ukuran tombol aksi S/M/L tidak lagi dikompensasi oleh
  halaman dokumentasi.
- `ListView`: item simple desktop berukuran default yang memuat badge kini
  setinggi minimum 108px, sama dengan kit React.
- `Sidebar`: state collapse Alpine kini tersinkron dengan prop server tanpa
  mencampurkan class lebar statis dan dinamis. Submenu aktif selalu terbuka;
  saat Sidebar ringkas, parent submenu hanya dapat membuka Sidebar bila event
  `collapse` didengarkan.
- `Drawer`: subitem menu dengan `href` tetap berupa tautan, subitem tanpa
  `href` berupa tombol, dan subitem nonaktif tidak lagi memiliki tujuan
  tautan serta mengikuti atribut ARIA disabled.

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
