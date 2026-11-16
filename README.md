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
| Fase 3 (selesai) | Clipboard, Popover, Modal, Table of Contents |
| Fase 3 (belum stabil) | Table (beserta Table Image dan Table File): prop dan markup masih bisa berubah |

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

Font Lato 2.0 ikut paket kit dan disalin Vite ke `public/build`. Inter dimuat
dari Google Fonts oleh browser, jadi build tidak butuh koneksi internet.

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
scripts/                        sinkronisasi token, ikon, dan font dari kit React
tests/                          tes kit, halaman docs, dan Playground
```

Panduan memakai paket ada di
[packages/design-kit-livewire/README.md](packages/design-kit-livewire/README.md),
alur kerja paket (development lokal, menambah komponen, publikasi) di
[packages/README.md](packages/README.md), dan panduan aplikasi dokumentasi di
[Docs.md](Docs.md).

## Kesetaraan dengan kit React

Token, ikon, dan font Lato disalin dari kit React, bukan ditulis ulang. Kit
React dibaca dari folder `react-design-system` yang bersebelahan dengan repo
ini, atau dari `CEPLOK_REACT_DIR`.

```bash
npm run tokens:check    # gagal bila token tertinggal dari kit React
npm run tokens:sync
npm run icons:check     # gagal bila ikon tertinggal dari flowbite-react-icons
npm run icons:sync
npm run fonts:check     # gagal bila font Lato tertinggal dari kit React
npm run fonts:sync
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
npm run fonts:check
composer audit --locked
npm audit --audit-level=high
```

Bila Pint menemukan masalah format, perbaiki dengan `vendor/bin/pint`.
`npm run build` perlu dijalankan sebelum tes: halaman docs memakai manifest
Vite.

## CI/CD

Repo GitLab di-mirror ke GitHub. Setiap push ke `main` di GitHub menjalankan
`.github/workflows/deploy.yml`:

1. **Quality check**: urutan di atas, kecuali `tokens:check`, `icons:check`,
   dan `fonts:check` yang butuh checkout kit React di sebelahnya.
2. **Build image**: bila quality check lolos, runner mem-build image Docker
   dan mendorongnya ke GitHub Container Registry sebagai
   `ghcr.io/<owner>/<repo>:<commit>` dan `:latest`.
3. **Deploy ke server**: runner masuk ke tailnet lewat Tailscale, SSH ke
   server, menarik commit yang sama (`git fetch` + `git reset --hard`), lalu
   menjalankan `scripts/deploy.sh`. Skrip itu menarik image dari GHCR,
   mengganti container-nya, lalu menunggu healthcheck `/up` lolos.

Server tidak pernah mem-build image sendiri, karena build Vite dan Composer
terlalu berat untuk server homelab. Workflow juga bisa dijalankan manual dari
tab Actions (Run workflow).

### Docker

- `Dockerfile` mem-build image FrankenPHP (PHP 8.4 + Caddy) dalam tiga tahap:
  dependency Composer, aset Vite, lalu image akhir tanpa Node dan tanpa
  dependency dev.
- `docker/start.sh` jalan setiap container start dan menjalankan `optimize`.
  Cache config dibuat dari env container, jadi `.env` tidak ikut masuk ke
  image.
- `compose.yaml` membaca `.env` di root repo, menyimpan `storage/` di volume
  `storage`, dan membuka port hanya di `127.0.0.1:${APP_PORT:-8080}`.
- Situs docs tidak memakai database. `compose.yaml` memaksa
  `SESSION_DRIVER=file`, `CACHE_STORE=file`, dan `QUEUE_CONNECTION=sync`,
  apa pun isi `.env`. Bila nanti butuh database, hapus ketiganya dan tambahkan
  service database di `compose.yaml`.

Uji di lokal dengan `docker compose up -d --build`, lalu buka
<http://localhost:8080>.

### Persiapan server (sekali saja)

Server-nya Debian 13 di jaringan lokal, terhubung ke Tailscale.

- Pasang Docker Engine dan plugin Compose dari repository apt resmi Docker
  untuk Debian. Buat user `deploy` yang masuk grup `docker`. Jangan memakai
  `root`.
- Clone repo GitHub ke folder aplikasi. Bila repo privat, clone pertama
  memakai login sendiri. Deploy berikutnya menarik kode dengan `GITHUB_TOKEN`
  dari workflow, jadi server tidak butuh deploy key.
- Buat `.env` produksi di folder itu: `APP_ENV=production`,
  `APP_DEBUG=false`, `APP_URL=https://<domain>`, `APP_KEY` (buat dengan
  `php artisan key:generate --show` di lokal), `LOG_CHANNEL=stderr` supaya
  log terbaca lewat `docker compose logs`, dan
  `APP_IMAGE=ghcr.io/<owner>/<repo>` (huruf kecil) supaya `docker compose`
  yang dijalankan manual memakai image yang sama.
- Deploy pertama berjalan lewat workflow (Run workflow di tab Actions), karena
  image-nya baru ada di GHCR setelah job build pertama.
- Pasang `cloudflared` dan buat Cloudflare Tunnel yang meneruskan domain ke
  `http://127.0.0.1:8080`. HTTPS diurus Cloudflare, jadi tidak perlu membuka
  port di router. `bootstrap/app.php` mempercayai header `X-Forwarded-*` dari
  tunnel, supaya URL aset memakai `https`.

### Tailscale

Runner GitHub masuk ke tailnet sebagai `tag:ci` hanya selama job deploy.
Server bertag `tag:server` dan memakai Tailscale SSH (`tailscale set --ssh`),
jadi izin masuknya diatur kebijakan akses Tailscale, bukan kunci SSH atau
`authorized_keys`.

1. Di kebijakan akses Tailscale, daftarkan `tag:ci` di `tagOwners`, izinkan
   `tag:ci` menjangkau `tag:server` di port 22, dan izinkan Tailscale SSH dari
   `tag:ci` ke `tag:server` sebagai user `deploy`:

   ```json
   "tagOwners": {
       "tag:ci": ["autogroup:admin"]
   },
   "acls": [
       { "action": "accept", "src": ["tag:ci"], "dst": ["tag:server:22"] }
   ],
   "ssh": [
       { "action": "accept", "src": ["tag:ci"], "dst": ["tag:server"], "users": ["deploy"] }
   ]
   ```

   Gabungkan dengan isi kebijakan yang sudah ada, jangan menimpanya. Bila
   kebijakannya memakai `grants`, tulis izin port 22 sebagai grant.
2. Buat OAuth client di Settings → OAuth clients dengan scope
   **Auth Keys: write** dan tag `tag:ci`.
3. `VPS_HOST` diisi nama MagicDNS atau IP Tailscale server (`100.x.y.z`),
   bukan IP LAN `10.10.10.x`.

### Secrets GitHub

Isi di Settings → Secrets and variables → Actions:

| Secret | Isi |
| --- | --- |
| `TS_OAUTH_CLIENT_ID` | ID OAuth client Tailscale |
| `TS_OAUTH_SECRET` | secret OAuth client Tailscale |
| `VPS_HOST` | nama MagicDNS atau IP Tailscale server |
| `VPS_USER` | user deploy (`deploy`) |
| `VPS_PATH` | folder aplikasi di server |
| `PACKAGE_REPO_TOKEN` | fine-grained PAT dengan izin **Contents: read and write**, hanya untuk repo paket |

Isi juga variable `PACKAGE_REPO` di tab Variables dengan
`takumifahri/ceplok-ui-design-kit-livewire`, repo paket yang dibaca Packagist. Job `package` memakai keduanya untuk push hasil split
`packages/design-kit-livewire` dan tag versinya ke repo itu. Langkahnya ada di
[packages/README.md](packages/README.md), bagian "Publikasi ke Composer".

Login ke GHCR tidak butuh secret tambahan. Workflow memakai `GITHUB_TOKEN`
bawaan untuk push image, dan meneruskannya ke server untuk pull. Token itu
kedaluwarsa begitu job selesai, dan server logout setelah pull.

Token mirror GitLab ke GitHub harus punya izin **Workflows: read and write**
(fine-grained) atau scope `workflow` (classic). Tanpa izin itu, GitHub menolak
push yang menyentuh `.github/workflows/`, dan mirror berhenti.

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
