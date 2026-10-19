# Ceplok Design Kit — Livewire

Repo ini berisi paket Composer **`ceplok-ui/design-kit-livewire`**: komponen
Blade + Alpine.js, design token, dan ikon Ceplok Design System untuk aplikasi
Laravel dan Livewire. Repo ini juga memuat aplikasi dokumentasinya. Paket ini
padanan `@ceplok-ui/design-kit-react`. Markup, class, token, dan ikon kedua
kit diuji sama persis di browser.

Sebelumnya repo ini adalah proof of concept State Security Service Design
System dengan paket `tpl/design-kit`; paket itu kini digantikan
`ceplok-ui/design-kit-livewire`.

## Status

| Tahap | Komponen |
| --- | --- |
| Fase 1 (selesai) | Alert, Avatar, Badge, Breadcrumb, Button, Card, Container, Footer, Hero, Icon, Pagination, Spinner, Toast |
| Fase 2 (selesai) | Komponen form: Input Field, Floating Label, Text Area, Select, Search, Datepicker, Upload, Radio, Toggle, Checkbox — serta Dropdown |

Setiap komponen punya halaman dokumentasi dengan demo, Playground Livewire,
contoh kode, dan tabel props, mengikuti dokumentasi kit React.

## Teknologi

| Teknologi | Kegunaan |
| --- | --- |
| Laravel 13 | Aplikasi dokumentasi |
| Livewire 4 | Playground dan contoh integrasi; membawa Alpine.js |
| Blaze | Mengompilasi komponen kit menjadi fungsi PHP biasa |
| Blade Icons | Set ikon Flowbite dan logo brand kit |
| Tailwind CSS 4 | Token (`@theme`) dan class utility |
| Vite 8 | Development server dan production build |
| PHPUnit 12 | Tes komponen, halaman, dan Playground |
| Laravel Pint | Pemeriksaan format kode PHP |

## Persyaratan

- PHP 8.4 atau lebih baru, dengan extension `dom` dan `pdo_sqlite`. Paket
  kitnya sendiri cukup PHP 8.2; PHP 8.4 dibutuhkan aplikasi dan tesnya.
- Composer 2.
- Node.js 20.19 atau lebih baru, dan npm.

Font Lato dan Inter dimuat dari Google Fonts oleh browser, jadi build tidak
butuh koneksi internet.

## Instalasi

```bash
git clone https://gitlab.com/intern-tpl-ipb/design-system-project.git
cd design-system-project

composer install

cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate

npm ci
npm run build
```

## Menjalankan

Server Laravel, queue listener, log viewer, dan Vite sekaligus:

```bash
composer run dev
```

Lalu buka `http://localhost:8000`. Untuk menjalankannya terpisah, pakai dua
terminal: `php artisan serve` dan `npm run dev`.

## Struktur

```text
packages/design-kit-livewire/   paket Composer ceplok-ui/design-kit-livewire
app/Docs/                       navigasi dan pengolah cuplikan kode situs docs
resources/views/pages/          halaman dokumentasi
resources/views/components/     komponen situs (x-docs.*) dan Playground Livewire
resources/js/, resources/css/   entry Vite: plugin kit + perilaku situs docs
scripts/                        sinkronisasi token dan ikon dari kit React
tests/                          tes kit, halaman docs, dan Playground
```

Panduan memakai paket ada di
[packages/design-kit-livewire/README.md](packages/design-kit-livewire/README.md),
alur kerja paket (development lokal, menambah komponen, publikasi) di
[packages/README.md](packages/README.md), dan panduan aplikasi dokumentasi di
[Docs.md](Docs.md).

## Kesetaraan dengan kit React

Token dan ikon disalin dari kit React, bukan ditulis ulang. Kit React dibaca
dari folder `react-design-system` yang bersebelahan dengan repo ini, atau dari
`CEPLOK_REACT_DIR`.

```bash
npm run tokens:check    # gagal bila token tertinggal dari kit React
npm run tokens:sync
npm run icons:check     # gagal bila ikon tertinggal dari flowbite-react-icons
npm run icons:sync
```

## Quality check lokal

Jalankan sebelum push:

```bash
composer validate --strict --no-check-publish
npm ci
npm run build
vendor/bin/pint --test
php artisan test
npm run tokens:check
npm run icons:check
composer audit --locked
npm audit --audit-level=high
```

Bila Pint menemukan masalah format, perbaiki dengan `vendor/bin/pint`.
`npm run build` perlu dijalankan sebelum tes: halaman docs memakai manifest
Vite.

## CI/CD

Repo ini belum memuat konfigurasi pipeline. Urutan quality check di atas
siap dipakai sebagai job-nya: `validate` (composer validate), `build` (npm ci,
npm run build), lalu `test` (Pint, PHPUnit, pemeriksaan token dan ikon, audit
dependency). Tahap `test` memakai artifact `public/build` dari tahap `build`.
Pemeriksaan token dan ikon butuh checkout kit React di sebelahnya; lewati
keduanya bila checkout itu tidak tersedia di runner.

## Alur Branch dan Merge Request

Branch utama memiliki fungsi berikut:

- `main` menyimpan kode stabil yang siap dirilis.
- `staging` menjadi tempat integrasi dan pengujian perubahan dari tim.
- `feature/*` menjadi tempat pengembangan satu fitur atau task.

Mulai pekerjaan dari `staging` terbaru:

```bash
git switch staging
git pull origin staging
git switch -c feature/nama-fitur
```

Setelah perubahan selesai dan quality check lokal berhasil:

```bash
git add .
git commit -m "feat: add nama fitur"
git push -u origin feature/nama-fitur
```

Buat merge request dengan alur:

```text
feature/nama-fitur -> staging
```

Perubahan dapat digabungkan setelah pipeline berhasil, reviewer memberikan
persetujuan, dan seluruh diskusi selesai. Setelah sekumpulan perubahan di
staging dinyatakan stabil oleh tim, buat merge request:

```text
staging -> main
```

Jangan melakukan push langsung ke `staging` atau `main`. Kedua branch tersebut
sebaiknya dilindungi melalui protected branch dan hanya menerima perubahan
melalui merge request.

## Distribusi Paket

Paket Composer `ceplok-ui/design-kit-livewire` dipecah ke repo sendiri lalu
dipublikasikan ke Packagist (publik) atau GitLab Composer Package Registry
(privat). Langkah dan aturan versinya ada di
[packages/README.md](packages/README.md). Paket npm tidak dibutuhkan lagi:
perilaku Alpine ikut paket Composer.

## Kontribusi

- Buat satu branch untuk satu fitur atau perbaikan.
- Gunakan nama branch yang menjelaskan task, seperti `feature/select-component`
  atau `fix/badge-dark-mode`.
- Buat commit kecil dengan pesan yang menjelaskan perubahan.
- Tambahkan atau perbarui tes bila perilaku komponen berubah.
- Jalankan quality check lokal sebelum push.
- Sertakan ringkasan perubahan dan cara pengujian pada deskripsi merge request.

Untuk laporan kerentanan keamanan, hubungi maintainer proyek melalui jalur
internal dan jangan memublikasikan detail sensitif pada issue umum.
