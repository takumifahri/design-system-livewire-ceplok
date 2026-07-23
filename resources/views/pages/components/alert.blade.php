@php
    // Warna tombol aksi mengikuti "ink" dari variant Alert yang sama —
    // didefinisikan di sini karena actions adalah slot bebas, bukan sesuatu
    // yang di-generate oleh komponen Alert itu sendiri.
    $variants = [
        'success' => ['label' => 'Success', 'button' => 'bg-green-800 hover:bg-green-900 focus-visible:outline-green-800'],
        'danger' => ['label' => 'Danger', 'button' => 'bg-red-800 hover:bg-red-900 focus-visible:outline-red-800'],
        'warning' => ['label' => 'Warning', 'button' => 'bg-yellow-800 hover:bg-yellow-900 focus-visible:outline-yellow-800'],
        'info' => ['label' => 'Info', 'button' => 'bg-primary-800 hover:bg-primary-900 focus-visible:outline-primary-800'],
        'purple' => ['label' => 'Purple', 'button' => 'bg-purple-800 hover:bg-purple-900 focus-visible:outline-purple-800'],
    ];

    $props = [
        ['variant', 'string', 'info', 'success · danger · warning · info · purple'],
        ['surface', 'string', 'soft', 'soft (latar bertinta) · outline (latar putih + garis)'],
        ['heading', 'string|null', 'null', 'Judul singkat di baris pertama.'],
        ['icon', 'string|false|null', 'null', 'Nama ikon kustom. false menyembunyikan ikon kiri, null memakai default variant.'],
        ['dismissible', 'bool', 'true', 'Menampilkan tombol tutup (X) di kanan atas.'],
        ['actions', 'slot', '—', 'Slot opsional untuk satu atau beberapa tombol.'],
    ];

    // Cuplikan kode untuk tombol salin di tiap section.
    $usage = [
        'variants' => <<<'CODE'
        <x-dk::alert variant="success" heading="Ini adalah Alert">
            Ini merupakan Design system Stasi berupa component alert.
            <x-slot:actions>
                <button type="button" class="rounded-lg bg-green-800 px-4 py-2.5 text-sm font-bold text-white hover:bg-green-900">
                    Button text
                </button>
            </x-slot:actions>
        </x-dk::alert>
        CODE,

        'surface' => <<<'CODE'
        {{-- soft (default): latar bertinta, tanpa garis --}}
        <x-dk::alert variant="info" heading="Ini adalah Alert">...</x-dk::alert>

        {{-- outline: latar putih, garis warna variant, shadow-soft --}}
        <x-dk::alert variant="info" surface="outline" heading="Ini adalah Alert">...</x-dk::alert>
        CODE,

        'options' => <<<'CODE'
        {{-- Tanpa tombol: cukup hilangkan slot actions --}}
        <x-dk::alert variant="warning" heading="Ini adalah Alert">
            Ini merupakan Design system Stasi berupa component alert.
        </x-dk::alert>

        {{-- Tanpa tombol tutup --}}
        <x-dk::alert variant="warning" heading="Ini adalah Alert" :dismissible="false">
            Ini merupakan Design system Stasi berupa component alert.
        </x-dk::alert>
        CODE,
    ];

    $bodyText = 'Ini merupakan Design system Stasi berupa component alert. Ini merupakan Design system Stasi berupa component alert.';
@endphp

<x-docs-layout section="components" title="Alert">
    <x-doc-hero eyebrow="Components" title="Alert" description="Menyampaikan pesan status — sukses, error, peringatan, atau informasi — tepat di dalam alur halaman. Lima variant warna, dua gaya permukaan, dan elemen yang sepenuhnya opsional." />

    <div class="mx-auto max-w-5xl space-y-10 px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Variants</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Lima variant warna dengan makna semantik yang sama dengan Badge dan token warna lain di design system ini.</p>
            <div class="space-y-4">
                @foreach ($variants as $key => $variant)
                    <x-dk::alert :variant="$key" heading="Ini adalah Alert">
                        {{ $bodyText }}
                        <x-slot:actions>
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 {{ $variant['button'] }}">
                                Button text
                                <x-dk::icon name="messages" class="size-4" />
                            </button>
                        </x-slot:actions>
                    </x-dk::alert>
                @endforeach
            </div>
            <x-doc-usage :code="$usage['variants']" />
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Surface</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Gaya <code class="text-xs font-bold text-gray-700">soft</code> (default) memakai latar bertinta untuk penekanan lebih kuat. Gaya <code class="text-xs font-bold text-gray-700">outline</code> memakai latar putih dengan garis tipis — cocok untuk halaman yang sudah padat warna.</p>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">soft</p>
                    <x-dk::alert variant="info" heading="Ini adalah Alert">
                        {{ $bodyText }}
                        <x-slot:actions>
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 {{ $variants['info']['button'] }}">
                                Button text
                                <x-dk::icon name="messages" class="size-4" />
                            </button>
                        </x-slot:actions>
                    </x-dk::alert>
                </div>
                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">outline</p>
                    <x-dk::alert variant="info" surface="outline" heading="Ini adalah Alert">
                        {{ $bodyText }}
                        <x-slot:actions>
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-bold text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 {{ $variants['info']['button'] }}">
                                Button text
                                <x-dk::icon name="messages" class="size-4" />
                            </button>
                        </x-slot:actions>
                    </x-dk::alert>
                </div>
            </div>
            <x-doc-usage :code="$usage['surface']" />
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Elemen opsional</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Heading, tombol aksi, dan tombol tutup semuanya opsional — cukup hilangkan bagian yang tidak dipakai.</p>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">Tanpa tombol</p>
                    <x-dk::alert variant="warning" heading="Ini adalah Alert">
                        {{ $bodyText }}
                    </x-dk::alert>
                </div>
                <div>
                    <p class="mb-2 text-sm font-black text-gray-900">Tanpa tombol tutup</p>
                    <x-dk::alert variant="warning" heading="Ini adalah Alert" :dismissible="false">
                        {{ $bodyText }}
                    </x-dk::alert>
                </div>
            </div>
            <x-doc-usage :code="$usage['options']" />
        </section>

        <section x-data="{
                width: 'desktop',
                withIcon: true,
                withClose: true,
                visible: true,
                replay() {
                    this.visible = false;
                    this.$nextTick(() => { this.visible = true });
                },
            }">
            <h2 class="mb-1 text-heading-3 font-black text-gray-900">Playground</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Coba langsung kombinasi lebar, ikon kiri, dan tombol tutup — dirender lewat komponen <code class="text-xs font-bold text-gray-700">&lt;x-dk::alert&gt;</code> yang sesungguhnya, bukan tiruan. Tutup lalu klik "Tampilkan Alert" untuk mengujinya lagi.</p>

            <div class="overflow-hidden rounded-2xl border border-border bg-surface-subtle p-4 sm:p-6">
                <div class="mx-auto transition-[max-width] duration-300 ease-out" :class="width === 'mobile' ? 'max-w-75' : 'max-w-full'">
                    @foreach (['desktop', 'mobile'] as $width)
                        @foreach ([true, false] as $withIcon)
                            @foreach ([true, false] as $withClose)
                                <template x-if="visible && width === '{{ $width }}' && withIcon === {{ $withIcon ? 'true' : 'false' }} && withClose === {{ $withClose ? 'true' : 'false' }}">
                                    <x-dk::alert
                                        variant="success"
                                        heading="Ini adalah Alert"
                                        :icon="$withIcon ? null : false"
                                        :dismissible="$withClose"
                                    >
                                        Ini merupakan Design system Komdigi berupa component alert.
                                        <x-slot:actions>
                                            <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-green-800 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-green-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-800">
                                                Button text
                                                <x-dk::icon name="messages" class="size-4" />
                                            </button>
                                        </x-slot:actions>
                                    </x-dk::alert>
                                </template>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Lebar</span>
                    <div class="mt-2 inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih lebar alert">
                        @foreach ([['mobile', 'Mobile'], ['desktop', 'Desktop']] as [$value, $label])
                            <button type="button" @click="width = '{{ $value }}'"
                                    class="rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="width === '{{ $value }}' ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="width === '{{ $value }}'">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Icon kiri</span>
                    <div class="mt-2 inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Tampilkan icon kiri">
                        @foreach ([['true', 'Ada'], ['false', 'Tanpa']] as [$value, $label])
                            <button type="button" @click="withIcon = {{ $value }}"
                                    class="rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="withIcon === {{ $value }} ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="withIcon === {{ $value }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Tombol tutup</span>
                    <div class="mt-2 inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Tampilkan tombol tutup">
                        @foreach ([['true', 'Ada'], ['false', 'Tanpa']] as [$value, $label])
                            <button type="button" @click="withClose = {{ $value }}"
                                    class="rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="withClose === {{ $value }} ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="withClose === {{ $value }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <x-dk::button variant="primary" @click="replay()">Tampilkan Alert</x-dk::button>
            </div>
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Properties</h2>
            <div class="ds-card overflow-hidden">
                <div class="ds-scroll-x overflow-x-auto">
                    <table class="w-full min-w-160 text-left text-sm">
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
