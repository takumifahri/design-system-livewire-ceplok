<?php

namespace Tests\Feature\Kit\Components;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AvatarTest extends TestCase
{
    public function test_name_membentuk_inisial_dan_nama_aksesibel(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar name="Putri Handayani" />'), 'body > div');

        $this->assertSame('img', $avatar->getAttribute('role'));
        $this->assertSame('Putri Handayani', $avatar->getAttribute('aria-label'));
        $this->assertHasClasses($avatar, 'grid shrink-0 place-items-center overflow-hidden rounded-full bg-avatar-surface font-sans font-normal text-avatar-content size-8 text-base');
        $this->assertSame('PH', trim($this->find($avatar, 'span[aria-hidden="true"]')->textContent));
    }

    #[DataProvider('namaDanInisial')]
    public function test_inisial_otomatis_mengikuti_nama(string $name, string $initials): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar :name="'.var_export($name, true).'" />'), 'div');

        $this->assertSame($initials, trim($this->find($avatar, 'span')->textContent));
    }

    public static function namaDanInisial(): array
    {
        return [
            'satu kata' => ['Pengguna', 'PE'],
            'beberapa kata' => ['Putri Nur Handayani', 'PH'],
            'spasi berlebih' => ['  Putri   Handayani  ', 'PH'],
        ];
    }

    public function test_initials_mengganti_inisial_otomatis_dan_alt_kosong_dekoratif(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar name="Putri Handayani" alt="" initials="UI" />'), 'div');

        $this->assertFalse($avatar->hasAttribute('role'));
        $this->assertFalse($avatar->hasAttribute('aria-label'));
        $this->assertSame('UI', trim($this->find($avatar, 'span')->textContent));
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
        $this->assertHasClasses($this->find($this->dom("<x-ceplok::avatar size=\"{$size}\" name=\"Putri\" />"), 'div'), $classes);
    }

    public function test_profile_fallback_merender_aset_terang_dan_gelap(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar name="Putri Handayani" fallback="female" />'), 'div');
        $images = $avatar->querySelectorAll('span > img');

        $this->assertCount(2, $images);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $images->item(0)->getAttribute('src'));
        $this->assertHasClasses($images->item(0), '[display:var(--avatar-profile-light-display)]');
        $this->assertHasClasses($images->item(1), '[display:var(--avatar-profile-dark-display)]');
        $this->assertSame('', $images->item(0)->getAttribute('alt'));
    }

    public function test_dark_mode_deprecated_memasang_scope_tema(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar name="Putri" dark-mode />'), 'div');

        $this->assertSame('dark', $avatar->getAttribute('data-theme'));
    }

    public function test_atribut_root_meng_override_nilai_terhitung_dan_class_digabungkan(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar name="Putri" dark-mode role="presentation" aria-label="Profil" data-theme="light" class="ring-1" />'), 'div');

        $this->assertSame('presentation', $avatar->getAttribute('role'));
        $this->assertSame('Profil', $avatar->getAttribute('aria-label'));
        $this->assertSame('light', $avatar->getAttribute('data-theme'));
        $this->assertHasClasses($avatar, 'grid ring-1');
    }

    public function test_foto_jatuh_kembali_ke_fallback_lewat_alpine(): void
    {
        $avatar = $this->find($this->dom('<x-ceplok::avatar src="/foto/putri.jpg" name="Putri Handayani" />'), 'body > div');

        $this->assertSame('ceplokAvatar', $avatar->getAttribute('x-data'));
        $this->assertSame("failed ? 'img' : null", $avatar->getAttribute('x-bind:role'));
        $this->assertSame("failed ? 'Putri Handayani' : null", $avatar->getAttribute('x-bind:aria-label'));

        $img = $avatar->querySelector('img[x-ref="img"]');
        $this->assertSame('/foto/putri.jpg', $img->getAttribute('src'));
        $this->assertSame('Putri Handayani', $img->getAttribute('alt'));
        $this->assertSame('fail(\'\\/foto\\/putri.jpg\')', $img->getAttribute('x-on:error'));

        $fallback = $avatar->querySelector('span[x-show="failed"]');
        $this->assertSame('failed', $fallback->getAttribute('x-show'));
        $this->assertTrue($fallback->hasAttribute('x-cloak'));
        $this->assertSame('PH', trim($fallback->textContent));
    }

    public function test_slot_dipakai_bila_initials_kosong(): void
    {
        $avatar = $this->find($this->dom("<x-ceplok::avatar name=\"Admin\">{{ svg('ceplok-o-user', 'size-4') }}</x-ceplok::avatar>"), 'div');

        $this->assertNotNull($avatar->querySelector('span[aria-hidden="true"] > svg.size-4'));
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::avatar />', '<x-ceplok::avatar fallback="initials"> memerlukan prop `name`, prop `initials`, atau isi slot.');
        $this->assertPropError('<x-ceplok::avatar fallback="robot" name="Putri" />', "Prop `fallback` pada <x-ceplok::avatar> harus salah satu dari 'initials', 'female', 'male'; diberikan 'robot'.");
        $this->assertPropError('<x-ceplok::avatar size="lg" name="Putri" />', "Prop `size` pada <x-ceplok::avatar> harus salah satu dari 'small', 'default', 'large'; diberikan 'lg'.");
    }
}
