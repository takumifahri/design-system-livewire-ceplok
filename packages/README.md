# Paket di repo ini

Folder ini memuat paket yang dikembangkan bersama aplikasi dokumentasi di root
repo, lalu dipublikasikan terpisah.

| Paket | Ekosistem | Isi |
| --- | --- | --- |
| `ceplok-ui/design-kit-livewire` | Composer | Komponen Blade `<x-ceplok::…>`, plugin Alpine, token CSS, dan ikon. Padanan `@ceplok-ui/design-kit-react`. |

Cara memakai paketnya ada di
[design-kit-livewire/README.md](design-kit-livewire/README.md). Dokumen ini
membahas alur kerja pengembangnya.

```text
packages/design-kit-livewire/
├── composer.json
├── README.md, HOWTO.md, CHANGELOG.md
├── src/
│   ├── DesignKitServiceProvider.php   komponen, set ikon, Blaze, vendor:publish
│   ├── Enums/                          pilihan prop: ButtonVariant, AlertVariant, …
│   └── Support/                        Prop (pemeriksa prop), Content, Bound
└── resources/
    ├── css/styles.css                  entry stylesheet: Tailwind, token, font, base, .ds-*
    ├── css/tokens.css                  salinan token kit React (jangan diedit di sini)
    ├── js/index.js, js/components/     plugin Alpine: dismissible, avatar, pagination
    ├── svg/flowbite/                   ikon Flowbite (salinan dari kit React)
    ├── svg/brands/                     logo brand milik kit
    └── views/components/               button.blade.php, badge.blade.php, …
```

## 1. Development lokal

Aplikasi di root memasang paket ini dari folder lokal lewat path repository di
[composer.json](../composer.json):

```json
"repositories": [
    {
        "type": "path",
        "url": "packages/design-kit-livewire",
        "options": { "versions": { "ceplok-ui/design-kit-livewire": "0.1.x-dev" } }
    }
],
"require": {
    "ceplok-ui/design-kit-livewire": "0.1.x-dev"
}
```

`vendor/ceplok-ui/design-kit-livewire` menjadi symlink (junction di Windows)
ke folder ini, jadi perubahan langsung terpakai tanpa `composer update`.
Versinya dipatok `0.1.x-dev` lewat `options.versions` supaya tidak berubah
mengikuti nama branch yang sedang aktif. `npm run dev` ikut memuat ulang
halaman saat berkas di `resources/` paket berubah.

Setelah clone baru, `composer install` membuat ulang symlink tersebut.

## 2. Menambah komponen

1. Buat `resources/views/components/nama.blade.php`. Komponen langsung
   tersedia sebagai `<x-ceplok::nama>` dan dikompilasi Blaze.
2. Samakan markup dan class-nya dengan komponen kit React, termasuk urutan
   elemen dan atribut ARIA.
3. Untuk prop berpilihan, buat enum di `src/Enums` dan periksa nilainya dengan
   `Prop::oneOf()`. Gunakan `Prop::bool()`, `Prop::int()`, dan `Prop::tag()`
   untuk prop boolean, angka, dan nama tag. Jangan memakai nilai prop mentah:
   atribut statis selalu tiba sebagai string.
4. Isi yang bisa datang sebagai prop atau slot diperiksa dengan
   `Content::filled()`, padanan pemeriksaan falsy di React.
5. Perilaku interaktif ditulis sebagai data Alpine di `resources/js/components`
   lalu didaftarkan di `resources/js/index.js`.
6. Tulis nama class Tailwind utuh. Tailwind hanya membuat class yang namanya
   muncul utuh di berkas sumber, jadi jangan merakitnya dari potongan string.
7. Tambahkan tes di `tests/Feature/Kit/Components` (lihat tes yang sudah ada:
   markup diperiksa dengan parser HTML bawaan PHP 8.4) dan halaman docs-nya.

## 3. Sinkronisasi dengan kit React

Token dan ikon disalin dari kit React, bukan ditulis ulang. Lokasi kit React
dibaca dari `CEPLOK_REACT_DIR`; bawaannya folder `react-design-system` yang
bersebelahan dengan repo ini.

```bash
npm run tokens:check   # gagal bila tokens.css tertinggal dari kit React
npm run tokens:sync    # salin ulang dari react-design-system/src/lib/tokens.css

npm run icons:check    # gagal bila ikon tertinggal dari flowbite-react-icons
npm run icons:sync     # render ulang ikon dari node_modules kit React
```

Jangan mengedit `resources/css/tokens.css` atau `resources/svg/flowbite/`
langsung: ubah sumbernya di kit React, lalu jalankan perintah sync.

## 4. Publikasi ke Composer

Paket Composer harus punya `composer.json` di root repo yang dipublikasikan,
jadi folder ini dipecah ke repo sendiri. Job `package` di
[deploy.yml](../.github/workflows/deploy.yml) melakukannya otomatis setiap ada
push ke `main` setelah quality check lolos:

```bash
git subtree split --prefix=packages/design-kit-livewire -b package-split
git push <url-repo-paket> package-split:main
```

Repo paketnya
[takumifahri/ceplok-ui-design-kit-livewire](https://github.com/takumifahri/ceplok-ui-design-kit-livewire),
yang terdaftar di
[Packagist](https://packagist.org/packages/ceplok-ui/design-kit-livewire).
Isinya hanya isi folder ini, tanpa aplikasi docs. Jangan commit langsung ke
repo itu: hasil split selalu fast-forward dari riwayatnya, dan push CI tidak
memakai `--force`, jadi commit yang hanya ada di repo paket membuat push
berikutnya ditolak.

### Rilis versi

Tag versi dibuat dari CHANGELOG, bukan ditentukan CI. Untuk merilis:

1. Di merge request rilis, ganti judul `## [Unreleased]` di
   [CHANGELOG.md](design-kit-livewire/CHANGELOG.md) menjadi
   `## [0.2.0] - 2026-11-03`, lalu tambahkan `## [Unreleased]` kosong di
   atasnya.
2. Setelah merge ke `main`, job `package` membuat tag `v0.2.0` di HEAD repo
   paket, bila tag itu belum ada.

Yang dibaca hanya bagian versi teratas. Merge tanpa bagian versi baru tidak
membuat tag. Tag yang sudah ada tidak pernah ditimpa.

Setelah tag ada, pilih registry-nya:

- **Packagist**, bila paket boleh publik. Daftarkan URL repo paketnya sekali;
  tag berikutnya terbaca otomatis lewat webhook.
- **GitLab Composer Package Registry**, bila paket harus privat. Consumer
  menambahkan repository GitLab dan token baca (deploy token dengan scope
  `read_package_registry`) di `auth.json`, bukan di `composer.json`.

Setelah itu project consumer cukup:

```bash
composer require ceplok-ui/design-kit-livewire:^0.1
```

## 5. Versioning

Ikuti semver (`MAJOR.MINOR.PATCH`):

- **Patch**: perbaikan bug tanpa mengubah prop atau markup yang diandalkan
  consumer.
- **Minor**: komponen, prop, atau token baru tanpa breaking change.
- **Major**: mengganti nama atau menghapus prop, token, atau ikon. Wajib
  dicatat di [CHANGELOG.md](design-kit-livewire/CHANGELOG.md) sebelum tag
  dibuat, karena paket dipakai lintas produk.

Selama versi masih `0.x`, perubahan minor boleh breaking; catat tetap di
changelog.
