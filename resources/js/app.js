/*
 * Livewire 4 sudah membawa Alpine. Keduanya dibundel di sini (layout memakai
 * @livewireScriptConfig, bukan @livewireScripts) supaya plugin Alpine kit
 * terdaftar sebelum Alpine berjalan. Jangan muat Alpine terpisah: Alpine yang
 * dimuat dua kali gagal diinisialisasi.
 */
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import ceplok from '../../vendor/ceplok-ui/design-kit-livewire/resources/js/index.js';
import copy from './docs/copy';
import docsDropdownPlayground from './docs/dropdown-playground';
import docsLayout from './docs/layout';
import docsSearch from './docs/search';
import docsToc from './docs/toc';
import setDocsTheme from './docs/theme';
import docsTirai from './docs/transition';
import spotlight from './docs/spotlight';
import { codeWindow, countUp, magnetic, parallax, reveal, scrollProgress, splitWords, tilt } from './docs/motion';

Alpine.plugin(ceplok);

// Perilaku situs dokumentasi; bukan bagian dari kit.
Alpine.data('designSystem', copy);
Alpine.data('docsLayout', docsLayout);
Alpine.data('docsDropdownPlayground', docsDropdownPlayground);
Alpine.data('docsSearch', docsSearch);
Alpine.data('docsToc', docsToc);
Alpine.data('docsTirai', docsTirai);
Alpine.directive('spotlight', spotlight);
Alpine.data('docsCodeWindow', codeWindow);
Alpine.directive('reveal', reveal);
Alpine.directive('split-words', splitWords);
Alpine.directive('count-up', countUp);
Alpine.directive('magnetic', magnetic);
Alpine.directive('tilt', tilt);
Alpine.directive('scroll-progress', scrollProgress);
Alpine.directive('parallax', parallax);
Alpine.magic('docsTheme', () => setDocsTheme);

// Font pratinjau Playground (kontrol "Font" di x-docs.controls, dipakai
// x-docs.stage). Store, karena kontrol dan panggungnya elemen terpisah.
Alpine.store('preview', { font: 'sans' });

Livewire.start();
