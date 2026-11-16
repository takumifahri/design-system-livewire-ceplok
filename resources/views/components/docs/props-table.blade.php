{{--
    Tabel "Properties" pada halaman komponen. Tiap baris `rows` berisi
    [nama, tipe, default, keterangan]; nama ditulis seperti atribut Blade-nya
    (kebab-case), mis. `icon-only`.
--}}
@props([
    'rows' => [],
    'minWidth' => '40rem',
])

<div {{ $attributes->class('ds-card overflow-hidden') }}>
    <div class="ds-scroll-x overflow-x-auto">
        <table class="w-full text-left text-sm" style="min-width: {{ $minWidth }}">
            <thead class="border-b border-border bg-surface-subtle text-xs font-black tracking-wide text-gray-500 uppercase dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3">Prop</th>
                    <th class="px-5 py-3">Tipe</th>
                    <th class="px-5 py-3">Default</th>
                    <th class="px-5 py-3">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border dark:divide-gray-700">
                @foreach ($rows as [$name, $type, $default, $description])
                    <tr>
                        {{-- nowrap: nama kebab-case (icon-only) jangan sampai patah di tanda hubung. --}}
                        <td class="px-5 py-3"><code class="text-xs font-bold whitespace-nowrap text-primary-700 dark:text-primary-400">{{ $name }}</code></td>
                        <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $type }}</td>
                        <td class="px-5 py-3"><code class="text-xs text-gray-500 dark:text-gray-400">{{ $default }}</code></td>
                        <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
