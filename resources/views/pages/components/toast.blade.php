@php
    $variants = [
        'success' => 'Sukses Membuat Data!',
        'danger' => 'Gagal Membuat Data!',
        'warning' => 'Sesi Anda akan berakhir dalam 5 menit.',
        'info' => 'Data sedang diproses.',
        'purple' => 'Fitur baru sudah tersedia.',
    ];

    $props = [
        ['variant', 'string', 'info', 'success · danger · warning · info · purple'],
        ['heading', 'string|null', 'null', 'Judul singkat; bila diisi, slot body tampil sebagai teks sekunder di bawahnya.'],
        ['icon', 'string|false|null', 'null', 'Nama ikon kustom. false menyembunyikan badge ikon, null memakai default variant.'],
        ['dismissible', 'bool', 'true', 'Menampilkan tombol tutup (×) di kanan atas.'],
        ['actions', 'slot', '—', 'Slot opsional untuk tombol aksi — biasanya full-width, lihat contoh "Dengan aksi".'],
    ];

    // Cuplikan kode untuk tombol salin di tiap section.
    $usage = [
        'variants' => <<<'CODE'
        <x-dk::toast variant="success">
            Sukses Membuat Data!
        </x-dk::toast>
        CODE,

        'actions' => <<<'CODE'
        <x-dk::toast variant="info" icon="refresh" heading="Update 2FA sudah tersedia!">
            Update Metode Password anda sekarang
            <x-slot:actions>
                <button type="button" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-primary-700">
                    Update 2FA
                </button>
            </x-slot:actions>
        </x-dk::toast>
        CODE,

        'position' => <<<'CODE'
        {{-- Toast selalu position: fixed terhadap viewport, bukan elemen inline --}}
        <div class="fixed bottom-4 left-4 z-50 space-y-3">
            <x-dk::toast variant="success">Sukses Membuat Data!</x-dk::toast>
        </div>
        CODE,
    ];
@endphp

<x-docs-layout section="components" title="Toast">
    <x-doc-hero eyebrow="Components" title="Toast" description="Notifikasi sekilas yang melayang di atas konten halaman — untuk konfirmasi aksi, pembaruan status, atau ajakan tindak lanjut. Berbeda dari Alert: kartu tetap netral, hanya badge ikon yang membawa warna variant." />

    <div class="mx-auto max-w-5xl space-y-10 px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Variants</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Gaya default: satu badge ikon, satu baris pesan, dan tombol tutup — cocok untuk konfirmasi singkat.</p>
            <div class="max-w-sm space-y-3">
                @foreach ($variants as $key => $message)
                    <x-dk::toast :variant="$key" :dismissible="false">
                        {{ $message }}
                    </x-dk::toast>
                @endforeach
            </div>
            <x-doc-usage :code="$usage['variants']" />
        </section>

        <section>
            <h2 class="mb-4 text-heading-3 font-black text-gray-900">Dengan heading & aksi</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Tambahkan <code class="text-xs font-bold text-gray-700">heading</code> untuk memisahkan judul dari isi, dan slot <code class="text-xs font-bold text-gray-700">actions</code> untuk satu tombol tindak lanjut — umumnya dibuat <code class="text-xs font-bold text-gray-700">w-full</code> agar memenuhi lebar kartu.</p>
            <div class="max-w-sm">
                <x-dk::toast variant="info" icon="refresh" heading="Update 2FA sudah tersedia!">
                    Update Metode Password anda sekarang
                    <x-slot:actions>
                        <button type="button" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-primary-700 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
                            Update 2FA
                        </button>
                    </x-slot:actions>
                </x-dk::toast>
            </div>
            <x-doc-usage :code="$usage['actions']" />
        </section>

        <section x-data="{
                width: 'desktop',
                position: 'bottom-left',
                withAction: false,
                visible: true,
                replay() {
                    this.visible = false;
                    this.$nextTick(() => { this.visible = true });
                },
            }">
            <h2 class="mb-1 text-heading-3 font-black text-gray-900">Playground</h2>
            <p class="mb-4 max-w-2xl text-body-sm text-gray-500">Toast selalu <code class="text-xs font-bold text-gray-700">position: fixed</code> terhadap viewport, bukan elemen inline seperti Alert — cuplikan ini mensimulasikannya di dalam bingkai halaman. Tutup lalu klik "Tampilkan Toast" untuk mengujinya lagi.</p>

            <div class="relative h-72 overflow-hidden rounded-2xl border border-border bg-surface-subtle p-4 sm:h-80 sm:p-6">
                <div class="relative mx-auto h-full transition-[max-width] duration-300 ease-out" :class="width === 'mobile' ? 'max-w-75' : 'max-w-full'">
                    {{-- Skeleton konten halaman, hanya latar --}}
                    <div class="space-y-3 opacity-60" aria-hidden="true">
                        <div class="h-2 w-2/5 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-5/6 rounded bg-gray-200"></div>
                        <div class="h-1.5 w-3/4 rounded bg-gray-200"></div>
                        <div class="mt-6 h-2 w-1/3 rounded bg-gray-300"></div>
                        <div class="h-1.5 w-full rounded bg-gray-200"></div>
                        <div class="h-1.5 w-4/6 rounded bg-gray-200"></div>
                    </div>

                    @foreach (['desktop', 'mobile'] as $width)
                        @foreach (['bottom-left', 'bottom-right', 'top-left', 'top-right'] as $position)
                            @foreach ([false, true] as $withAction)
                                <template x-if="visible && width === '{{ $width }}' && position === '{{ $position }}' && withAction === {{ $withAction ? 'true' : 'false' }}">
                                    <div class="absolute {{ str_contains($position, 'bottom') ? 'bottom-4' : 'top-4' }} {{ $width === 'mobile' ? 'inset-x-3' : ('w-full max-w-75 '.(str_contains($position, 'left') ? 'left-4' : 'right-4')) }}">
                                        @if ($withAction)
                                            <x-dk::toast variant="info" icon="refresh" heading="Update 2FA sudah tersedia!">
                                                Update Metode Password anda sekarang
                                                <x-slot:actions>
                                                    <button type="button" class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-bold text-white transition-colors hover:bg-primary-700">
                                                        Update 2FA
                                                    </button>
                                                </x-slot:actions>
                                            </x-dk::toast>
                                        @else
                                            <x-dk::toast variant="success">
                                                Sukses Membuat Data!
                                            </x-dk::toast>
                                        @endif
                                    </div>
                                </template>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-start gap-6">
                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Lebar</span>
                    <div class="mt-2 inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih lebar toast">
                        @foreach ([['mobile', 'Mobile'], ['desktop', 'Desktop']] as [$value, $label])
                            <button type="button" @click="width = '{{ $value }}'"
                                    class="rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="width === '{{ $value }}' ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="width === '{{ $value }}'">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Isi</span>
                    <div class="mt-2 inline-flex rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih isi toast">
                        @foreach ([['false', 'Pesan singkat'], ['true', 'Heading + aksi']] as [$value, $label])
                            <button type="button" @click="withAction = {{ $value }}"
                                    class="rounded-md px-3 py-1.5 text-sm font-bold transition-colors"
                                    :class="withAction === {{ $value }} ? 'bg-primary-50 text-primary-700' : 'text-gray-500 hover:text-gray-800'"
                                    :aria-pressed="withAction === {{ $value }}">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="ml-auto">
                    <span class="text-xs font-black tracking-wide text-gray-500 uppercase">Posisi</span>
                    <div class="mt-2 inline-grid grid-cols-2 gap-1 rounded-lg border border-border bg-surface p-1" role="group" aria-label="Pilih posisi toast">
                        @foreach ([['top-left', 'Atas kiri'], ['top-right', 'Atas kanan'], ['bottom-left', 'Bawah kiri'], ['bottom-right', 'Bawah kanan']] as [$value, $label])
                            <button type="button" @click="position = '{{ $value }}'"
                                    class="grid size-9 place-items-center rounded-md transition-colors"
                                    :class="position === '{{ $value }}' ? 'bg-primary-50 text-primary-700' : 'text-gray-400 hover:text-gray-700'"
                                    :aria-pressed="position === '{{ $value }}'"
                                    aria-label="{{ $label }}"
                                    title="{{ $label }}">
                                <span class="size-1.5 rounded-full bg-current"></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <x-dk::button variant="primary" @click="replay()">Tampilkan Toast</x-dk::button>
            </div>

            <x-doc-usage :code="$usage['position']" />
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
