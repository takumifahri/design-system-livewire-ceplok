/*
 * Menyalin token dari kit React ke kit Livewire supaya kedua kit memakai nilai
 * yang sama persis. Sumber tunggalnya `react-design-system/src/lib/tokens.css`;
 * salinannya `packages/design-kit-livewire/resources/css/tokens.css`.
 *
 *   npm run tokens:sync    salin ulang dari kit React
 *   npm run tokens:check   gagal (exit 1) bila salinannya sudah tertinggal
 *
 * Lokasi kit React dibaca dari CEPLOK_REACT_DIR; bawaannya folder
 * `react-design-system` yang bersebelahan dengan repo ini.
 *
 * Komentar pembuka sumbernya diganti dengan HEADER di bawah; sisanya disalin
 * apa adanya, hanya akhir barisnya dinormalkan ke LF.
 */
import { existsSync, readFileSync, writeFileSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const reactDir = resolve(root, process.env.CEPLOK_REACT_DIR ?? '../react-design-system');
const source = join(reactDir, 'src/lib/tokens.css');
const target = join(root, 'packages/design-kit-livewire/resources/css/tokens.css');

const HEADER = `/*
 * Ceplok Design Kit — design tokens (Livewire)
 *
 * Salinan dari react-design-system/src/lib/tokens.css — jangan diedit di sini.
 * Ubah sumbernya di kit React, lalu jalankan \`npm run tokens:sync\` dari root
 * repo ini supaya kedua kit memakai nilai yang sama persis.
 *
 * \`static\` = SEMUA token selalu di-emit ke :root, walau tidak dipakai utility.
 * Ini wajib untuk design system: swatch/preview yang memakai var(--color-*)
 * secara inline tetap resolve, dan consumer bebas memakai token apa pun.
 */
`;

if (!existsSync(source)) {
    console.error(`Token sumber tidak ditemukan: ${source}`);
    console.error('Atur CEPLOK_REACT_DIR ke folder react-design-system.');
    process.exit(1);
}

const body = readFileSync(source, 'utf8').replace(/\r\n/g, '\n');
const opening = body.match(/^\/\*[\s\S]*?\*\/\n/);
if (!opening) {
    console.error('Format tokens.css sumber berubah: komentar pembukanya tidak ditemukan.');
    process.exit(1);
}

const expected = HEADER + body.slice(opening[0].length);
const current = existsSync(target) ? readFileSync(target, 'utf8') : '';

if (process.argv.includes('--check')) {
    if (current !== expected) {
        console.error('tokens.css kit Livewire tertinggal dari kit React. Jalankan: npm run tokens:sync');
        process.exit(1);
    }
    console.log('tokens.css sama dengan kit React.');
} else if (current === expected) {
    console.log('tokens.css sudah sama dengan kit React.');
} else {
    writeFileSync(target, expected);
    console.log(`tokens.css disalin dari ${source}`);
}
