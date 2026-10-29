/*
 * Menyalin font Lato 2.0 yang dibundel kit React ke kit Livewire, supaya kedua
 * kit memuat berkas font yang sama persis.
 *
 *   npm run fonts:sync    salin ulang dari kit React
 *   npm run fonts:check   gagal (exit 1) bila salinannya sudah tertinggal
 *
 * Sumbernya folder `react-design-system/src/lib/fonts` (berkas .woff2,
 * lato.css dengan @font-face-nya, dan lisensi OFL.txt); salinannya
 * `packages/design-kit-livewire/resources/fonts`. Lokasi kit React dibaca dari
 * CEPLOK_REACT_DIR; bawaannya folder `react-design-system` yang bersebelahan
 * dengan repo ini.
 *
 * Berkas disalin byte demi byte, tanpa diubah: lisensi OFL Lato memakai
 * Reserved Font Name, jadi berkas font yang dimodifikasi tidak boleh lagi
 * bernama "Lato".
 */
import { copyFileSync, existsSync, mkdirSync, readFileSync, readdirSync, rmSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const reactDir = resolve(root, process.env.CEPLOK_REACT_DIR ?? '../react-design-system');
const source = join(reactDir, 'src/lib/fonts');
const target = join(root, 'packages/design-kit-livewire/resources/fonts');

if (!existsSync(source)) {
    console.error(`Folder font sumber tidak ditemukan: ${source}`);
    console.error('Atur CEPLOK_REACT_DIR ke folder react-design-system.');
    process.exit(1);
}

const expected = readdirSync(source).sort();
const current = existsSync(target) ? readdirSync(target) : [];
const stale = current.filter((file) => !expected.includes(file));
const changed = expected.filter((file) => {
    const path = join(target, file);
    return !existsSync(path) || !readFileSync(path).equals(readFileSync(join(source, file)));
});

if (process.argv.includes('--check')) {
    if (stale.length || changed.length) {
        console.error(`Font kit Livewire tertinggal dari kit React: ${changed.length} berbeda, ${stale.length} usang.`);
        console.error('Jalankan: npm run fonts:sync');
        process.exit(1);
    }
    console.log(`Font sama dengan kit React (${expected.length} berkas).`);
} else if (!stale.length && !changed.length) {
    console.log('Font sudah sama dengan kit React.');
} else {
    mkdirSync(target, { recursive: true });
    stale.forEach((file) => rmSync(join(target, file)));
    changed.forEach((file) => copyFileSync(join(source, file), join(target, file)));
    console.log(`Font disalin dari ${source}: ${changed.length} diperbarui, ${stale.length} dihapus.`);
}
