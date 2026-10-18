<?php

namespace Tests\Feature\Kit\Components;

use Livewire\Component;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** Input Field, Floating Label, dan Text Area. */
class TextFieldsTest extends TestCase
{
    public function test_input_field_label_caption_dan_atribut_ke_input(): void
    {
        $dom = $this->dom('<x-ceplok::input-field label="Nama" helper-text="Sesuai KTP" name="nama" placeholder="Nama lengkap" class="max-w-md" />');
        $input = $this->find($dom, 'input');
        $id = $input->getAttribute('id');

        $this->assertSame($id, $this->find($dom, 'label')->getAttribute('for'));
        $this->assertSame("{$id}-helper", $input->getAttribute('aria-describedby'));
        $this->assertSame('nama', $input->getAttribute('name'));
        $this->assertSame('Nama lengkap', $input->getAttribute('placeholder'));
        $this->assertHasClasses($this->find($dom, 'body > div'), 'w-full max-w-md');
        $this->assertHasClasses($this->find($dom, 'input')->parentElement, 'h-13 border-field-border bg-field focus-within:border-primary-500');
        $this->assertFalse($this->find($dom, 'body > div')->hasAttribute('x-data'));
    }

    /** @return array<string, array{string, string, ?string, bool}> */
    public static function state(): array
    {
        return [
            'typing mengunci garis aksen' => ['typing', 'border-primary-500', null, false],
            'inactive menonaktifkan input' => ['inactive', 'border-input-field-border-inactive', null, true],
            'failed memasang aria-invalid' => ['failed', 'border-feedback-error bg-input-field-surface-failed', 'true', false],
        ];
    }

    #[DataProvider('state')]
    public function test_state_input_field(string $state, string $box, ?string $invalid, bool $disabled): void
    {
        $dom = $this->dom("<x-ceplok::input-field label=\"Nama\" state=\"{$state}\" />");
        $input = $this->find($dom, 'input');

        $this->assertHasClasses($input->parentElement, $box);
        $this->assertSame($invalid, $input->getAttribute('aria-invalid'));
        $this->assertSame($disabled, $input->hasAttribute('disabled'));
    }

    public function test_password_memasang_gembok_dan_tombol_mata(): void
    {
        $dom = $this->dom('<x-ceplok::input-field label="Kata sandi" type="password" />');
        $input = $this->find($dom, 'input');
        $eye = $this->find($dom, 'button[aria-label="Tampilkan kata sandi"]');

        $this->assertSame('ceplokInputField', $this->find($dom, 'body > div')->getAttribute('x-data'));
        $this->assertSame('password', $input->getAttribute('type'));
        $this->assertSame('false', $input->getAttribute('spellcheck'));
        $this->assertSame('off', $input->getAttribute('autocapitalize'));
        $this->assertSame("ceplokRevealed ? 'text' : 'password'", $input->getAttribute('x-bind:type'));
        $this->assertSame('false', $eye->getAttribute('aria-pressed'));
        $this->assertSame($input->getAttribute('id'), $eye->getAttribute('aria-controls'));
        $this->assertCount(2, $eye->querySelectorAll('svg'));
        // Gembok bawaan 16px di kiri isian.
        $this->assertNotNull($dom->querySelector('input')->parentElement->querySelector('span > svg[viewBox="0 0 16 16"]'));
        $this->assertNull($this->dom('<x-ceplok::input-field type="password" :icon="false" aria-label="Kata sandi" />')->querySelector('svg[viewBox="0 0 16 16"]'));
    }

    public function test_clearable_menampilkan_tombol_hapus(): void
    {
        $button = $this->find($this->dom('<x-ceplok::input-field label="Cari" clearable />'), 'button[aria-label="Kosongkan isian"]');

        $this->assertSame('ceplokClear()', $button->getAttribute('x-on:click'));
        $this->assertNull($this->dom('<x-ceplok::input-field label="Cari" />')->querySelector('button'));
    }

    public function test_floating_label_turun_saat_kosong_dan_naik_saat_berisi(): void
    {
        $empty = $this->dom('<x-ceplok::floating-label label="Nama" placeholder="Contoh: Budi" />');
        $label = $this->find($empty, 'label');

        $this->assertHasClasses($label, 'top-1/2 text-sm left-3 text-content-muted');
        // Placeholder baru muncul setelah label naik.
        $this->assertFalse($this->find($empty, 'input')->hasAttribute('placeholder'));
        $this->assertSame('ceplokFloatingLabel', $this->find($empty, 'body > div')->getAttribute('x-data'));

        $filled = $this->dom('<x-ceplok::floating-label label="Nama" placeholder="Contoh: Budi" value="Budi" />');

        $this->assertHasClasses($this->find($filled, 'label'), 'top-0 left-3 text-xs');
        $this->assertSame('Contoh: Budi', $this->find($filled, 'input')->getAttribute('placeholder'));
        $this->assertSame('ceplokFloatingLabel', $this->find($filled, 'body > div')->getAttribute('x-data'));
    }

    public function test_floating_label_error_dan_active_mengunci_label_di_atas(): void
    {
        $error = $this->dom('<x-ceplok::floating-label label="Nama" state="error" helper-text="Wajib diisi" />');

        $this->assertHasClasses($this->find($error, 'label'), 'top-0 text-feedback-error');
        $this->assertSame('true', $this->find($error, 'input')->getAttribute('aria-invalid'));
        $this->assertHasClasses($this->find($error, 'input')->parentElement, 'border-red-600');
        $this->assertFalse($this->find($error, 'label')->hasAttribute('x-bind:class'));

        $active = $this->find($this->dom('<x-ceplok::floating-label label="Nama" state="active" application="purple" />'), 'label');

        $this->assertHasClasses($active, 'top-0 text-floating-label-accent-purple');
    }

    public function test_text_area_default_dan_editor(): void
    {
        $default = $this->dom('<x-ceplok::text-area label="Pesan" hint="0/200" helper-text="Caption" name="pesan" value="Halo" rows="4" />');
        $field = $this->find($default, 'textarea');

        $this->assertSame('Halo', $field->textContent);
        $this->assertSame('pesan', $field->getAttribute('name'));
        $this->assertSame('4', $field->getAttribute('rows'));
        $this->assertFalse($field->hasAttribute('value'));
        $this->assertHasClasses($field, 'h-40.5 rounded-lg border focus-within:border-primary-600');
        $this->assertSame('0/200', trim($this->find($default, 'label + span')->textContent));

        $editor = $this->dom('<x-ceplok::text-area label="Pesan" type="editor" submit-label="Kirim" />');
        $buttons = $editor->querySelectorAll('[title]');

        $this->assertCount(7, $buttons);
        $this->assertSame("ceplokToolbar('code')", $this->find($editor, 'button[title="Sisipkan kode"]')->getAttribute('x-on:click'));
        $this->assertNotNull($editor->querySelector('span.w-px[aria-hidden="true"]'));
        $this->assertHasClasses($this->find($editor, 'textarea'), 'h-41.5 bg-surface');
        $this->assertSame('ceplokSubmit()', $this->find($editor, 'button:not([title])')->getAttribute('x-on:click'));
    }

    public function test_slot_toolbar_menggantikan_tombol_bawaan(): void
    {
        $dom = $this->dom('<x-ceplok::text-area type="editor" aria-label="Pesan"><x-slot:toolbar><span id="alat">Alat</span></x-slot:toolbar></x-ceplok::text-area>');

        $this->assertNotNull($dom->querySelector('#alat'));
        $this->assertCount(0, $dom->querySelectorAll('[title]'));
    }

    public function test_nilai_awal_wire_model_dirender_server(): void
    {
        $component = Livewire::test(new class extends Component
        {
            public string $nama = 'Budi';

            public string $sandi = 'rahasia';

            public string $catatan = 'Catatan awal';

            public string $kota = 'Bandung';

            public function render(): string
            {
                return <<<'BLADE'
                    <div>
                        <x-ceplok::input-field id="nama" label="Nama" wire:model="nama" />
                        <x-ceplok::input-field id="sandi" label="Sandi" type="password" wire:model="sandi" />
                        <x-ceplok::text-area id="catatan" label="Catatan" wire:model="catatan" />
                        <x-ceplok::floating-label id="kota" label="Kota" wire:model.live="kota" />
                    </div>
                    BLADE;
            }
        });

        $dom = $this->parse($component->html());

        $this->assertSame('Budi', $this->find($dom, '#nama')->getAttribute('value'));
        // Kata sandi tidak pernah ditulis ke HTML.
        $this->assertFalse($this->find($dom, '#sandi')->hasAttribute('value'));
        $this->assertSame('Catatan awal', $this->find($dom, '#catatan')->textContent);
        $this->assertSame('Bandung', $this->find($dom, '#kota')->getAttribute('value'));
        $this->assertHasClasses($this->find($dom, 'label[for="kota"]'), 'top-0');
    }
}
