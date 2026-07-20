@php
    $palettes = [
        'primary' => [
            'name' => 'Biru — Primary',
            'description' => 'Identitas utama aplikasi State Security Service',
            'colors' => [900 => '#233876', 800 => '#1E429F', 700 => '#1A56DB', 600 => '#1C64F2', 500 => '#3F83F8', 400 => '#76A9FA', 300 => '#A4CAFE', 200 => '#C3DDFD', 100 => '#E1EFFE', 50 => '#EBF5FF'],
        ],
        'gray' => [
            'name' => 'Abu-abu — Neutral',
            'description' => 'Teks, border, dan permukaan antarmuka',
            'colors' => [900 => '#111827', 800 => '#1F2937', 700 => '#374151', 600 => '#4B5563', 500 => '#6B7280', 400 => '#9CA3AF', 300 => '#D1D5DB', 200 => '#E5E7EB', 100 => '#F3F4F6', 50 => '#F9FAFB'],
        ],
        'danger' => [
            'name' => 'Merah — Danger',
            'description' => 'Error dan tindakan destruktif',
            'colors' => [900 => '#771D1D', 800 => '#9B1C1C', 700 => '#C81E1E', 600 => '#E02424', 500 => '#F05252', 400 => '#F98080', 300 => '#F8B4B4', 200 => '#FBD5D5', 100 => '#FDE8E8', 50 => '#FDF2F2'],
        ],
        'orange' => [
            'name' => 'Orange — Alert',
            'description' => 'Peringatan yang memerlukan perhatian',
            'colors' => [900 => '#771D1D', 800 => '#8A2C0D', 700 => '#B43403', 600 => '#D03801', 500 => '#FF5A1F', 400 => '#FF8A4C', 300 => '#FDBA8C', 200 => '#FCD9BD', 100 => '#FEECDC', 50 => '#FFF8F1'],
        ],
        'warning' => [
            'name' => 'Kuning — Warning',
            'description' => 'Border dan aksen pada peringatan',
            'colors' => [900 => '#633112', 800 => '#723B13', 700 => '#8E4B10', 600 => '#9F580A', 500 => '#C27803', 400 => '#E3A008', 300 => '#FACA15', 200 => '#FCE96A', 100 => '#FDF6B2', 50 => '#FDFDEA'],
        ],
        'success' => [
            'name' => 'Hijau — Success',
            'description' => 'Status proses berhasil dan aplikasi Digika',
            'colors' => [900 => '#014737', 800 => '#03543F', 700 => '#046C4E', 600 => '#057A55', 500 => '#0E9F6E', 400 => '#31C48D', 300 => '#84E1BC', 200 => '#BCF0DA', 100 => '#DEF7EC', 50 => '#F3FAF7'],
        ],
        'purple' => [
            'name' => 'Ungu — Simaya',
            'description' => 'Identitas dan aksen produk Simaya',
            'colors' => [900 => '#4A1D96', 800 => '#5521B5', 700 => '#6C2BD9', 600 => '#7E3AF2', 500 => '#9061F9', 400 => '#AC94FA', 300 => '#CABFFD', 200 => '#DCD7FE', 100 => '#EDEBFE', 50 => '#F6F5FF'],
        ],
    ];
@endphp

<x-docs-layout section="foundations" title="Colors">
    <x-doc-hero eyebrow="Foundations · Color" title="Color tokens" description="Klik warna untuk menyalin nilai HEX. Gunakan skala primitive untuk membentuk semantic token, bukan langsung untuk menentukan makna.">
        <span class="mt-5 inline-block w-fit rounded-full border border-primary-200 bg-primary-50 px-3 py-1.5 text-xs font-bold text-primary-700">7 palette · 70 tokens</span>
    </x-doc-hero>

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <div class="space-y-5">
            @foreach ($palettes as $key => $palette)
                <article class="ds-card overflow-hidden p-4 sm:p-6">
                    <div class="mb-4 flex flex-col justify-between gap-1 sm:flex-row sm:items-end">
                        <div>
                            <h3 class="text-lg font-black text-gray-900">{{ $palette['name'] }}</h3>
                            <p class="mt-0.5 text-sm text-gray-500">{{ $palette['description'] }}</p>
                        </div>
                        <code class="text-xs font-bold text-gray-400">colors.{{ $key }}</code>
                    </div>

                    <div class="grid grid-cols-2 overflow-hidden rounded-xl sm:grid-cols-5 lg:grid-cols-10">
                        @foreach ($palette['colors'] as $shade => $hex)
                            <button
                                @click="copyToken('{{ $hex }}', '{{ $key }}-{{ $shade }}')"
                                class="group relative flex min-h-24 flex-col justify-between p-3 text-left transition-transform hover:z-10 hover:-translate-y-1 focus:z-10 sm:min-h-28"
                                style="background-color: {{ $hex }}"
                                aria-label="Salin {{ $key }} {{ $shade }}, {{ $hex }}"
                            >
                                <span class="text-sm font-black {{ $shade >= 500 ? 'text-white' : 'text-gray-900' }}">{{ $shade }}</span>
                                <span class="text-[10px] font-bold tracking-wide {{ $shade >= 500 ? 'text-white/80' : 'text-gray-700' }}">{{ $hex }}</span>
                                <span x-show="copied === '{{ $key }}-{{ $shade }}'" x-transition class="absolute inset-0 grid place-items-center bg-gray-900/80 text-xs font-bold text-white">Tersalin!</span>
                            </button>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-12">
            <p class="ds-eyebrow">Semantic layer</p>
            <h2 class="mt-2 text-heading-3 font-black text-gray-900">Tokens berdasarkan fungsi</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['Brand', 'Aksi dan identitas utama', '#1C64F2', 'bg-brand', 'text-white'],
                    ['Error', 'Error dan aksi destruktif', '#E02424', 'bg-feedback-error', 'text-white'],
                    ['Warning', 'Status perlu perhatian', '#FACA15', 'bg-feedback-warning', 'text-gray-900'],
                    ['Success', 'Proses berhasil', '#057A55', 'bg-feedback-success', 'text-white'],
                ] as [$name, $description, $hex, $class, $textClass])
                    <div class="ds-card p-4">
                        <div class="{{ $class }} {{ $textClass }} grid size-11 place-items-center rounded-xl">
                            <span class="size-3 rounded-full border-2 border-current"></span>
                        </div>
                        <h3 class="mt-4 text-sm font-black text-gray-900">{{ $name }}</h3>
                        <p class="mt-1 text-xs leading-5 text-gray-500">{{ $description }}</p>
                        <code class="mt-3 block text-[11px] font-bold text-gray-400">{{ $hex }}</code>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-docs-layout>
