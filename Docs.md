# tpl/design-kit & @tpl/design-kit

Dokumentasi alur kerja package internal yang dipecah dari aplikasi dokumentasi
Komdigi Design System. Ada dua package yang dipublikasikan dari folder ini:

| Package | Ekosistem | Isi |
| --- | --- | --- |
| `tpl/design-kit` | Composer | Blade components (`<x-dk::button>`, dll) + token CSS |
| `@tpl/design-kit` | npm | Alpine.js components (`registerDesignKit`, dll) + token CSS |

Keduanya dikembangkan bareng di repo aplikasi ini (`Design-system-projects`)
sebagai **local path package**, supaya bisa dipakai dan diedit sekaligus tanpa
publish berkali-kali saat masih development.

```
packages/
├── tokens/design-tokens.css        ← sumber tunggal token warna & tipografi
├── php/                             ← tpl/design-kit (Composer)
│   ├── composer.json
│   ├── src/DesignKitServiceProvider.php
│   └── resources/
│       ├── css/tokens.css           ← salinan dari packages/tokens/
│       └── views/components/        ← button.blade.php, badge.blade.php, ...
└── js/                               ← @tpl/design-kit (npm)
    ├── package.json
    └── src/
        ├── index.js                  ← registerDesignKit(Alpine)
        ├── tokens.css                ← salinan dari packages/tokens/
        └── components/               ← clipboard.js, disclosure.js, ...
```

---

## 1. Alur development lokal

Aplikasi utama (`Design-system-projects`) sudah di-wiring supaya "menginstall"
kedua package ini dari folder lokal, bukan dari registry:

**Composer** — [composer.json](../composer.json) punya path repository:

```json
"repositories": [
    { "type": "path", "url": "packages/php" }
],
"require": {
    "tpl/design-kit": "@dev"
}
```

Ini bikin `vendor/tpl/design-kit` jadi **symlink** ke `packages/php`. Efeknya:
edit file di `packages/php/resources/views/components/button.blade.php`
langsung kepakai di aplikasi, tanpa `composer update` ulang.

**npm** — [package.json](../package.json) punya workspace:

```json
"workspaces": ["packages/js"],
"dependencies": {
    "@tpl/design-kit": "*"
}
```

Sama halnya, `node_modules/@tpl/design-kit` jadi symlink ke `packages/js`.

Kalau symlink belum ada (misal setelah clone baru), jalankan ulang:

```bash
composer install
npm install
```

### Menambah komponen baru

- **Blade component** → bikin file baru di
  `packages/php/resources/views/components/nama.blade.php`, otomatis bisa
  dipakai sebagai `<x-dk::nama>` (didaftarkan via
  `Blade::anonymousComponentPath` di `DesignKitServiceProvider`).
- **Alpine component** → bikin file baru di `packages/js/src/components/`,
  export function-nya, lalu daftarkan di `packages/js/src/index.js` dalam
  `registerDesignKit()`.

### Token warna/tipografi

Satu sumber di `packages/tokens/design-tokens.css`. **Jangan edit** salinan di
`packages/php/resources/css/tokens.css` atau `packages/js/src/tokens.css`
langsung — edit sumbernya, lalu sinkronkan:

```bash
npm run sync:tokens
```

---

## 2. Cara aplikasi ini memakai package

`resources/css/app.css`:

```css
@import 'tailwindcss';
@import '../../vendor/tpl/design-kit/resources/css/tokens.css';

@source '../../vendor/tpl/design-kit/resources/views/**/*.blade.php';
```

`resources/js/app.js`:

```js
import Alpine from 'alpinejs';
import { registerDesignKit, dkClipboard } from '@tpl/design-kit';

registerDesignKit(Alpine);
window.Alpine = Alpine;
Alpine.start();
```

Contoh pemakaian komponen ada di [welcome.blade.php](../resources/views/welcome.blade.php),
bagian "Komponen dari package".

---

## 3. Publish `@tpl/design-kit` ke npm (private, GitLab registry)

Registry-nya adalah **GitLab npm Package Registry** milik project ini sendiri
— gratis, private mengikuti visibility repo, tidak butuh akun npm terpisah.

Konfigurasi ada di [.npmrc](../.npmrc) (root repo):

```
@tpl:registry=https://gitlab.com/api/v4/projects/intern-tpl-ipb%2Fdesign-system-project/packages/npm/
//gitlab.com/api/v4/projects/intern-tpl-ipb%2Fdesign-system-project/packages/npm/:_authToken=${NPM_TOKEN}
```

### Publish otomatis lewat CI (direkomendasikan)

Job `publish_design_kit_npm` di [.gitlab-ci.yml](../.gitlab-ci.yml) jalan
otomatis saat push tag berformat `design-kit-js@x.y.z`:

```bash
git tag design-kit-js@0.1.0
git push origin design-kit-js@0.1.0
```

CI otomatis bump versi di `packages/js/package.json` sesuai tag, lalu publish
pakai `$CI_JOB_TOKEN` bawaan GitLab (tidak perlu bikin token manual).

### Publish manual dari laptop (kalau perlu)

1. Bikin **Project Access Token** atau **Personal Access Token** dengan scope
   `api`, di GitLab → Settings → Access Tokens.
2. Jalankan:
   ```bash
   export NPM_TOKEN=glpat-xxxxxxxxxxxx
   npm publish --workspace packages/js
   ```

Cek dulu tanpa publish beneran dengan `--dry-run`:

```bash
npm publish --workspace packages/js --dry-run
```

### Cara project lain (consumer) install package ini

Tambahkan di `.npmrc` project consumer:

```
@tpl:registry=https://gitlab.com/api/v4/projects/intern-tpl-ipb%2Fdesign-system-project/packages/npm/
//gitlab.com/api/v4/projects/intern-tpl-ipb%2Fdesign-system-project/packages/npm/:_authToken=${NPM_TOKEN}
```

`NPM_TOKEN` diisi token dengan scope minimal `read_package_registry` — idealnya
**Deploy Token** khusus read-only, bukan personal token, supaya consumer tidak
dikasih akses lebih dari yang perlu. Lalu:

```bash
npm install @tpl/design-kit
```

---

## 4. Publish `tpl/design-kit` ke Composer

Saat ini package Composer masih dipakai lewat **path repository** (lokal, poin
1 di atas) karena baru satu consumer. Kalau nanti perlu dipakai project
Laravel lain di luar repo ini, ada dua opsi:

**Opsi A — Split ke repo sendiri (untuk konsumsi eksternal jangka panjang)**

```bash
git subtree split --prefix=packages/php -b design-kit-php-split
```

Push branch itu ke repo baru, lalu tag versi di sana (`v1.0.0`). Consumer
lain menambahkan sebagai VCS repository:

```json
"repositories": [
    { "type": "vcs", "url": "https://gitlab.com/intern-tpl-ipb/design-kit-php.git" }
]
```

**Opsi B — GitLab Composer Package Registry** (mirip skema npm di atas,
tapi untuk Composer) — lebih pas kalau `packages/php` sudah displit jadi repo
sendiri, karena `composer.json` package harus ada di root repo yang dipublish.

Belum ada keputusan final soal opsi ini — evaluasi ulang kalau sudah ada
consumer kedua.

---

## 5. Versioning

Ikuti semver (`MAJOR.MINOR.PATCH`):

- **Patch** — perbaikan bug, tidak ubah API/props komponen.
- **Minor** — komponen baru, token baru, tanpa breaking change.
- **Major** — rename/hapus token, ubah props komponen yang breaking. Wajib
  dicatat di changelog sebelum tag dibuat, karena dipakai lintas produk.

Format tag npm: `design-kit-js@x.y.z` (dipakai CI untuk trigger publish
otomatis, lihat bagian 3). Format tag Composer menyusul saat package displit
ke repo sendiri (bagian 4).
