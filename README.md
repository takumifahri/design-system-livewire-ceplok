# Komdigi Design System

Komdigi Design System adalah proof of concept untuk membangun fondasi antarmuka yang konsisten pada aplikasi berbasis Laravel. Proyek ini menyediakan design token, aturan tipografi, dan halaman dokumentasi interaktif sebagai acuan bersama bagi designer dan developer.

Saat ini proyek masih berbentuk aplikasi Laravel untuk pengembangan dan validasi visual. Proyek belum dipublikasikan sebagai package npm atau Composer.

## Tujuan Proyek

- Menyediakan sumber acuan untuk warna dan tipografi.
- Menjaga konsistensi visual antarhalaman dan antarproduk.
- Memisahkan nilai dasar desain dari kebutuhan semantik komponen.
- Memudahkan pengujian token sebelum didistribusikan sebagai package.
- Menyiapkan alur kolaborasi melalui code review, staging, dan CI/CD.

## Fitur Saat Ini

- Primitive color tokens untuk `primary`, `gray`, `danger`, `orange`, `warning`, `success`, dan `purple`.
- Semantic color tokens untuk brand, feedback, content, border, dan surface.
- Typography scale mulai dari `display` sampai `caption`.
- Lato sebagai font utama.
- Utility class berbasis Tailwind CSS.
- Halaman dokumentasi responsif untuk melihat dan menyalin nilai token.
- Interaksi antarmuka menggunakan Alpine.js.
- Pipeline GitLab untuk build, test, code style, dependency audit, SAST, dan secret detection.

## Teknologi

| Teknologi | Kegunaan |
| --- | --- |
| Laravel 13 | Aplikasi dokumentasi dan integrasi backend |
| Blade | Template halaman dokumentasi |
| Tailwind CSS 4 | Utility CSS dan definisi token |
| Alpine.js | Interaksi ringan pada halaman |
| Vite 8 | Development server dan production build |
| PHPUnit | Unit dan feature test |
| Laravel Pint | Pemeriksaan format kode PHP |
| GitLab CI/CD | Otomatisasi quality check dan security scan |

## Persyaratan

Pastikan perangkat pengembangan memiliki:

- PHP 8.3 atau lebih baru.
- Composer 2.
- Node.js 20.19 atau lebih baru.
- npm.
- Extension PHP SQLite untuk pengembangan dan testing lokal.

Build frontend membutuhkan koneksi internet karena konfigurasi Vite mengambil file font Lato dari Bunny Fonts.

## Instalasi

Clone repository dan masuk ke direktori proyek:

```bash
git clone https://gitlab.com/intern-tpl-ipb/design-system-project.git
cd design-system-project
```

Pasang dependency PHP:

```bash
composer install
```

Siapkan environment Laravel:

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

Pasang dependency frontend dan buat production build awal:

```bash
npm ci
npm run build
```

## Menjalankan Proyek

Cara paling praktis untuk menjalankan server Laravel, queue listener, log viewer, dan Vite secara bersamaan:

```bash
composer run dev
```

Buka aplikasi melalui:

```text
http://localhost:8000
```

Untuk menjalankan backend dan frontend secara terpisah, gunakan dua terminal:

```bash
php artisan serve
```

```bash
npm run dev
```

## Cara Kerja Design Token

Token didefinisikan di `resources/css/app.css` menggunakan konfigurasi theme Tailwind CSS.

Primitive token menyimpan nilai dasar palet:

```css
--color-primary-600: #1c64f2;
--color-danger-600: #e02424;
--color-gray-900: #111827;
```

Semantic token menjelaskan tujuan penggunaan dan mengacu pada primitive token:

```css
--color-brand: var(--color-primary-600);
--color-feedback-error: var(--color-danger-600);
--color-content: var(--color-gray-900);
```

Pemisahan tersebut membuat komponen bergantung pada makna token, bukan langsung pada nilai warna. Jika warna brand berubah, komponen tidak perlu diperbarui satu per satu.

Contoh penggunaan utility pada Blade:

```blade
<button class="bg-brand text-white hover:bg-brand-hover">
    Simpan
</button>

<p class="text-body text-content-subtle">
    Informasi tambahan
</p>
```

Class komponen yang sudah tersedia antara lain:

- `ds-card`
- `ds-eyebrow`
- `ds-nav-link`

## Struktur Utama

```text
app/                         Kode aplikasi Laravel
resources/css/app.css        Token, base style, dan component style
resources/js/app.js          Interaksi Alpine.js
resources/views/             Halaman dokumentasi Blade
routes/web.php               Route aplikasi
tests/Feature/               Feature test Laravel
tests/Unit/                  Unit test PHP
public/build/                Hasil production build Vite
.gitlab-ci.yml               Konfigurasi pipeline GitLab
```

Direktori `vendor`, `node_modules`, dan `public/build` dibuat secara lokal atau oleh pipeline dan tidak disimpan ke Git.

## Quality Check Lokal

Jalankan pemeriksaan berikut sebelum melakukan push.

Validasi dependency Composer:

```bash
composer validate --strict --no-check-publish
```

Pasang dependency sesuai lockfile dan build frontend:

```bash
npm ci
npm run build
```

Periksa format PHP tanpa mengubah file:

```bash
vendor/bin/pint --test
```

Jika ditemukan masalah format, perbaiki dengan:

```bash
vendor/bin/pint
```

Jalankan seluruh test:

```bash
php artisan test
```

Periksa kerentanan dependency:

```bash
composer audit --locked
npm audit --audit-level=high
```

## CI/CD

Pipeline GitLab berjalan pada branch, merge request, dan tag. Ketika sebuah branch sudah memiliki merge request, pipeline branch duplikat tidak dibuat.

Pipeline terdiri dari tiga tahap:

1. `validate` memeriksa konfigurasi Composer.
2. `build` memasang dependency npm dan menghasilkan artifact frontend di `public/build`.
3. `test` menjalankan Laravel test, Pint, dependency audit, SAST, dan secret detection.

Artifact frontend dari tahap build digunakan oleh feature test Laravel. Hal ini memastikan halaman yang menggunakan Vite manifest juga dapat diuji di environment CI.

Pipeline saat ini belum melakukan deployment atau publish package. Tahap release akan ditambahkan setelah nama package, jenis package, registry, dan aturan versioning ditetapkan.

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

Perubahan dapat digabungkan setelah pipeline berhasil, reviewer memberikan persetujuan, dan seluruh diskusi selesai. Setelah sekumpulan perubahan di staging dinyatakan stabil oleh tim, buat merge request:

```text
staging -> main
```

Jangan melakukan push langsung ke `staging` atau `main`. Kedua branch tersebut sebaiknya dilindungi melalui protected branch dan hanya menerima perubahan melalui merge request.

## Rencana Distribusi Package

Distribusi akhir akan dipilih berdasarkan jenis aset yang digunakan kembali:

- Package npm untuk design token, CSS, Tailwind, dan JavaScript.
- Package Composer untuk Blade component dan integrasi khusus Laravel.
- Kombinasi keduanya jika design system membutuhkan aset frontend sekaligus komponen Blade.

Release stabil sebaiknya dibuat dari tag pada branch `main`, misalnya `v1.0.0`. Pipeline release kemudian membangun dan memublikasikan package secara otomatis ke registry yang telah ditentukan.

## Kontribusi

- Buat satu branch untuk satu fitur atau perbaikan.
- Gunakan nama branch yang menjelaskan task, seperti `feature/button-component` atau `fix/typography-scale`.
- Buat commit kecil dengan pesan yang menjelaskan perubahan.
- Tambahkan atau perbarui test jika perilaku aplikasi berubah.
- Jalankan quality check lokal sebelum push.
- Sertakan ringkasan perubahan dan cara pengujian pada deskripsi merge request.

Untuk laporan kerentanan keamanan, hubungi maintainer proyek melalui jalur internal dan jangan memublikasikan detail sensitif pada issue umum.
