<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class IconTest extends TestCase
{
    public function test_menyeragamkan_ukuran_dan_menyembunyikan_ikon_dari_pembaca_layar(): void
    {
        $icon = $this->find($this->dom("<x-ceplok::icon>{{ svg('ceplok-o-bell') }}</x-ceplok::icon>"), 'body > span');

        $this->assertSame('true', $icon->getAttribute('aria-hidden'));
        $this->assertHasClasses($icon, 'inline-flex size-5 items-center justify-center [&>svg]:size-full');
        $this->assertNotNull($icon->querySelector('svg'));
    }

    public function test_kelas_pemakai_disambung_seperti_cn_di_react(): void
    {
        $icon = $this->find($this->dom("<x-ceplok::icon class=\"size-6 text-gray-500\">{{ svg('ceplok-o-bell') }}</x-ceplok::icon>"), 'span');

        $this->assertHasClasses($icon, 'inline-flex size-6 text-gray-500');
    }
}
