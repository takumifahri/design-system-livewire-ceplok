<?php

namespace Tests\Feature\Kit\Components;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** Checkbox, Radio, dan Toggle: pola yang sama di ketiganya. */
class ChoiceControlsTest extends TestCase
{
    /** @return array<string, array{string, string}> */
    public static function kontrol(): array
    {
        return [
            'checkbox' => ['checkbox', 'checkbox'],
            'radio' => ['radio', 'radio'],
            'toggle' => ['toggle', 'checkbox'],
        ];
    }

    /**
     * Kontrol yang sama, hanya nama komponennya. PHPUnit 12 memperingatkan
     * bila provider mengirim lebih banyak argumen daripada parameter tesnya.
     *
     * @return array<string, array{string}>
     */
    public static function nama(): array
    {
        return array_map(fn (array $kontrol) => [$kontrol[0]], self::kontrol());
    }

    #[DataProvider('kontrol')]
    public function test_label_dan_caption_ditautkan_ke_input(string $name, string $type): void
    {
        $dom = $this->dom("<x-ceplok::{$name} label=\"Saya setuju\" helper-text=\"Wajib dicentang.\" />");
        $input = $this->find($dom, "input[type=\"{$type}\"]");
        $id = $input->getAttribute('id');

        $this->assertNotSame('', $id);
        $this->assertSame($id, $this->find($dom, 'label')->getAttribute('for'));
        $this->assertSame("{$id}-helper", $input->getAttribute('aria-describedby'));
        $this->assertSame('Wajib dicentang.', trim($this->find($dom, "#{$id}-helper")->textContent));
        $this->assertHasClasses($this->find($dom, 'body > div'), 'flex items-start gap-2');
    }

    #[DataProvider('nama')]
    public function test_tanpa_teks_pembungkusnya_span_inline(string $name): void
    {
        $root = $this->find($this->dom("<x-ceplok::{$name} aria-label=\"Setuju\" />"), 'body > span');

        $this->assertHasClasses($root, 'inline-flex');
        $this->assertNull($root->querySelector('label'));
    }

    #[DataProvider('kontrol')]
    public function test_atribut_ke_input_kelas_dan_wire_key_ke_pembungkus(string $name, string $type): void
    {
        $dom = $this->dom("<x-ceplok::{$name} label=\"Setuju\" name=\"setuju\" value=\"ya\" required class=\"mt-4\" wire:key=\"baris-1\" data-uji=\"1\" />");
        $root = $this->find($dom, 'body > div');
        $input = $this->find($dom, 'input');

        $this->assertHasClasses($root, 'mt-4');
        $this->assertSame('baris-1', $root->getAttribute('wire:key'));
        $this->assertFalse($input->hasAttribute('wire:key'));
        $this->assertSame('setuju', $input->getAttribute('name'));
        $this->assertSame('ya', $input->getAttribute('value'));
        $this->assertSame('1', $input->getAttribute('data-uji'));
        $this->assertTrue($input->hasAttribute('required'));
        $this->assertSame($type, $input->getAttribute('type'));
        $this->assertLacksClasses($input, 'mt-4');
    }

    #[DataProvider('nama')]
    public function test_inactive_dan_disabled_menonaktifkan_kontrol(string $name): void
    {
        foreach (['state="inactive"', 'disabled'] as $attribute) {
            $dom = $this->dom("<x-ceplok::{$name} label=\"Setuju\" {$attribute} />");

            $this->assertTrue($this->find($dom, 'input')->hasAttribute('disabled'));
            $this->assertHasClasses($this->find($dom, 'label'), 'cursor-not-allowed');
        }
    }

    #[DataProvider('nama')]
    public function test_dark_mode_platform_dan_aplikasi(string $name): void
    {
        $dom = $this->dom("<x-ceplok::{$name} label=\"Setuju\" dark-mode platform=\"mobile\" application=\"purple\" />");

        $this->assertSame('dark', $this->find($dom, 'body > div')->getAttribute('data-theme'));
        $this->assertHasClasses($this->find($dom, 'label'), 'text-xs');
        $this->assertStringContainsString('purple-500', $dom->body->innerHTML);
    }

    #[DataProvider('nama')]
    public function test_prop_yang_tidak_sah_ditolak(string $name): void
    {
        $this->assertPropError("<x-ceplok::{$name} platform=\"tablet\" />", "Prop `platform` pada <x-ceplok::{$name}> harus salah satu dari 'default', 'mobile'");
        $this->assertPropError("<x-ceplok::{$name} state=\"failed\" />", "Prop `state` pada <x-ceplok::{$name}>");
        $this->assertPropError("<x-ceplok::{$name} application=\"green\" />", "Prop `application` pada <x-ceplok::{$name}>");
    }

    public function test_toggle_berperan_switch(): void
    {
        $this->assertSame('switch', $this->find($this->dom('<x-ceplok::toggle label="Notifikasi" />'), 'input')->getAttribute('role'));
    }

    public function test_wire_model_mencentang_sesuai_properti_sejak_html_pertama(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public bool $setuju = true;

            public bool $notifikasi = false;

            /** @var list<string> */
            public array $minat = ['olahraga'];

            public string $jenis = 'wanita';

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::checkbox id="setuju" label="Setuju" wire:model="setuju" />
                        <x-ceplok::toggle id="notifikasi" label="Notifikasi" wire:model="notifikasi" />
                        <x-ceplok::checkbox id="olahraga" label="Olahraga" wire:model="minat" value="olahraga" />
                        <x-ceplok::checkbox id="musik" label="Musik" wire:model="minat" value="musik" />
                        <x-ceplok::radio id="pria" label="Pria" wire:model="jenis" value="pria" />
                        <x-ceplok::radio id="wanita" label="Wanita" wire:model="jenis" value="wanita" />
                    </div>
                    BLADE;
            }
        });

        $dom = $this->parse($component->html());
        $checked = fn (string $id) => $this->find($dom, "#{$id}")->hasAttribute('checked');

        $this->assertTrue($checked('setuju'));
        $this->assertFalse($checked('notifikasi'));
        $this->assertTrue($checked('olahraga'));
        $this->assertFalse($checked('musik'));
        $this->assertFalse($checked('pria'));
        $this->assertTrue($checked('wanita'));
    }

    public function test_checked_eksplisit_menang_atas_properti(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public bool $setuju = false;

            public function render(): string
            {
                return '<div><x-ceplok::checkbox label="Setuju" wire:model="setuju" checked /></div>';
            }
        });

        $this->assertTrue($this->find($this->parse($component->html()), 'input')->hasAttribute('checked'));
    }
}
