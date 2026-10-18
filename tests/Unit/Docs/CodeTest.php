<?php

namespace Tests\Unit\Docs;

use App\Docs\Code;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    public function test_dedent_membuang_indentasi_bersama_dan_baris_kosong_di_ujung(): void
    {
        $code = "\n        <div>\n            <p>Isi</p>\n        </div>\n    \n";

        $this->assertSame("<div>\n    <p>Isi</p>\n</div>", Code::dedent($code));
    }

    public function test_dedent_mengabaikan_baris_kosong_di_tengah(): void
    {
        $this->assertSame("a\n\nb", Code::dedent("    a\n\n    b"));
    }

    public function test_dedent_membuang_spasi_di_ujung_baris(): void
    {
        $this->assertSame("a\n  b", Code::dedent("  a   \n    b  "));
    }

    public function test_dedent_dari_slot_mengukur_indentasi_mulai_baris_kedua(): void
    {
        // Blade men-trim isi slot, jadi baris pertama sudah kehilangan indentasinya.
        $code = "<x-ceplok::badge\n                    variant=\"success\"\n                >Aktif</x-ceplok::badge>";

        $this->assertSame("<x-ceplok::badge\n    variant=\"success\"\n>Aktif</x-ceplok::badge>", Code::dedent($code, fromSlot: true));
    }

    public function test_dedent_menerima_akhir_baris_windows(): void
    {
        $this->assertSame("a\n  b", Code::dedent("    a\r\n      b\r\n"));
    }

    public function test_highlight_meng_escape_kode_lalu_memasang_sorotan(): void
    {
        $html = Code::highlight('<x-ceplok::badge ⟦variant⟧="success" ⟦*dark-mode⟧ />')->toHtml();

        $this->assertSame(
            '&lt;x-ceplok::badge <span class="text-primary-300">variant</span>=&quot;success&quot; <span class="font-bold text-primary-300">dark-mode</span> /&gt;',
            $html,
        );
    }

    public function test_highlight_nilai_hidup_menjadi_x_text_dengan_teks_cadangan(): void
    {
        $html = Code::highlight('max-w-[⟪width|720⟫px] ⟪measured.avail⟫')->toHtml();

        $this->assertSame('max-w-[<span x-text="width">720</span>px] <span x-text="measured.avail"></span>', $html);
    }

    public function test_highlight_tidak_mengubah_kurung_siku_biasa(): void
    {
        $this->assertSame('[[&#039;label&#039; =&gt; &#039;Home&#039;]]', Code::highlight("[['label' => 'Home']]")->toHtml());
    }

    public function test_highlight_tidak_menjalankan_markup_di_dalam_kode(): void
    {
        $html = Code::highlight('⟦<script>alert(1)</script>⟧')->toHtml();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
