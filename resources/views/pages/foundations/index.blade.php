@php
    $brands = count(glob(base_path('vendor/ceplok-ui/design-kit-livewire/resources/svg/brands/*.svg')) ?: []);
@endphp

<x-docs.layout title="Foundations">
    <x-docs.hero
        eyebrow="Foundations · Overview"
        title="Foundations"
        description="Token dan aturan dasar yang menjadi pijakan seluruh komponen: warna, tipografi, jarak, garis, kedalaman, dan ikon. Semuanya terbit sebagai CSS token lewat tokens.css di paket ceplok-ui/design-kit-livewire."
    />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        {{-- grid-cols-1 (minmax(0,1fr)): tanpa itu kolom di bawah sm melebar mengikuti pratinjau terlebar. --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-docs.overview-card route="foundations.colors" name="Colors" desc="Delapan palet primitive dan lapisan semantic token yang memisahkan makna dari nilai warna." wide>
                <div class="rounded-xl bg-surface-subtle p-5">
                    <div class="flex gap-2">
                        @foreach (['bg-primary-600', 'bg-green-500', 'bg-yellow-300', 'bg-red-500', 'bg-purple-500'] as $swatch)
                            <span class="{{ $swatch }} h-12 flex-1 rounded-lg"></span>
                        @endforeach
                    </div>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="foundations.typography" name="Typography" desc="Skala tipe Lato dari Display hingga Caption, lengkap dengan bobot dan tinggi baris.">
                <div class="rounded-xl bg-surface-subtle p-5">
                    <p class="text-heading-2 leading-none font-black text-gray-900">Aa</p>
                    <p class="mt-2 text-body-sm text-gray-500">Lato · 48 → 12px</p>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="foundations.spacing" name="Spacing" desc="Skala jarak berbasis 4px untuk padding, margin, dan gap di seluruh produk.">
                <div class="flex flex-col justify-center gap-1.5 rounded-xl bg-surface-subtle p-5">
                    @foreach (['w-6', 'w-12', 'w-20', 'w-32'] as $width)
                        <span class="{{ $width }} h-2.5 rounded-xs bg-primary-600"></span>
                    @endforeach
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="foundations.border" name="Border" desc="Radius sudut, ketebalan garis, dan gaya garis yang membentuk kontur komponen.">
                <div class="flex items-center gap-3 rounded-xl bg-surface-subtle p-5">
                    <span class="size-12 rounded-sm bg-primary-600"></span>
                    <span class="size-12 rounded-lg bg-primary-600"></span>
                    <span class="size-12 rounded-2xl bg-primary-600"></span>
                    <span class="size-12 rounded-full bg-primary-600"></span>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="foundations.elevation" name="Elevation" desc="Enam tingkat bayangan untuk menyusun hierarki kedalaman antarpermukaan.">
                <div class="flex items-center gap-4 rounded-xl bg-surface-subtle p-5">
                    <span class="size-12 rounded-lg bg-white shadow-sm"></span>
                    <span class="size-12 rounded-lg bg-white shadow-md"></span>
                    <span class="size-12 rounded-lg bg-white shadow-lg"></span>
                    <span class="size-12 rounded-lg bg-white shadow-xl"></span>
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="foundations.icons" name="Icons" :desc="'Set ikon solid dan outline, plus '.$brands.' logo brand — semuanya terdaftar di blade-icons dengan awalan ceplok dan brand.'" wide>
                <div class="flex flex-wrap items-center gap-4 rounded-xl bg-surface-subtle p-5 text-gray-700">
                    {{ svg('ceplok-o-home', 'size-6') }}
                    {{ svg('ceplok-o-search', 'size-6') }}
                    {{ svg('ceplok-o-bell-ring', 'size-6') }}
                    {{ svg('ceplok-o-user-settings', 'size-6') }}
                    <span class="h-6 w-px bg-border"></span>
                    {{ svg('brand-github', 'size-6') }}
                    {{ svg('brand-instagram', 'size-6') }}
                </div>
            </x-docs.overview-card>
        </div>
    </div>
</x-docs.layout>
