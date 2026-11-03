{{--
    Navbar tiruan untuk mockup ponsel di halaman Table of Contents. Ikon kiri
    melambangkan pembuka sidebar; slot-nya duduk di kanan dan berisi Table of
    Contents `layout="drawer"` dengan slot `trigger` berupa ikon, jadi ikon
    kanan membuka Drawer daftar isi.

    Navbar ini sengaja tidak `relative` atau `sticky`: Drawer `drawer-contained`
    menempel di pembungkus `relative` terdekat, yaitu bingkai ponselnya.
--}}
<div {{ $attributes->class('flex shrink-0 items-center justify-between gap-3 border-b border-border bg-toc-surface px-3 py-2.5 transition-colors duration-300') }}>
    <span class="inline-flex p-1.5 text-content-muted" aria-hidden="true"><x-ceplok-o-bars class="size-5" /></span>
    {{ $slot }}
</div>
