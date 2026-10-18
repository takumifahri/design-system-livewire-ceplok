<?php

namespace Tests\Feature\Kit\Components;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    public function test_inisial_dengan_nama_aksesibel(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar initials="PH" alt="Putri Handayani" />'), 'body > div');

        $this->assertSame('img', $avatar->getAttribute('role'));
        $this->assertSame('Putri Handayani', $avatar->getAttribute('aria-label'));
        $this->assertHasClasses($avatar, 'grid shrink-0 place-items-center overflow-hidden rounded-full font-normal bg-gray-100 text-gray-900 size-8 text-base');
        $this->assertFalse($avatar->hasAttribute('x-data'));

        $initials = $this->find($avatar, 'span[aria-hidden="true"]');

        $this->assertSame('PH', trim($initials->textContent));
    }

    public function test_tanpa_alt_tidak_diumumkan_dua_kali(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar initials="PH" />'), 'body > div');

        $this->assertFalse($avatar->hasAttribute('role'));
        $this->assertFalse($avatar->hasAttribute('aria-label'));
    }

    /** @return array<string, array{string, string}> */
    public static function ukuran(): array
    {
        return [
            'small' => ['small', 'size-6 text-xs'],
            'default' => ['default', 'size-8 text-base'],
            'large' => ['large', 'size-20 text-3xl'],
        ];
    }

    #[DataProvider('ukuran')]
    public function test_size_mengatur_diameter_dan_ukuran_teks(string $size, string $classes): void
    {
        $this->assertHasClasses($this->find($this->dom("<x-ceplok::avatar size=\"{$size}\" initials=\"PH\" />"), 'div'), $classes);
    }

    public function test_dark_mode_mengganti_warna_lingkaran_inisial(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar initials="PH" dark-mode />'), 'div');

        $this->assertHasClasses($avatar, 'bg-gray-800 text-gray-100');
        $this->assertLacksClasses($avatar, 'bg-gray-100 text-gray-900');
    }

    public function test_foto_jatuh_kembali_ke_inisial_lewat_alpine(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar src="/foto/putri.jpg" alt="Putri Handayani" initials="PH" />'), 'body > div');

        $this->assertSame('ceplokAvatar', $avatar->getAttribute('x-data'));
        $this->assertSame("failed ? 'img' : null", $avatar->getAttribute('x-bind:role'));
        $this->assertSame("failed ? 'Putri Handayani' : null", $avatar->getAttribute('x-bind:aria-label'));
        $this->assertFalse($avatar->hasAttribute('role'));
        $this->assertHasClasses($avatar, 'bg-gray-100 text-gray-900');

        $img = $this->find($avatar, 'img');

        $this->assertSame('/foto/putri.jpg', $img->getAttribute('src'));
        $this->assertSame('Putri Handayani', $img->getAttribute('alt'));
        $this->assertSame('img', $img->getAttribute('x-ref'));
        $this->assertSame('failed = true', $img->getAttribute('x-on:error'));

        $initials = $this->find($avatar, 'span');

        $this->assertSame('failed', $initials->getAttribute('x-show'));
        $this->assertTrue($initials->hasAttribute('x-cloak'));
        $this->assertSame('PH', trim($initials->textContent));
    }

    public function test_foto_dark_mode_mengatur_warna_lewat_alpine(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar src="/foto/putri.jpg" initials="PH" dark-mode />'), 'body > div');

        $this->assertSame("failed ? 'bg-gray-800 text-gray-100' : 'bg-gray-100 text-gray-900'", $avatar->getAttribute('x-bind:class'));
        $this->assertLacksClasses($avatar, 'bg-gray-100 bg-gray-800');
    }

    public function test_slot_dipakai_bila_prop_initials_kosong(): void
    {
        $avatar = $this->find($this->dom("<x-ceplok::avatar alt=\"Admin\">{{ svg('ceplok-o-user', 'size-4') }}</x-ceplok::avatar>"), 'div');

        $this->assertNotNull($avatar->querySelector('span[aria-hidden="true"] > svg.size-4'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::avatar size="lg" initials="PH" />', "Prop `size` pada <x-ceplok::avatar> harus salah satu dari 'small', 'default', 'large'; diberikan 'lg'.");
    }
}
