<?php

namespace Tests\Feature\Kit;

use CeplokUi\DesignKit\Enums\BadgeVariant;
use CeplokUi\DesignKit\Enums\ButtonSize;
use CeplokUi\DesignKit\Support\Prop;
use Illuminate\Support\Facades\Exceptions;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;
use Tests\TestCase;

class PropTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['app.debug' => true]);
    }

    public function test_one_of_menerima_kasus_enum_atau_nilai_stringnya(): void
    {
        $this->assertSame('success', Prop::oneOf(BadgeVariant::class, BadgeVariant::Success, BadgeVariant::Gray, 'badge', 'variant'));
        $this->assertSame('success', Prop::oneOf(BadgeVariant::class, 'success', BadgeVariant::Gray, 'badge', 'variant'));
    }

    public function test_one_of_menolak_nilai_yang_tidak_dikenal_dan_menyebut_pilihan_yang_sah(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Prop `variant` pada <x-ceplok::badge> harus salah satu dari 'gray', 'brand', 'danger', 'warning', 'success'; diberikan 'hijau'.");

        Prop::oneOf(BadgeVariant::class, 'hijau', BadgeVariant::Gray, 'badge', 'variant');
    }

    public function test_one_of_menolak_kasus_dari_enum_lain(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('diberikan CeplokUi\DesignKit\Enums\ButtonSize::Base.');

        Prop::oneOf(BadgeVariant::class, ButtonSize::Base, BadgeVariant::Gray, 'badge', 'variant');
    }

    /** @return array<string, array{mixed, bool}> */
    public static function nilaiBoolean(): array
    {
        return [
            'true' => [true, true],
            'false' => [false, false],
            'string "true"' => ['true', true],
            'string "false"' => ['false', false],
            'string "TRUE"' => ['TRUE', true],
            'string "1"' => ['1', true],
            'string "0"' => ['0', false],
            'int 1' => [1, true],
            'int 0' => [0, false],
        ];
    }

    #[DataProvider('nilaiBoolean')]
    public function test_bool_menerima_boolean_dan_string_dari_atribut_statis(mixed $value, bool $expected): void
    {
        $this->assertSame($expected, Prop::bool($value, ! $expected, 'alert', 'dismissible'));
    }

    /** @return array<string, array{mixed, string}> */
    public static function bukanBoolean(): array
    {
        return [
            'salah ketik' => ['yes', "'yes'"],
            'string kosong' => ['', "''"],
            'null' => [null, 'NULL'],
            'array' => [[], 'array'],
            'objek' => [new stdClass, 'objek stdClass'],
        ];
    }

    #[DataProvider('bukanBoolean')]
    public function test_bool_menolak_nilai_lain(mixed $value, string $described): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Prop `dismissible` pada <x-ceplok::alert> harus boolean, mis. :dismissible=\"true\"; diberikan {$described}.");

        Prop::bool($value, true, 'alert', 'dismissible');
    }

    public function test_int_menerima_angka_dan_string_angka(): void
    {
        $this->assertSame(3, Prop::int(3, 1, 'pagination', 'current-page'));
        $this->assertSame(3, Prop::int('3', 1, 'pagination', 'current-page'));
        $this->assertSame(0, Prop::int('0', 1, 'pagination', 'total-pages', min: 0));
    }

    public function test_int_menolak_nilai_di_bawah_minimum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Prop `current-page` pada <x-ceplok::pagination> harus bilangan bulat minimal 1; diberikan 0.');

        Prop::int(0, 1, 'pagination', 'current-page', min: 1);
    }

    public function test_int_menolak_bukan_bilangan_bulat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("harus bilangan bulat; diberikan '2.5'.");

        Prop::int('2.5', 1, 'pagination', 'current-page');
    }

    public function test_tag_menerima_nama_tag_html(): void
    {
        $this->assertSame('section', Prop::tag('section', 'div', 'container', 'as'));
        $this->assertSame('h2', Prop::tag('h2', 'div', 'container', 'as'));
        $this->assertSame('my-element', Prop::tag('my-element', 'div', 'container', 'as'));
    }

    /** @return array<string, array{mixed}> */
    public static function bukanTag(): array
    {
        return [
            'menyisipkan atribut' => ['div onclick="alert(1)"'],
            'huruf besar' => ['Section'],
            'diawali angka' => ['1div'],
            'kosong' => [''],
            'bukan string' => [42],
        ];
    }

    #[DataProvider('bukanTag')]
    public function test_tag_menolak_selain_nama_tag(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Prop `as` pada <x-ceplok::container> harus nama tag HTML');

        Prop::tag($value, 'div', 'container', 'as');
    }

    public function test_di_production_nilai_bawaan_dipakai_dan_kesalahannya_dilaporkan(): void
    {
        config(['app.debug' => false]);
        Exceptions::fake();

        $this->assertSame('gray', Prop::oneOf(BadgeVariant::class, 'hijau', BadgeVariant::Gray, 'badge', 'variant'));
        $this->assertTrue(Prop::bool('yes', true, 'alert', 'dismissible'));
        $this->assertSame(1, Prop::int('abc', 1, 'pagination', 'current-page'));
        $this->assertSame('div', Prop::tag('div onclick', 'div', 'container', 'as'));

        Exceptions::assertReported(fn (InvalidArgumentException $e) => str_contains($e->getMessage(), '<x-ceplok::badge>'));
        Exceptions::assertReported(fn (InvalidArgumentException $e) => str_contains($e->getMessage(), '<x-ceplok::container>'));
    }

    public function test_di_production_halaman_tetap_dirender_dengan_nilai_bawaan(): void
    {
        config(['app.debug' => false]);
        Exceptions::fake();

        $badge = $this->find($this->dom('<x-ceplok::badge variant="hijau">Aktif</x-ceplok::badge>'), 'body > span');

        $this->assertHasClasses($badge, 'bg-gray-100 text-gray-900');
        Exceptions::assertReported(InvalidArgumentException::class);
    }
}
