<?php

namespace Tests\Unit\Kit;

use CeplokUi\DesignKit\Support\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    public function test_pasangan_nilai_label_dan_daftar_teks(): void
    {
        $this->assertSame([['label' => null, 'options' => [
            ['value' => 'jkt', 'label' => 'Jakarta', 'disabled' => false, 'selected' => false],
        ]]], Options::fromArray(['jkt' => 'Jakarta']));

        // Daftar teks biasa: nilainya sama dengan labelnya, bukan indeksnya.
        $this->assertSame(['Jakarta', 'Bandung'], array_column(Options::fromArray(['Jakarta', 'Bandung'])[0]['options'], 'value'));
    }

    public function test_daftar_array_dan_kelompok_seperti_optgroup(): void
    {
        $groups = Options::fromArray([
            ['value' => 'aceh', 'label' => 'Aceh'],
            ['label' => 'Jawa', 'disabled' => true, 'options' => ['jb' => 'Jawa Barat']],
            ['value' => 'bali', 'label' => 'Bali', 'disabled' => true],
        ]);

        $this->assertSame([null, 'Jawa', null], array_column($groups, 'label'));
        $this->assertTrue($groups[1]['options'][0]['disabled']);
        $this->assertSame('bali', $groups[2]['options'][0]['value']);
        $this->assertTrue($groups[2]['options'][0]['disabled']);
    }

    public function test_slot_mengikuti_aturan_html(): void
    {
        $groups = Options::fromHtml('<option value="">Pilih</option><option>Aceh</option><optgroup label="Jawa" disabled><option value="jb">Jawa Barat</option><option value="jt" selected>Jawa Tengah</option></optgroup><option value="bali">Bali &amp; Nusa</option>');

        $this->assertSame([null, 'Jawa', null], array_column($groups, 'label'));
        // Tanpa `value`, teksnya yang jadi nilai.
        $this->assertSame('Aceh', $groups[0]['options'][1]['value']);
        // <optgroup disabled> mematikan seluruh isinya.
        $this->assertSame([true, true], array_column($groups[1]['options'], 'disabled'));
        $this->assertSame('Bali & Nusa', $groups[2]['options'][0]['label']);
        $this->assertSame('jt', Options::selected($groups));
        $this->assertSame('', Options::first($groups));
    }

    public function test_atribut_selected_disetel_ulang_tanpa_mengubah_atribut_lain(): void
    {
        $html = Options::withSelected('<option value="a" selected>A</option><option value="b" data-uji="1">B &amp; C</option>', 'b');

        $this->assertSame('<option value="a">A</option><option value="b" data-uji="1" selected>B &amp; C</option>', $html);
    }

    public function test_opsi_pertama_yang_tidak_dimatikan(): void
    {
        $this->assertSame('b', Options::first(Options::fromArray([['value' => 'a', 'label' => 'A', 'disabled' => true], ['value' => 'b', 'label' => 'B']])));
        $this->assertNull(Options::first([]));
    }
}
