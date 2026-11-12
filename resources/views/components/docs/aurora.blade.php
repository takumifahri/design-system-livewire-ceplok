{{--
    Latar cahaya hero — padanan Aurora di docs React: kisi halus, gumpalan
    cahaya, dan sapuan sinar. Satu rona saja, dari tangga primary, supaya
    latarnya terbaca sebagai cahaya, bukan hiasan. Pasang di dalam pembungkus
    `relative overflow-hidden`; lapisannya bertanda `data-depth`, jadi
    `x-parallax` pada pembungkus itu menggesernya makin pelan saat digulir.
--}}
<div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
    <div data-depth="0" class="ds-grid absolute inset-[-20%]"></div>
    <div data-depth="1" class="absolute inset-[-30%]">
        <div class="ds-blob absolute top-[6%] left-[2%] size-136 rounded-full bg-primary-200/40 blur-[110px]" style="--duration: 11s; --x: 8%; --y: -6%"></div>
        <div class="ds-blob absolute right-[4%] bottom-[2%] size-120 rounded-full bg-primary-100/50 blur-[120px]" style="--duration: 14.5s; --x: -8%; --y: 7%"></div>
        <div class="ds-blob absolute top-[38%] left-[46%] size-88 rounded-full bg-primary-50/60 blur-[90px]" style="--duration: 18s; --x: 8%; --y: -6%"></div>
    </div>
    <div data-depth="2" class="ds-beam absolute inset-0"></div>
    {{-- Peredam di tepi bawah: menyatukan hero dengan section putih di bawahnya. --}}
    <div class="absolute inset-x-0 bottom-0 h-40 bg-linear-to-b from-transparent to-white"></div>
</div>
