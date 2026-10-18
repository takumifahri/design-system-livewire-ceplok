<?php

use Livewire\Component;

/*
 * Contoh Selection di halaman Dropdown: pilihan urutan dipegang properti
 * $urut, dan tiap baris memanggil $set lewat `attributes`-nya. Panel menutup
 * sendiri setelah baris ditekan; Livewire lalu merender ulang label tombol
 * dan tanda terpilihnya.
 */
new class extends Component
{
    public string $urut = 'Terbaru';

    public bool $dark = false;
};
?>

<div class="contents">
    <x-docs.dropdown-example example="selection" model="urut" :selected="$urut" attached :dark="$dark" />
</div>
