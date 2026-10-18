@php
    $radiusScale = [['rounded-sm', '2px'], ['rounded', '4px'], ['rounded-md', '6px'], ['rounded-lg', '8px'], ['rounded-xl', '12px'], ['rounded-2xl', '16px'], ['rounded-3xl', '24px'], ['rounded-full', '9999px']];
    $borderWidths = [['border-0', '0px', 'outline outline-dashed outline-gray-300'], ['border', '1px', ''], ['border-2', '2px', ''], ['border-4', '4px', ''], ['border-8', '8px', '']];
    $code = fn (string $c) => '<code class="text-xs font-bold text-gray-700">'.e($c).'</code>';
@endphp

<x-docs.layout title="Border">
    <x-docs.page
        eyebrow="Foundations · Border"
        title="Radius, ketebalan, dan gaya garis"
        description="Border membentuk kontur komponen — radius menentukan karakter sudut, ketebalan memberi penekanan, dan gaya garis membedakan status."
        :toc="['radius' => 'Border radius', 'width' => 'Border width', 'style' => 'Border style', 'prinsip' => 'Prinsip penggunaan']"
    >
        <x-docs.section id="radius" title="Border radius">
            <x-docs.lead>
                Dari {!! $code('rounded-sm') !!} (2px) hingga {!! $code('rounded-full') !!} (pill/lingkaran). Gunakan {!! $code('rounded') !!}
                sebagai default.
            </x-docs.lead>

            <article class="ds-card p-5 sm:p-7">
                <div class="ds-scroll-x flex items-center gap-4 overflow-x-auto pb-2">
                    @foreach ($radiusScale as [$class, $value])
                        <div @class([$class, $class === 'rounded-full' ? 'size-40' : 'aspect-6/7 w-40', 'flex shrink-0 flex-col items-center justify-center gap-1 bg-primary-600 text-center'])>
                            <code class="text-sm font-bold text-white">.{{ $class }}</code>
                            <code class="text-xs text-primary-200">{{ $value }}</code>
                        </div>
                    @endforeach
                </div>
            </article>

            <x-docs.code>
                @verbatim
                {{-- Radius sesuai jenis komponen --}}
                <button class="rounded-lg border border-border px-4 py-2">Tombol</button>
                <div class="rounded-2xl border border-border p-6">Kartu</div>
                <span class="rounded-full bg-primary-50 px-2.5 py-1">Badge</span>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="width" title="Border width">
            <x-docs.lead>
                Lima tingkat dari {!! $code('border-0') !!} hingga {!! $code('border-8') !!}. Default {!! $code('border') !!} (1px) cukup untuk
                mayoritas kebutuhan.
            </x-docs.lead>

            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    @foreach ($borderWidths as [$class, $value, $extra])
                        <div @class([$class, $extra, 'flex aspect-6/7 flex-col items-center justify-center gap-1 rounded-lg border-primary-300 bg-white text-center'])>
                            <code class="text-sm font-bold break-all text-primary-700">.{{ $class }}</code>
                            <code class="text-xs text-gray-400">{{ $value }}</code>
                        </div>
                    @endforeach
                </div>
            </article>

            <x-docs.code>
                @verbatim
                {{-- Ketebalan — di atas 1px hanya untuk penekanan --}}
                <div class="border border-border">Default 1px</div>
                <div class="border-2 border-primary-600">Sedang terpilih</div>
                <div class="border-b border-border">Garis bawah saja</div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="style" title="Border style">
            <x-docs.lead>
                Gaya standar adalah {!! $code('border-solid') !!} — dipakai untuk seluruh kontur komponen.
            </x-docs.lead>

            <article class="ds-card p-5 sm:p-7">
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="flex aspect-6/7 flex-col items-center justify-center gap-1 rounded-lg border-2 border-solid border-white bg-primary-500 text-center ring-1 ring-gray-200">
                        <code class="text-sm font-bold break-all text-white">.border-solid</code>
                        <code class="text-xs text-primary-100">solid</code>
                    </div>
                </div>
            </article>

            <x-docs.code>
                @verbatim
                {{-- Gaya garis --}}
                <div class="border border-solid border-border">Solid — default</div>
                <div class="border border-dashed border-gray-300">Putus-putus</div>
                @endverbatim
            </x-docs.code>
        </x-docs.section>

        <x-docs.section id="prinsip" title="Prinsip penggunaan">
            <x-docs.principles>
                <x-docs.principle>Kontrol interaktif memakai {!! $code('rounded-lg') !!}, kartu {!! $code('rounded-2xl') !!}, badge/avatar {!! $code('rounded-full') !!}.</x-docs.principle>
                <x-docs.principle>Gunakan token {!! $code('border-border') !!} (gray-200) untuk garis pemisah dan kontur netral.</x-docs.principle>
                <x-docs.principle>Ketebalan di atas 1px hanya untuk penekanan — fokus, seleksi aktif, atau validasi.</x-docs.principle>
                <x-docs.principle>Jangan mencampur radius berbeda pada elemen bertumpuk; sudut dalam sebaiknya lebih kecil dari sudut luar.</x-docs.principle>
            </x-docs.principles>
        </x-docs.section>
    </x-docs.page>
</x-docs.layout>
