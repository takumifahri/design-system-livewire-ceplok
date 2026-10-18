<?php

namespace Tests\Feature\Kit;

use CeplokUi\DesignKit\Support\Bound;
use Dom\Element;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Badge, Alert, dan Toast yang diikat wire:model membaca nilai awalnya dari
 * properti Livewire, jadi HTML pertama dari server sudah benar — tidak ada
 * kedipan tampil-lalu-hilang sebelum Alpine berjalan.
 */
class BoundTest extends TestCase
{
    public function test_has_mengenali_wire_model_dan_x_model(): void
    {
        $this->assertTrue(Bound::has(new ComponentAttributeBag(['wire:model' => 'tampil'])));
        $this->assertTrue(Bound::has(new ComponentAttributeBag(['wire:model.live' => 'tampil'])));
        $this->assertTrue(Bound::has(new ComponentAttributeBag(['x-model' => 'tampil'])));
        $this->assertFalse(Bound::has(new ComponentAttributeBag(['wire:click' => 'simpan', 'class' => 'w-full'])));
    }

    public function test_bool_memakai_nilai_bawaan_di_luar_livewire(): void
    {
        $attributes = new ComponentAttributeBag(['wire:model' => 'tampil']);

        $this->assertTrue(Bound::bool($attributes, true));
        $this->assertFalse(Bound::bool($attributes, false));
    }

    /** @return array<string, array{string, string}> */
    public static function komponen(): array
    {
        return [
            'badge' => ['<x-ceplok::badge dismissible wire:model="tampil">Filter</x-ceplok::badge>', 'span'],
            'alert' => ['<x-ceplok::alert wire:model="tampil">Pesan</x-ceplok::alert>', 'div[role="alert"]'],
            'toast' => ['<x-ceplok::toast wire:model="tampil">Pesan</x-ceplok::toast>', 'div[role="status"]'],
        ];
    }

    #[DataProvider('komponen')]
    public function test_properti_false_dirender_tersembunyi_sejak_awal(string $markup, string $selector): void
    {
        $element = $this->renderInLivewire($markup, $selector, tampil: false);

        // Keadaan awal dibawa markup, bukan x-data, supaya x-data tidak berubah antar-render.
        $this->assertSame('ceplokDismissible', $element->getAttribute('x-data'));
        $this->assertSame('display: none;', $element->getAttribute('style'));
    }

    #[DataProvider('komponen')]
    public function test_properti_true_dirender_tampil(string $markup, string $selector): void
    {
        $element = $this->renderInLivewire($markup, $selector, tampil: true);

        $this->assertSame('ceplokDismissible', $element->getAttribute('x-data'));
        $this->assertFalse($element->hasAttribute('style'));
    }

    public function test_path_bersarang_dibaca_dari_array_properti(): void
    {
        $component = Livewire::test(new class extends Component
        {
            /** @var array<string, bool> */
            public array $filters = ['aktif' => true, 'jakarta' => false];

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::badge id="aktif" dismissible wire:model.live="filters.aktif">Aktif</x-ceplok::badge>
                        <x-ceplok::badge id="jakarta" dismissible wire:model.live="filters.jakarta">Jakarta</x-ceplok::badge>
                    </div>
                    BLADE;
            }
        });

        $dom = $this->parse($component->html());

        $this->assertFalse($this->find($dom, '#aktif')->hasAttribute('style'));
        $this->assertSame('display: none;', $this->find($dom, '#jakarta')->getAttribute('style'));
    }

    public function test_properti_yang_tidak_ada_jatuh_ke_bawaan_tampil(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public function render(): string
            {
                return '<div><x-ceplok::badge dismissible wire:model="tidakAda">Filter</x-ceplok::badge></div>';
            }
        });

        $this->assertFalse($this->find($this->parse($component->html()), 'span')->hasAttribute('style'));
    }

    public function test_open_eksplisit_menang_atas_nilai_properti(): void
    {
        $element = $this->renderInLivewire('<x-ceplok::badge dismissible wire:model="tampil" :open="true">Filter</x-ceplok::badge>', 'span', tampil: false);

        $this->assertFalse($element->hasAttribute('style'));
    }

    public function test_render_ulang_mengikuti_properti_terbaru(): void
    {
        $component = $this->host('<x-ceplok::toast wire:model="tampil">Tersimpan</x-ceplok::toast>', tampil: false);

        $component->set('tampil', true);

        $toast = $this->find($this->parse($component->html()), 'div[role="status"]');

        $this->assertFalse($toast->hasAttribute('style'));
        // x-data tetap sama antar-render: Alpine menginisialisasi ulang data yang ekspresinya berubah.
        $this->assertSame('ceplokDismissible', $toast->getAttribute('x-data'));
    }

    private function renderInLivewire(string $markup, string $selector, bool $tampil): Element
    {
        return $this->find($this->parse($this->host($markup, $tampil)->html()), $selector);
    }

    /** Komponen Livewire yang merender `$markup` di dalam akarnya, dengan properti `$tampil`. */
    private function host(string $markup, bool $tampil): Testable
    {
        return Livewire::test(new class extends Component
        {
            public bool $tampil = true;

            public string $markup = '';

            public function render(): string
            {
                return '<div>'.$this->markup.'</div>';
            }
        }, ['tampil' => $tampil, 'markup' => $markup]);
    }
}
