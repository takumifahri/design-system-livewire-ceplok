<x-docs-layout section="home" title="Home">
    <section class="relative overflow-hidden border-b border-border bg-gradient-to-b from-primary-50 to-white px-5 py-16 sm:px-8 lg:px-12 lg:py-24 xl:px-14">
        <div class="mx-auto max-w-5xl">
            <span class="inline-flex items-center gap-2 rounded-full border border-primary-200 bg-white px-3 py-1.5 text-xs font-bold text-primary-700">
                <span class="size-2 rounded-full bg-green-500"></span>
                v1.0.0 · Ready to use
            </span>
            <h1 class="mt-5 max-w-3xl text-[clamp(2.25rem,6vw,3.75rem)] leading-[1.05] font-black tracking-tight text-gray-900">
                State Security Service Design System
            </h1>
            <p class="mt-5 max-w-2xl text-body-lg text-gray-600">
                Satu sumber visual dan komponen yang konsisten untuk membangun layanan digital yang jelas, inklusif, dan mudah dikenali.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <x-dk::button :as="'a'" href="{{ route('foundations.colors') }}" variant="primary">Lihat Foundations</x-dk::button>
                <x-dk::button :as="'a'" href="{{ route('components.index') }}" variant="secondary">Jelajahi Components</x-dk::button>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-5xl px-5 py-12 sm:px-8 lg:px-12 lg:py-16 xl:px-14">
        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ([
                ['Foundations', 'Color token & typography — dasar visual dari seluruh produk.', 'foundations.colors', 'M12 3.5c3.8 3.2 6.5 6 6.5 9.5a6.5 6.5 0 1 1-13 0c0-3.5 2.7-6.3 6.5-9.5Z'],
                ['Components', 'Blade & Alpine components siap pakai dari tpl/design-kit.', 'components.index', 'M4 4h6v6H4V4Zm10 0h6v6h-6V4ZM4 14h6v6H4v-6Zm10 0h6v6h-6v-6Z'],
            ] as [$name, $desc, $route, $icon])
                <a href="{{ route($route) }}" class="ds-card group flex flex-col p-6 transition-shadow hover:shadow-md">
                    <span class="grid size-11 place-items-center rounded-xl bg-primary-50 text-primary-700">
                        <svg class="size-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </span>
                    <h2 class="mt-4 text-heading-4 font-black text-gray-900">{{ $name }}</h2>
                    <p class="mt-1.5 text-body-sm text-gray-500">{{ $desc }}</p>
                    <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary-700">
                        Buka
                        <svg class="size-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                    </span>
                </a>
            @endforeach
        </div>

        <div class="mt-12 overflow-hidden rounded-2xl bg-gray-900 shadow-soft">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-3">
                <span class="text-xs font-bold text-gray-300">Mulai cepat</span>
                <span class="text-[10px] font-bold tracking-wider text-gray-500 uppercase">Blade + tpl/design-kit</span>
            </div>
            <pre class="overflow-x-auto p-5 text-sm leading-7 text-gray-300"><code>&lt;x-dk::button variant=&quot;<span class="text-primary-300">primary</span>&quot;&gt;Simpan&lt;/x-dk::button&gt;
&lt;x-dk::badge variant=&quot;<span class="text-green-300">success</span>&quot;&gt;Aktif&lt;/x-dk::badge&gt;</code></pre>
        </div>
    </div>
</x-docs-layout>
