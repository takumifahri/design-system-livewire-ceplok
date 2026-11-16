<?php

namespace Tests\Feature\Kit\Components;

use Tests\TestCase;

class SpinnerTest extends TestCase
{
    public function test_bawaannya_diumumkan_sebagai_status_loading(): void
    {
        $spinner = $this->find($this->dom('<x-ceplok::spinner />'), 'body > svg');

        $this->assertSame('status', $spinner->getAttribute('role'));
        $this->assertSame('Loading', $spinner->getAttribute('aria-label'));
        $this->assertSame('0 0 50 50', $spinner->getAttribute('viewBox'));
        $this->assertHasClasses($spinner, 'shrink-0 animate-spin motion-reduce:animate-none size-[50px]');
        $this->assertCount(2, $spinner->querySelectorAll('circle'));
        $this->assertSame('25 75', $this->find($spinner, 'circle.stroke-primary-600')->getAttribute('stroke-dasharray'));
    }

    public function test_size_large(): void
    {
        $this->assertHasClasses($this->find($this->dom('<x-ceplok::spinner size="large" />'), 'svg'), 'size-[100px]');
    }

    public function test_label_bisa_diganti(): void
    {
        $spinner = $this->find($this->dom('<x-ceplok::spinner aria-label="Memuat daftar pengguna" />'), 'svg');

        $this->assertSame('Memuat daftar pengguna', $spinner->getAttribute('aria-label'));
        $this->assertSame('status', $spinner->getAttribute('role'));
    }

    public function test_role_class_dan_atribut_svg_diteruskan(): void
    {
        $spinner = $this->find($this->dom('<x-ceplok::spinner role="progressbar" class="text-primary-700" data-testid="loading" />'), 'svg');

        $this->assertSame('progressbar', $spinner->getAttribute('role'));
        $this->assertSame('loading', $spinner->getAttribute('data-testid'));
        $this->assertHasClasses($spinner, 'shrink-0 animate-spin motion-reduce:animate-none size-[50px] text-primary-700');
    }

    public function test_aria_hidden_melepas_role_dan_label(): void
    {
        foreach (['<x-ceplok::spinner aria-hidden="true" />', '<x-ceplok::spinner :aria-hidden="true" />'] as $markup) {
            $spinner = $this->find($this->dom($markup), 'svg');

            $this->assertSame('true', $spinner->getAttribute('aria-hidden'));
            $this->assertFalse($spinner->hasAttribute('role'));
            $this->assertFalse($spinner->hasAttribute('aria-label'));
        }
    }

    public function test_prop_yang_tidak_sah_ditolak(): void
    {
        $this->assertPropError('<x-ceplok::spinner size="small" />', "Prop `size` pada <x-ceplok::spinner> harus salah satu dari 'default', 'large'; diberikan 'small'.");
    }
}
