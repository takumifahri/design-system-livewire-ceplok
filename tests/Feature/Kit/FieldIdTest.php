<?php

namespace Tests\Feature\Kit;

use CeplokUi\DesignKit\Support\FieldId;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * ID kontrol form harus stabil antar-render: morph Livewire mengenali elemen
 * dari `id`, jadi ID yang berganti membuat input diganti utuh dan kehilangan
 * fokus saat pengguna mengetik.
 */
class FieldIdTest extends TestCase
{
    private function id(array $attributes, string $component = 'checkbox'): string
    {
        return FieldId::for(new ComponentAttributeBag($attributes), $component);
    }

    /** ID yang sama dihitung lagi di request baru, seperti setelah halaman dimuat ulang. */
    private function again(array $attributes, string $component = 'checkbox'): string
    {
        $this->app->forgetScopedInstances();

        return $this->id($attributes, $component);
    }

    public function test_id_eksplisit_dipakai_apa_adanya(): void
    {
        $this->assertSame('setuju', $this->id(['id' => 'setuju', 'wire:model' => 'setuju']));
    }

    public function test_id_diturunkan_dari_wire_model_x_model_atau_name(): void
    {
        $this->assertSame($this->id(['wire:model.live' => 'setuju']), $this->again(['wire:model.live' => 'setuju']));
        $this->assertSame($this->id(['name' => 'email']), $this->again(['name' => 'email', 'class' => 'mt-4']));
        $this->assertNotSame($this->id(['name' => 'email']), $this->id(['name' => 'telepon']));
        $this->assertNotSame($this->id(['name' => 'email'], 'input-field'), $this->id(['name' => 'email'], 'select'));
        $this->assertStringStartsWith('ceplok-checkbox-', $this->id(['x-model' => 'setuju']));
    }

    public function test_radio_sekelompok_dibedakan_oleh_value(): void
    {
        $this->assertNotSame(
            $this->id(['name' => 'jenis', 'value' => 'pria'], 'radio'),
            $this->id(['name' => 'jenis', 'value' => 'wanita'], 'radio'),
        );
    }

    public function test_field_yang_sama_dua_kali_tetap_mendapat_id_unik(): void
    {
        // Radio sekelompok tanpa `value` sama-sama bernilai "on".
        $first = [$this->id(['name' => 'setuju'], 'radio'), $this->id(['name' => 'setuju'], 'radio')];

        $this->assertNotSame($first[0], $first[1]);

        $this->app->forgetScopedInstances();

        $this->assertSame($first, [$this->id(['name' => 'setuju'], 'radio'), $this->id(['name' => 'setuju'], 'radio')]);
    }

    public function test_tanpa_nama_memakai_nomor_urut_yang_sama_di_setiap_request(): void
    {
        $first = [$this->id([]), $this->id([])];

        $this->assertNotSame($first[0], $first[1]);

        // Nomor urutnya disimpan di instance scoped, jadi request berikutnya mulai dari awal lagi.
        $this->app->forgetScopedInstances();

        $this->assertSame($first, [$this->id([]), $this->id([])]);
    }

    public function test_id_tetap_sama_setelah_livewire_merender_ulang(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public bool $setuju = false;

            public int $hitung = 0;

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::checkbox label="Setuju" wire:model="setuju" />
                        <x-ceplok::input-field label="Catatan" />
                        <x-ceplok::radio label="Ya" name="pilihan" />
                        <x-ceplok::radio label="Tidak" name="pilihan" />
                        <span>{{ $hitung }}</span>
                    </div>
                    BLADE;
            }
        });

        $ids = fn () => array_map(fn ($el) => $el->getAttribute('id'), iterator_to_array($this->parse($component->html())->querySelectorAll('input'), false));
        $before = $ids();

        $component->set('hitung', 5);

        $this->assertSame($before, $ids());
        $this->assertCount(4, array_unique($before));
    }
}
