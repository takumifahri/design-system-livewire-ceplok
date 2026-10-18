<?php

namespace Tests;

use Dom\Element;
use Dom\HTMLDocument;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use InvalidArgumentException;
use Throwable;

abstract class TestCase extends BaseTestCase
{
    /**
     * Merender potongan Blade lalu mem-parse hasilnya dengan parser HTML5
     * bawaan PHP 8.4, supaya tes memeriksa elemen dan atributnya lewat
     * selektor CSS alih-alih mencocokkan potongan string.
     *
     * @param  array<string, mixed>  $data
     */
    protected function dom(string $template, array $data = []): HTMLDocument
    {
        return $this->parse((string) $this->blade($template, $data));
    }

    /** Mem-parse HTML utuh, atau potongan HTML yang dibungkus ke dalam <body>. */
    protected function parse(string $html): HTMLDocument
    {
        if (! str_starts_with(ltrim($html), '<!')) {
            $html = '<!doctype html><html><body>'.$html.'</body></html>';
        }

        return HTMLDocument::createFromString($html, LIBXML_NOERROR);
    }

    /** Elemen pertama yang cocok dengan selektor; tes langsung gagal bila tidak ada. */
    protected function find(HTMLDocument|Element $root, string $selector): Element
    {
        $element = $root->querySelector($selector);

        $this->assertNotNull($element, "Tidak ada elemen yang cocok dengan `{$selector}`.");

        return $element;
    }

    /**
     * Anak langsung sebuah elemen. Ditelusuri lewat firstElementChild karena
     * properti `children` baru tersedia di PHP 8.5.
     *
     * @return list<Element>
     */
    protected function childrenOf(Element $element): array
    {
        $children = [];

        for ($child = $element->firstElementChild; $child !== null; $child = $child->nextElementSibling) {
            $children[] = $child;
        }

        return $children;
    }

    /** @return list<string> */
    protected function classesOf(Element $element): array
    {
        return preg_split('/\s+/', trim((string) $element->getAttribute('class')), flags: PREG_SPLIT_NO_EMPTY);
    }

    /** Elemen memuat semua kelas yang disebut (urutan dan kelas lain tidak diperiksa). */
    protected function assertHasClasses(Element $element, string $classes): void
    {
        $actual = $this->classesOf($element);

        foreach (preg_split('/\s+/', trim($classes)) as $class) {
            $this->assertContains($class, $actual, "Kelas `{$class}` tidak ada di `".implode(' ', $actual).'`.');
        }
    }

    /** Elemen tidak memuat satu pun kelas yang disebut. */
    protected function assertLacksClasses(Element $element, string $classes): void
    {
        $actual = $this->classesOf($element);

        foreach (preg_split('/\s+/', trim($classes)) as $class) {
            $this->assertNotContains($class, $actual, "Kelas `{$class}` seharusnya tidak ada di `".implode(' ', $actual).'`.');
        }
    }

    /**
     * Render gagal karena prop yang tidak sah. Mode debug dinyalakan dulu:
     * hanya di mode itu Prop melempar error; di production ia melapor ke log
     * dan memakai nilai bawaan.
     */
    protected function assertPropError(string $template, string $message): void
    {
        config(['app.debug' => true]);

        try {
            $this->blade($template);
        } catch (Throwable $e) {
            // Error dari dalam komponen dibungkus ViewException oleh Blade.
            while ($e->getPrevious() !== null && ! $e instanceof InvalidArgumentException) {
                $e = $e->getPrevious();
            }

            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertStringContainsString($message, $e->getMessage());

            return;
        }

        $this->fail('Render seharusnya gagal dengan pesan: '.$message);
    }
}
