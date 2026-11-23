@php
    use App\Docs\Navigation;
@endphp

{{--
    Pencarian halaman (Ctrl+K / ⌘K / "/") — port DocsSearch di docs React.
    Logikanya di resources/js/docs/search.js; dibuka lewat event `docs-search`.
--}}
<dialog
    x-data="docsSearch(@js(Navigation::pages()))"
    x-on:docs-search.window="show()"
    x-on:keydown.window="shortcutKey($event)"
    x-on:close="reset()"
    x-on:click="clickBackdrop($event)"
    closedby="any"
    aria-label="Cari halaman"
    class="m-auto mt-[12vh] w-[min(36rem,calc(100vw-2rem))] overflow-hidden rounded-xl border border-border bg-white p-0 shadow-xl backdrop:bg-gray-900/40 dark:border-gray-700 dark:bg-gray-800 dark:backdrop:bg-gray-900/70"
>
    <div class="flex items-center gap-3 border-b border-border px-4 dark:border-gray-700">
        <svg class="size-4.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" d="m20 20-4.5-4.5M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" />
        </svg>
        <input
            x-ref="input"
            x-model="query"
            x-on:input="active = 0"
            x-on:keydown="move($event)"
            x-bind:aria-activedescendant="results.length ? `docs-search-${active}` : null"
            type="text"
            role="combobox"
            aria-expanded="true"
            aria-controls="docs-search-list"
            aria-autocomplete="list"
            autocomplete="off"
            spellcheck="false"
            placeholder="Cari halaman… (mis. tombol, tanggal, dropdown)"
            class="h-12 min-w-0 flex-1 bg-transparent text-sm text-gray-900 placeholder:text-gray-400 focus:outline-none dark:text-white"
        >
        <kbd class="shrink-0 rounded border border-border px-1.5 py-0.5 text-[10px] font-bold text-gray-500 max-sm:hidden dark:border-gray-600 dark:text-gray-400">Esc</kbd>
    </div>

    <ul x-show="results.length" id="docs-search-list" role="listbox" aria-label="Halaman" class="ds-scroll-y max-h-[min(60vh,26rem)] overflow-y-auto p-2">
        <template x-for="(page, i) in results" x-bind:key="page.url">
            <li x-bind:id="`docs-search-${i}`" role="option" x-bind:aria-selected="i === active">
                <a
                    x-bind:href="page.url"
                    x-on:mousemove="active = i"
                    x-on:click="click($event, page.url)"
                    x-bind:class="{ 'bg-primary-50 dark:bg-primary-900': i === active }"
                    tabindex="-1"
                    class="flex items-center justify-between gap-3 rounded-lg px-3 py-2.5"
                >
                    <span class="min-w-0">
                        <span
                            x-text="page.label"
                            x-bind:class="i === active ? 'text-primary-700 dark:text-primary-300' : 'text-gray-900 dark:text-white'"
                            class="block truncate text-sm font-bold"
                        ></span>
                        <span x-text="page.letak" class="block truncate text-xs text-gray-500 dark:text-gray-400"></span>
                    </span>
                    <span x-show="i === active" aria-hidden="true" class="shrink-0 text-xs text-gray-400">↵</span>
                </a>
            </li>
        </template>
    </ul>

    <p x-show="! results.length" x-cloak class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
        Tidak ada halaman yang cocok dengan “<span x-text="query.trim()"></span>”.
    </p>

    {{-- Petunjuk papan ketik — tidak berarti di layar sentuh, jadi disembunyikan di mobile. --}}
    <div class="flex gap-4 border-t border-border bg-gray-50 px-4 py-2.5 text-[11px] text-gray-500 max-sm:hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
        <span>↑↓ pindah</span>
        <span>↵ buka</span>
        <span>Esc tutup</span>
        <span class="ml-auto"><span x-text="shortcut">Ctrl K</span> dari halaman mana pun</span>
    </div>
</dialog>
