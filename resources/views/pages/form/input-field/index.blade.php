<x-docs.layout title="Input Field Form">
    <x-docs.hero
        eyebrow="Form · Overview"
        title="Input Field Form"
        description="Kumpulan isian teks pada formulir: Input Field satu baris, Floating Label, dan Text Area untuk isian panjang. Seluruh warna, radius, dan jarak memakai token dari Foundations."
    />

    <div class="mx-auto max-w-5xl px-5 py-9 sm:px-8 lg:px-12 lg:py-12 xl:px-14">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-docs.overview-card route="form.input-field.input" name="Input Field" desc="Isian teks satu baris dengan label di atas field, lengkap dengan state dan warna per aplikasi.">
                <div class="rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::input-field label="Nama lengkap" placeholder="Masukkan nama lengkap" :icon="svg('ceplok-s-user', 'size-4')" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.input-field.floating-label" name="Floating Label" desc="Label yang naik ke garis atas begitu field difokus atau berisi — hemat ruang tanpa kehilangan keterangan.">
                <div class="rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::floating-label label="Nama lengkap" value="Budi Santoso" state="active" :icon="svg('ceplok-s-user', 'size-4')" />
                </div>
            </x-docs.overview-card>

            <x-docs.overview-card route="form.input-field.text-area" name="Text Area" desc="Isian teks banyak baris untuk keterangan panjang, dengan tinggi yang bisa diatur." wide>
                <div class="rounded-xl bg-surface-subtle p-5">
                    <x-ceplok::text-area platform="mobile" placeholder="Tulis keterangan tambahan…" />
                </div>
            </x-docs.overview-card>
        </div>
    </div>
</x-docs.layout>
