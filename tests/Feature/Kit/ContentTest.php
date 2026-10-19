<?php

namespace Tests\Feature\Kit;

use CeplokUi\DesignKit\Support\Content;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentSlot;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ContentTest extends TestCase
{
    /** @return array<string, array{mixed, bool}> */
    public static function isi(): array
    {
        return [
            'null' => [null, false],
            'false' => [false, false],
            'string kosong' => ['', false],
            'spasi saja' => ["  \n ", false],
            'teks' => ['Judul', true],
            'angka nol' => [0, true],
            'HtmlString kosong' => [new HtmlString(' '), false],
            'HtmlString berisi' => [new HtmlString('<em>Judul</em>'), true],
            'slot kosong' => [new ComponentSlot(''), false],
            'slot berisi' => [new ComponentSlot('Judul'), true],
        ];
    }

    #[DataProvider('isi')]
    public function test_filled_mengikuti_aturan_falsy_react(mixed $value, bool $expected): void
    {
        $this->assertSame($expected, Content::filled($value));
    }

    public function test_filled_menerima_hasil_svg(): void
    {
        // svg() mengembalikan objek Htmlable yang tidak bisa di-cast ke string.
        $this->assertTrue(Content::filled(svg('ceplok-o-bell')));
    }

    public function test_icon_menerima_nama_ikon_atau_markup_jadi(): void
    {
        $this->assertSame(svg('ceplok-o-user')->toHtml(), Content::icon('ceplok-o-user'));
        $this->assertSame('<svg data-ikon="kustom"></svg>', Content::icon(new HtmlString('<svg data-ikon="kustom"></svg>')));
    }
}
