# How to: menulis CHANGELOG dan merilis versi

Panduan untuk tim yang mengubah kode paket `ceplok-ui/design-kit-livewire`.
[CHANGELOG.md](CHANGELOG.md) adalah satu-satunya tempat versi paket
ditentukan. CI membaca berkas itu untuk membuat tag rilis, jadi formatnya
harus tepat.

## Daftar isi

1. [Kapan mencatat](#1-kapan-mencatat)
2. [Di mana mencatat](#2-di-mana-mencatat)
3. [Cara menulis entri](#3-cara-menulis-entri)
4. [Perubahan yang memutus kompatibilitas](#4-perubahan-yang-memutus-kompatibilitas)
5. [Memilih nomor versi](#5-memilih-nomor-versi)
6. [Merilis versi](#6-merilis-versi)
7. [Kesalahan yang sering terjadi](#7-kesalahan-yang-sering-terjadi)
8. [Checklist merge request](#8-checklist-merge-request)

## 1. Kapan mencatat

Catat setiap perubahan yang dirasakan **pemakai paket**, di merge request yang
sama dengan perubahannya, bukan menyusul.

| Dicatat | Tidak dicatat |
| --- | --- |
| Komponen, prop, slot, atau event baru | Perubahan yang hanya menyentuh situs docs |
| Markup, class, warna, atau ARIA komponen berubah | Refactor internal yang tidak mengubah HTML hasil render |
| Bug komponen diperbaiki | Tes baru tanpa perubahan perilaku |
| Token, font, atau ikon bertambah atau berubah | Perubahan CI, Docker, atau deploy |
| Prop, token, atau ikon diganti nama atau dihapus | Perbaikan salah ketik di komentar |

Ragu? Tanyakan: "Apakah aplikasi yang memakai paket ini akan melihat bedanya
setelah update?" Kalau ya, catat.

## 2. Di mana mencatat

Selalu di bagian `## [Unreleased]`, paling atas. Kelompokkan entri di bawah
salah satu subjudul ini, dengan urutan seperti ini:

| Subjudul | Isinya |
| --- | --- |
| `### Ditambahkan` | Komponen, prop, slot, event, token, atau ikon baru |
| `### Diubah` | Perilaku atau tampilan yang sudah ada berubah |
| `### Diperbaiki` | Bug yang diperbaiki |
| `### Dihapus` | Komponen, prop, token, atau ikon yang dibuang |

Pakai subjudul yang sudah ada. Kalau belum ada, buat dengan urutan di atas.
Jangan membuat subjudul baru di luar empat itu, kecuali bagian
`### Migrasi …` untuk perubahan yang memutus kompatibilitas (lihat
[bagian 4](#4-perubahan-yang-memutus-kompatibilitas)).

## 3. Cara menulis entri

Satu poin untuk satu perubahan, ditulis dari sudut pandang pemakai: apa yang
sekarang bisa atau harus mereka lakukan.

- Sebut komponen, prop, event, dan nilai di dalam backtick:
  `` `select` ``, `` `helper-text` ``, `` `page-change` ``.
- Pakai nama Blade (kebab-case), bukan nama React. Sebut padanan React-nya
  bila membantu, mis. "padanan `title={null}`".
- Jelaskan *apa* yang berubah, bukan *bagaimana* kodenya diubah.
- Bahasa Indonesia, baris dipotong sekitar 78 karakter, lanjutan baris
  diindentasi dua spasi.
- Sebut sumber rancangan sebagai "desain", jangan menulis nama aplikasi
  desainnya.
- Rincian yang panjang boleh dipecah jadi sub-poin, seperti entri Table di
  CHANGELOG.

Contoh:

```markdown
### Ditambahkan

- `select`: prop `info` menampilkan ikon informasi di samping label, dengan
  teksnya sebagai tooltip. Setara `info` di kit React.

### Diperbaiki

- `pagination`: tombol halaman terakhir tidak lagi hilang saat
  `total-pages` bernilai `1`.
```

Hindari entri seperti ini:

```markdown
- fix bug select                         ← komponen apa, bug apa?
- refactor SelectOptions.php             ← pemakai tidak peduli nama berkas
- Update select sesuai desain baru       ← apa yang berubah bagi pemakai?
```

## 4. Perubahan yang memutus kompatibilitas

Perubahan memutus kompatibilitas (breaking) bila aplikasi pemakai harus
mengubah kodenya setelah update, mis. prop diganti nama, nilai pilihan
dihapus, event diganti, atau ikon dihapus.

1. Awali poinnya dengan `**Memutus kompatibilitas:**`, dan tulis di bawah
   subjudul yang sesuai (biasanya `Diubah` atau `Dihapus`).
2. Sebut pengganti atau cara pindahnya di poin yang sama.
3. Bila langkah pindahnya lebih dari satu kalimat, tambahkan bagian
   `### Migrasi dari <versi sebelumnya>` di bawah subjudul lain, seperti
   bagian "Migrasi dari `tpl/design-kit`" yang sudah ada.

```markdown
### Diubah

- **Memutus kompatibilitas:** `badge`: nilai `variant="primary"` diganti
  `variant="brand"`, mengikuti kit React. Ganti semua `variant="primary"`
  pada Badge.
```

## 5. Memilih nomor versi

Versi mengikuti [Semantic Versioning](https://semver.org/lang/id/):
`MAJOR.MINOR.PATCH`.

| Isi `[Unreleased]` | Selama `0.x` (sekarang) | Setelah `1.0.0` |
| --- | --- | --- |
| Hanya `Diperbaiki` | naikkan PATCH: `0.1.0` → `0.1.1` | `1.2.0` → `1.2.1` |
| Ada `Ditambahkan` atau `Diubah`, tanpa breaking | naikkan MINOR: `0.1.1` → `0.2.0` | `1.2.1` → `1.3.0` |
| Ada **Memutus kompatibilitas** | naikkan MINOR: `0.2.0` → `0.3.0` | naikkan MAJOR: `1.3.0` → `2.0.0` |

Selama `0.x`, rilis minor boleh breaking. Pemakai dengan constraint `^0.1`
hanya menerima `0.1.x`, jadi mereka tidak tiba-tiba ikut naik ke `0.2.0`.

Nomor versi hanya boleh berbentuk `X.Y.Z`. Akhiran seperti `-beta.1` tidak
dibaca CI dan tidak akan di-tag.

## 6. Merilis versi

Rilis dilakukan lewat merge request biasa. **Jangan membuat tag sendiri**,
baik di repo utama maupun di repo paket.

**Langkah 1.** Di branch rilis dari `staging`, ubah judul `## [Unreleased]`
menjadi nomor versi dan tanggal rilis (format `YYYY-MM-DD`), lalu tambahkan
`## [Unreleased]` kosong di atasnya.

Sebelum:

```markdown
## [Unreleased]

### Ditambahkan

- `select`: prop `info` …
```

Sesudah:

```markdown
## [Unreleased]

## [0.2.0] - 2026-11-10

### Ditambahkan

- `select`: prop `info` …
```

`v0.1.0` dan `v0.1.1` sudah dirilis manual sebelum alur ini ada, jadi nomor
berikutnya minimal `0.1.2`. Di rilis berikutnya, hapus juga kalimat "Rilis
pertama, direncanakan sebagai `0.1.0`." di bawah judulnya.

**Langkah 2.** Commit dengan pesan `chore(release): rilis 0.2.0`, lalu buat
merge request ke `staging`, kemudian `staging` ke `main` seperti biasa.

**Langkah 3.** Setelah merge ke `main` dan quality check lolos, CI:

1. Mendorong isi `packages/design-kit-livewire` ke branch `main` repo paket,
   [takumifahri/ceplok-ui-design-kit-livewire](https://github.com/takumifahri/ceplok-ui-design-kit-livewire).
   Repo itu yang dibaca Packagist; jangan commit langsung ke sana.
2. Membaca judul versi **teratas** di CHANGELOG (`0.2.0`).
3. Membuat tag `v0.2.0` di repo paket, bila tag itu belum ada.

Hasilnya terlihat di tab Actions GitHub, job "Publikasi paket": ada notice
`v0.2.0 dibuat di …`. Packagist membaca tag baru itu lewat webhook.

Merge ke `main` yang tidak menambah judul versi baru hanya memperbarui branch
`main` repo paket. Tidak ada versi baru yang dibuat.

## 7. Kesalahan yang sering terjadi

**Judul versi salah format, jadi tag tidak dibuat.** CI hanya mengenali
`## [0.2.0]` (dua `#`, spasi, kurung siku, tiga angka). Contoh yang tidak
terbaca: `## 0.2.0`, `## [v0.2.0]`, `### [0.2.0]`, `## [0.2]`.

**Lupa menambahkan `## [Unreleased]` kosong.** Entri berikutnya jadi masuk ke
bagian versi yang sudah dirilis. Selalu tambahkan judul itu di MR rilis.

**Mengedit bagian versi yang sudah dirilis.** Isi versi lama hanya boleh
diubah untuk salah ketik. Perubahan baru selalu masuk `[Unreleased]`, meski
memperbaiki sesuatu dari versi lama.

**Memakai ulang nomor versi.** Tag yang sudah ada tidak pernah ditimpa CI.
Bila `v0.2.0` sudah ada, judul `## [0.2.0]` yang baru dilewati tanpa tag.
Naikkan nomornya.

**Merilis dua versi dalam satu merge.** CI hanya men-tag versi teratas. Satu
merge ke `main`, paling banyak satu versi baru.

**Konflik di CHANGELOG saat merge.** Dua branch biasanya sama-sama menambah
poin di `[Unreleased]`. Pertahankan poin dari kedua sisi di subjudul yang
sesuai.

## 8. Checklist merge request

- [ ] Perubahan yang dirasakan pemakai sudah dicatat di `[Unreleased]`.
- [ ] Poin ada di subjudul yang tepat: Ditambahkan, Diubah, Diperbaiki, atau
      Dihapus.
- [ ] Komponen, prop, dan event ditulis dalam backtick dengan nama Blade.
- [ ] Perubahan breaking diawali **Memutus kompatibilitas:** dan
      menyebut cara pindahnya.
- [ ] Khusus MR rilis: judul `## [X.Y.Z] - YYYY-MM-DD` sudah benar, dan
      `## [Unreleased]` kosong ada di atasnya.
