@php
    $title = 'Komdigi Card Desktop';
    $desc = 'Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.';

    // Playground: 2 pilihan gambar × 4 pilihan aksi = 8 kombinasi.
    $actionChoices = [
        'two' => 'Dua tombol',
        'one' => 'Satu tombol',
        'link' => 'Tautan',
        'none' => 'Tanpa aksi',
    ];

    $props = [
        ['image', 'string|null', 'null', 'URL gambar. Dikosongkan untuk varian tanpa gambar.'],
        ['imageAlt', 'string', "''", 'Teks alternatif gambar.'],
        ['title', 'string|null', 'null', 'Judul kartu.'],
        ['description', 'string|null', 'null', 'Deskripsi singkat.'],
        ['href', 'string|null', 'null', 'Mengaktifkan varian tautan bila diisi.'],
        ['linkLabel', 'string|null', 'Selengkapnya', 'Teks tautan, dipakai bersama href.'],
        ['actions', 'slot', '—', 'Slot untuk satu atau beberapa tombol.'],
    ];
@endphp

<x-docs-layout section="components" title="Card">
    <x-doc-hero eyebrow="Components" title="Card" description="Wadah ringkas untuk menampilkan satu unit informasi: gambar, judul, deskripsi, dan aksi. Setiap bagian bersifat opsional, sehingga seluruh varian pada desain terbentuk dari komponen yang sama." />

    <div class="mx-auto max-w-5xl space-y-10 px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <section x-data="{ view: 'desktop', hasImage: true, action: 'two' }">
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Playground</h2>

            <div class="grid place-items-center rounded-2xl border border-border bg-surface-subtle p-6 sm:p-10">
                <div class="w-full transition-[max-width] duration-300 ease-out"
                     :class="view === 'mobile' ? 'max-w-[238px]' : 'max-w-[384px]'">
                    @foreach ([true, false] as $withImage)
                        @foreach (array_keys($actionChoices) as $action)
                            <div x-show="hasImage === {{ $withImage ? 'true' : 'false' }} && action === '{{ $action }}'" x-cloak>
                                <x-dk::card
                                    :image="$withImage ? asset('images/card-sample.svg') : null"
                                    image-alt="Suasana kerja tim"
                                    :title="$title"
                                    :description="$desc"
                                    :href="$action === 'link' ? '#' : null"
                                    :link-label="$action === 'link' ? 'See our guideline' : null"
                                >
                                    @if ($action === 'one' || $action === 'two')
                                        <x-slot:actions>
                                            @if ($action === 'two')
                                                <x-dk::button variant="secondary">Button text <x-dk::icon name="messages" class="size-4" /></x-dk::button>
                                            @endif
                                            <x-dk::button variant="primary">Button text <x-dk::icon name="messages" class="size-4" /></x-dk::button>
                                        </x-slot:actions>
                                    @endif
                                </x-dk::card>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- Kontrol --}}
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Lebar</span>
                    <div class="mt-2 inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih lebar kartu">
                        @foreach ([['mobile', 'Mobile'], ['desktop', 'Desktop']] as [$value, $label])
                            <button type="button" @click="view = '{{ $value }}'"
                                    class="rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="view === '{{ $value }}' ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="view === '{{ $value }}'">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Gambar</span>
                    <div class="mt-2 inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Tampilkan gambar">
                        @foreach ([['true', 'Ada'], ['false', 'Tanpa']] as [$value, $label])
                            <button type="button" @click="hasImage = {{ $value }}"
                                    class="rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="hasImage === {{ $value }} ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="hasImage === {{ $value }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Aksi</span>
                    <div class="mt-2 inline-flex flex-wrap rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih jenis aksi">
                        @foreach ($actionChoices as $value => $label)
                            <button type="button" @click="action = '{{ $value }}'"
                                    class="rounded-md px-2.5 py-1.5 text-sm font-bold transition-colors"
                                    :class="action === '{{ $value }}' ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="action === '{{ $value }}'">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <p class="mt-4 text-body-sm text-gray-500">
                Ukuran judul dan jarak dalam kartu memakai <em>container query</em>, sehingga menyesuaikan lebar kartu itu sendiri — bukan lebar layar. Kartu yang sama otomatis tampil ringkas di kolom sempit.
            </p>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Varian</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">Default</p>
                    <x-dk::card :image="asset('images/card-sample.svg')" image-alt="" :title="$title" :description="$desc">
                        <x-slot:actions>
                            <x-dk::button variant="primary">Button text <x-dk::icon name="messages" class="size-4" /></x-dk::button>
                        </x-slot:actions>
                    </x-dk::card>
                </div>

                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">Tanpa gambar</p>
                    <x-dk::card :title="$title" :description="$desc">
                        <x-slot:actions>
                            <x-dk::button variant="primary">Button text <x-dk::icon name="messages" class="size-4" /></x-dk::button>
                        </x-slot:actions>
                    </x-dk::card>
                </div>

                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">Tanpa tombol</p>
                    <x-dk::card :image="asset('images/card-sample.svg')" image-alt="" :title="$title" :description="$desc" />
                </div>

                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">Dengan tautan</p>
                    <x-dk::card :image="asset('images/card-sample.svg')" image-alt="" :title="$title" :description="$desc" href="#" link-label="See our guideline" />
                </div>
            </div>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Penggunaan</h2>
            <div class="ds-card overflow-hidden">
                <pre class="ds-scroll-x overflow-x-auto bg-gray-900 p-5 text-xs leading-6 text-gray-300"><code>{{-- Default: gambar + judul + deskripsi + tombol --}}
&lt;x-dk::card
    :image="asset('images/artikel.jpg')"
    image-alt="Suasana kerja tim"
    title="Komdigi Card Desktop"
    description="Here are the biggest enterprise technology acquisitions of 2021."
&gt;
    &lt;x-slot:<span class="text-primary-300">actions</span>&gt;
        &lt;x-dk::button variant="secondary"&gt;Button text&lt;/x-dk::button&gt;
        &lt;x-dk::button variant="primary"&gt;Button text&lt;/x-dk::button&gt;
    &lt;/x-slot:actions&gt;
&lt;/x-dk::card&gt;

{{-- Tanpa gambar: cukup hilangkan :image --}}
&lt;x-dk::card title="..." description="..." /&gt;

{{-- Dengan tautan: isi href + link-label --}}
&lt;x-dk::card
    title="..."
    description="..."
    <span class="text-primary-300">href</span>="/panduan"
    <span class="text-primary-300">link-label</span>="See our guideline"
/&gt;</code></pre>
            </div>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Properties</h2>
            <div class="ds-card overflow-hidden">
                <div class="ds-scroll-x overflow-x-auto">
                    <table class="w-full min-w-[40rem] text-left text-sm">
                        <thead class="border-b border-border bg-surface-subtle text-xs font-black tracking-wide text-gray-500 uppercase">
                            <tr>
                                <th class="px-5 py-3">Prop</th>
                                <th class="px-5 py-3">Tipe</th>
                                <th class="px-5 py-3">Default</th>
                                <th class="px-5 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @foreach ($props as [$name, $type, $default, $desc2])
                                <tr>
                                    <td class="px-5 py-3"><code class="text-xs font-bold text-primary-700">{{ $name }}</code></td>
                                    <td class="px-5 py-3 text-gray-500">{{ $type }}</td>
                                    <td class="px-5 py-3"><code class="text-xs text-gray-500">{{ $default }}</code></td>
                                    <td class="px-5 py-3 text-gray-600">{{ $desc2 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-docs-layout>
