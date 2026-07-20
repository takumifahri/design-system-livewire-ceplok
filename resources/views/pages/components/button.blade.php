@php
    $props = [
        ['variant', 'string', 'primary', 'primary · secondary · danger · ghost'],
        ['as', 'string', 'button', 'Tag HTML yang dirender, mis. a untuk link'],
    ];
@endphp

<x-docs-layout section="components" title="Button">
    <x-doc-hero eyebrow="Components" title="Button" description="Memicu aksi utama pada sebuah halaman atau form. Empat variant untuk tingkat penekanan yang berbeda." />

    <div class="mx-auto max-w-5xl space-y-10 px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Variants</h2>
            <x-doc-example>
                <x-dk::button variant="primary">Simpan perubahan</x-dk::button>
                <x-dk::button variant="secondary">Batal</x-dk::button>
                <x-dk::button variant="danger">Hapus</x-dk::button>
                <x-dk::button variant="ghost">Lewati</x-dk::button>
                <x-slot:code>&lt;x-dk::button variant=&quot;<span class="text-primary-300">primary</span>&quot;&gt;Simpan perubahan&lt;/x-dk::button&gt;
&lt;x-dk::button variant=&quot;secondary&quot;&gt;Batal&lt;/x-dk::button&gt;
&lt;x-dk::button variant=&quot;danger&quot;&gt;Hapus&lt;/x-dk::button&gt;
&lt;x-dk::button variant=&quot;ghost&quot;&gt;Lewati&lt;/x-dk::button&gt;</x-slot:code>
            </x-doc-example>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">States</h2>
            <x-doc-example>
                <x-dk::button variant="primary">Normal</x-dk::button>
                <x-dk::button variant="primary" disabled>Nonaktif</x-dk::button>
                <x-slot:code>&lt;x-dk::button variant=&quot;primary&quot; <span class="text-primary-300">disabled</span>&gt;Nonaktif&lt;/x-dk::button&gt;</x-slot:code>
            </x-doc-example>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Sebagai link</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Gunakan prop <code class="text-xs font-bold text-gray-700">as="a"</code> agar dirender sebagai anchor tanpa kehilangan gaya tombol.</p>
            <x-doc-example>
                <x-dk::button :as="'a'" href="{{ route('foundations.colors') }}" variant="secondary">Buka Foundations</x-dk::button>
                <x-slot:code>&lt;x-dk::button :as=&quot;<span class="text-primary-300">'a'</span>&quot; href=&quot;/foundations/colors&quot; variant=&quot;secondary&quot;&gt;
    Buka Foundations
&lt;/x-dk::button&gt;</x-slot:code>
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
