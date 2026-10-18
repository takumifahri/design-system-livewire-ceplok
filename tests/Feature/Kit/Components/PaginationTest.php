<?php

namespace Tests\Feature\Kit\Components;

use Dom\Element;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    /** @return array<string, array{int, int, list<string>}> */
    public static function daftarHalaman(): array
    {
        return [
            'satu halaman' => [1, 1, ['1']],
            'lima halaman tanpa ringkasan' => [3, 5, ['1', '2', '3', '4', '5']],
            'awal' => [1, 10, ['1', '2', '3', '...', '10']],
            'masih di awal' => [3, 10, ['1', '2', '3', '...', '10']],
            'tengah' => [5, 10, ['1', '...', '5', '...', '10']],
            'menjelang akhir' => [8, 10, ['1', '...', '8', '9', '10']],
            'akhir' => [10, 10, ['1', '...', '8', '9', '10']],
            'tanpa halaman' => [1, 0, []],
        ];
    }

    #[DataProvider('daftarHalaman')]
    public function test_lebih_dari_lima_halaman_diringkas_seperti_kit_react(int $current, int $total, array $expected): void
    {
        $nav = $this->find($this->dom("<x-ceplok::pagination :current-page=\"{$current}\" :total-pages=\"{$total}\" />"), 'body > nav');
        $cells = array_slice($this->childrenOf($nav), 1, -1);

        $this->assertSame($expected, array_map(fn (Element $cell) => trim($cell->textContent), $cells));
    }

    public function test_mode_tombol_untuk_livewire(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::pagination :current-page="3" :total-pages="5" />'), 'body > nav');

        $this->assertSame('ceplokPagination', $nav->getAttribute('x-data'));
        $this->assertSame('page', $nav->getAttribute('x-modelable'));
        $this->assertSame('Pagination', $nav->getAttribute('aria-label'));
        $this->assertHasClasses($nav, 'inline-flex overflow-hidden rounded-lg h-10 border border-gray-300 bg-white');

        $active = $this->find($nav, 'button[aria-current="page"]');

        $this->assertSame('3', trim($active->textContent));
        $this->assertFalse($active->hasAttribute('x-on:click'));
        $this->assertHasClasses($active, 'h-10 w-10 bg-primary-50 text-primary-500');
        $this->assertSame('go(2)', $this->find($nav, 'button[aria-label="Previous page"]')->getAttribute('x-on:click'));
        $this->assertSame('go(4)', $this->find($nav, 'button[aria-label="Next page"]')->getAttribute('x-on:click'));

        $lastPage = collect(iterator_to_array($nav->querySelectorAll('button'), false))
            ->first(fn (Element $button) => trim($button->textContent) === '5');

        $this->assertSame('go(5)', $lastPage?->getAttribute('x-on:click'));
    }

    public function test_tombol_di_ujung_daftar_dinonaktifkan(): void
    {
        $first = $this->dom('<x-ceplok::pagination :current-page="1" :total-pages="5" />');
        $previous = $this->find($first, 'button[aria-label="Previous page"]');

        $this->assertTrue($previous->hasAttribute('disabled'));
        $this->assertFalse($previous->hasAttribute('x-on:click'));
        $this->assertHasClasses($previous, 'disabled:cursor-not-allowed disabled:opacity-50');

        $last = $this->find($this->dom('<x-ceplok::pagination :current-page="5" :total-pages="5" />'), 'button[aria-label="Next page"]');

        $this->assertTrue($last->hasAttribute('disabled'));
        $this->assertFalse($last->hasAttribute('x-on:click'));
    }

    public function test_url_berpola_membuat_setiap_halaman_menjadi_tautan(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::pagination :current-page="2" :total-pages="5" url="/pengajuan?page={page}" />'), 'nav');

        $this->assertFalse($nav->hasAttribute('x-data'));
        $this->assertSame('/pengajuan?page=1', $this->find($nav, 'a[aria-label="Previous page"]')->getAttribute('href'));
        $this->assertSame('/pengajuan?page=3', $this->find($nav, 'a[aria-label="Next page"]')->getAttribute('href'));
        $this->assertSame('/pengajuan?page=2', $this->find($nav, 'a[aria-current="page"]')->getAttribute('href'));
        $this->assertCount(7, $nav->querySelectorAll('a'));
        $this->assertNull($nav->querySelector('button'));
    }

    public function test_url_di_halaman_pertama_menonaktifkan_tombol_sebelumnya(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::pagination :current-page="1" :total-pages="3" url="/hal/{page}" />'), 'nav');

        $this->assertTrue($this->find($nav, 'button[aria-label="Previous page"]')->hasAttribute('disabled'));
        $this->assertSame('/hal/2', $this->find($nav, 'a[aria-label="Next page"]')->getAttribute('href'));
    }

    public function test_url_berupa_closure(): void
    {
        $nav = $this->find($this->dom('<x-ceplok::pagination :current-page="2" :total-pages="3" :url="fn (int $page) => \'/arsip/\'.$page" />'), 'nav');

        $this->assertSame('/arsip/3', $this->find($nav, 'a[aria-label="Next page"]')->getAttribute('href'));
    }

    /** @return array<string, array{string, bool, string}> */
    public static function tema(): array
    {
        return [
            'default' => ['default', false, 'bg-gray-50 text-gray-500'],
            'primary' => ['primary', false, 'bg-primary-50 text-primary-500'],
            'purple' => ['purple', false, 'bg-purple-50 text-purple-500'],
            'default gelap' => ['default', true, 'bg-gray-700 text-gray-300'],
            'primary gelap' => ['primary', true, 'bg-gray-700 text-primary-500'],
            'purple gelap' => ['purple', true, 'bg-gray-700 text-purple-400'],
        ];
    }

    #[DataProvider('tema')]
    public function test_theme_dan_dark_mode_menentukan_warna_halaman_aktif(string $theme, bool $dark, string $classes): void
    {
        $dom = $this->dom('<x-ceplok::pagination :current-page="2" :total-pages="3" :theme="$theme" :dark-mode="$dark" />', compact('theme', 'dark'));

        $this->assertHasClasses($this->find($dom, '[aria-current="page"]'), $classes);
        $this->assertHasClasses($this->find($dom, 'nav'), $dark ? 'border-gray-700 bg-gray-800' : 'border-gray-300 bg-white');
    }

    public function test_size_s_dan_responsive(): void
    {
        $small = $this->dom('<x-ceplok::pagination :current-page="1" :total-pages="3" size="s" />');

        $this->assertHasClasses($this->find($small, 'nav'), 'h-8');
        $this->assertHasClasses($this->find($small, '[aria-current="page"]'), 'h-8 min-w-8 px-2');
        $this->assertHasClasses($this->find($small, 'button svg'), 'size-5');

        $responsive = $this->dom('<x-ceplok::pagination :current-page="1" :total-pages="3" size="responsive" />');

        $this->assertHasClasses($this->find($responsive, 'nav'), 'h-8 @lg:h-10');
        $this->assertHasClasses($this->find($responsive, '[aria-current="page"]'), '@lg:h-10 @lg:min-w-10');
    }

    public function test_angka_dari_atribut_statis_diterima(): void
    {
        $this->assertSame('3', trim($this->find($this->dom('<x-ceplok::pagination current-page="3" total-pages="5" />'), '[aria-current="page"]')->textContent));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::pagination :current-page="0" :total-pages="5" />', 'Prop `current-page` pada <x-ceplok::pagination> harus bilangan bulat minimal 1; diberikan 0.');
        $this->assertPropError('<x-ceplok::pagination :current-page="1" :total-pages="-1" />', 'Prop `total-pages` pada <x-ceplok::pagination> harus bilangan bulat minimal 0');
        $this->assertPropError('<x-ceplok::pagination theme="green" />', "Prop `theme` pada <x-ceplok::pagination> harus salah satu dari 'default', 'primary', 'purple'");
        $this->assertPropError('<x-ceplok::pagination size="lg" />', 'Prop `size` pada <x-ceplok::pagination>');
    }
}
