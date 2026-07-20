<x-docs-layout section="components" title="Components">
    <x-doc-hero eyebrow="Components · Overview" title="Components" description="Komponen Blade & Alpine siap pakai dari package tpl/design-kit. Dipakai lewat sintaks &lt;x-dk::nama&gt; setelah package terpasang.">
        <span class="mt-5 inline-block w-fit rounded-full border border-primary-200 bg-primary-50 px-3 py-1.5 text-xs font-bold text-primary-700">2 komponen tersedia</span>
    </x-doc-hero>

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <div class="grid gap-5 sm:grid-cols-2">
            <a href="{{ route('components.button') }}" class="ds-card group flex flex-col p-6 transition-shadow hover:shadow-md">
                <div class="flex flex-wrap items-center gap-2 rounded-xl bg-surface-subtle p-5">
                    <x-dk::button variant="primary">Primary</x-dk::button>
                    <x-dk::button variant="secondary">Secondary</x-dk::button>
                </div>
                <h2 class="mt-4 text-heading-4 font-black text-gray-900">Button</h2>
                <p class="mt-1.5 text-body-sm text-gray-500">Aksi utama dengan empat variant: primary, secondary, danger, ghost.</p>
                <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary-700">
                    Lihat detail
                    <svg class="size-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </span>
            </a>

            <a href="{{ route('components.badge') }}" class="ds-card group flex flex-col p-6 transition-shadow hover:shadow-md">
                <div class="flex flex-wrap items-center gap-2 rounded-xl bg-surface-subtle p-5">
                    <x-dk::badge variant="success">Aktif</x-dk::badge>
                    <x-dk::badge variant="warning">Menunggu</x-dk::badge>
                    <x-dk::badge variant="danger">Ditolak</x-dk::badge>
                </div>
                <h2 class="mt-4 text-heading-4 font-black text-gray-900">Badge</h2>
                <p class="mt-1.5 text-body-sm text-gray-500">Label status ringkas dengan lima variant warna semantik.</p>
                <span class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-primary-700">
                    Lihat detail
                    <svg class="size-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </span>
            </a>
        </div>
    </div>
</x-docs-layout>
