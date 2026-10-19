/*
 * Menyalin set ikon Flowbite yang dipakai kit React ke kit Livewire, supaya
 * ikon kedua kit identik sampai ke path SVG-nya.
 *
 *   npm run icons:sync    tulis ulang dari kit React
 *   npm run icons:check   gagal (exit 1) bila salinannya sudah tertinggal
 *
 * Sumbernya paket `flowbite-react-icons` yang terpasang di kit React (lokasi
 * kit React dibaca dari CEPLOK_REACT_DIR; bawaannya folder
 * `react-design-system` yang bersebelahan dengan repo ini). Paket Blade resmi
 * `themesberg/flowbite-blade-icons` tidak dipakai karena masih berisi set
 * ikon generasi lama: viewBox, path, dan daftar ikonnya berbeda.
 *
 * Setiap komponen ikon dirender menjadi SVG statis dan ditulis ke
 * `packages/design-kit-livewire/resources/svg/flowbite/` sebagai `o-<nama>.svg`
 * (outline) dan `s-<nama>.svg` (solid). Kit mendaftarkannya ke blade-icons
 * dengan prefix `ceplok`, jadi `Bell` dari `icons/outline` di React menjadi
 * `svg('ceplok-o-bell')` atau `<x-ceplok-o-bell />` di Blade.
 */
import { existsSync, mkdirSync, readFileSync, readdirSync, rmSync, writeFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const reactDir = resolve(root, process.env.CEPLOK_REACT_DIR ?? '../react-design-system');
const target = join(root, 'packages/design-kit-livewire/resources/svg/flowbite');

if (!existsSync(join(reactDir, 'node_modules/flowbite-react-icons'))) {
    console.error(`flowbite-react-icons tidak ditemukan di ${reactDir}/node_modules.`);
    console.error('Jalankan `npm install` di kit React, atau atur CEPLOK_REACT_DIR.');
    process.exit(1);
}

const require = createRequire(join(reactDir, 'package.json'));
const { createElement } = require('react');
const { renderToStaticMarkup } = require('react-dom/server');
const iconsDir = join(reactDir, 'node_modules/flowbite-react-icons');
const { version } = JSON.parse(readFileSync(join(iconsDir, 'package.json'), 'utf8'));
const sets = {
    o: require('flowbite-react-icons/outline'),
    s: require('flowbite-react-icons/solid'),
};

/** `ArrowRightToBracket` → `arrow-right-to-bracket`. */
const kebab = (name) =>
    name
        .replace(/([a-z0-9])([A-Z])/g, '$1-$2')
        .replace(/([A-Z])([A-Z][a-z])/g, '$1-$2')
        .toLowerCase();

const expected = new Map();
for (const [prefix, icons] of Object.entries(sets)) {
    for (const [name, component] of Object.entries(icons)) {
        const svg = renderToStaticMarkup(createElement(component))
            .replace(/<(path|circle|rect|line|polyline|polygon|ellipse)([^>]*)><\/\1>/g, '<$1$2/>');
        expected.set(`${prefix}-${kebab(name)}.svg`, `${svg}\n`);
    }
}

const license = readFileSync(join(iconsDir, 'LICENSE'), 'utf8').replace(/\r\n/g, '\n');
expected.set(
    'LICENSE.md',
    `Ikon di folder ini dirender dari flowbite-react-icons ${version} (scripts/sync-icons.mjs).\n\n${license}`,
);

const current = existsSync(target) ? readdirSync(target) : [];
const stale = current.filter((file) => !expected.has(file));
const changed = [...expected].filter(([file, content]) => {
    const path = join(target, file);
    return !existsSync(path) || readFileSync(path, 'utf8') !== content;
});

if (process.argv.includes('--check')) {
    if (stale.length || changed.length) {
        console.error(`Ikon kit Livewire tertinggal dari flowbite-react-icons ${version}: ${changed.length} berbeda, ${stale.length} usang.`);
        console.error('Jalankan: npm run icons:sync');
        process.exit(1);
    }
    console.log(`Ikon sama dengan flowbite-react-icons ${version} (${expected.size - 1} ikon).`);
} else {
    mkdirSync(target, { recursive: true });
    for (const file of stale) rmSync(join(target, file));
    for (const [file, content] of changed) writeFileSync(join(target, file), content);
    console.log(`flowbite-react-icons ${version}: ${expected.size - 1} ikon — ${changed.length} ditulis, ${stale.length} dihapus.`);
}
