@php
    $props = [
        ['variant', 'string', 'gray', 'gray · brand · danger · warning · success'],
    ];
@endphp

<x-docs-layout section="components" title="Badge">
    <x-doc-hero eyebrow="Components" title="Badge" description="Label status ringkas untuk menandai kondisi sebuah entitas. Lima variant warna dengan makna semantik." />

    <div class="mx-auto max-w-5xl space-y-10 px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Variants</h2>
            <x-doc-example>
                <x-dk::badge variant="gray">Draft</x-dk::badge>
                <x-dk::badge variant="brand">Baru</x-dk::badge>
                <x-dk::badge variant="success">Aktif</x-dk::badge>
                <x-dk::badge variant="warning">Menunggu</x-dk::badge>
                <x-dk::badge variant="danger">Ditolak</x-dk::badge>
                <x-slot:code>&lt;x-dk::badge variant=&quot;<span class="text-green-300">success</span>&quot;&gt;Aktif&lt;/x-dk::badge&gt;
&lt;x-dk::badge variant=&quot;warning&quot;&gt;Menunggu&lt;/x-dk::badge&gt;
&lt;x-dk::badge variant=&quot;danger&quot;&gt;Ditolak&lt;/x-dk::badge&gt;</x-slot:code>
            </x-doc-example>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Dalam konteks</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Badge biasanya menyertai judul, baris tabel, atau kartu untuk menandai status tanpa memakan banyak ruang.</p>
            <x-doc-example>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-gray-900">Pengajuan izin <x-dk::badge variant="warning">Menunggu</x-dk::badge></span>
                <span class="inline-flex items-center gap-2 text-sm font-bold text-gray-900">Verifikasi data <x-dk::badge variant="success">Selesai</x-dk::badge></span>
            </x-doc-example>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Properties</h2>
            <div class="ds-card overflow-hidden">
                <div class="ds-scroll-x overflow-x-auto">
                    <table class="w-full min-w-[36rem] text-left text-sm">
                        <thead class="border-b border-border bg-surface-subtle text-xs font-black tracking-wide text-gray-500 uppercase">
                            <tr>
                                <th class="px-5 py-3">Prop</th>
                                <th class="px-5 py-3">Tipe</th>
                                <th class="px-5 py-3">Default</th>
                                <th class="px-5 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($props as [$name, $type, $default, $desc])
                                <tr>
                                    <td class="px-5 py-3"><code class="text-xs font-bold text-primary-700">{{ $name }}</code></td>
                                    <td class="px-5 py-3 text-gray-500">{{ $type }}</td>
                                    <td class="px-5 py-3"><code class="text-xs text-gray-500">{{ $default }}</code></td>
                                    <td class="px-5 py-3 text-gray-600">{{ $desc }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-docs-layout>
