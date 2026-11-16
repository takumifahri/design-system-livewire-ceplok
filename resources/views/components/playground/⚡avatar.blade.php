<?php

use CeplokUi\DesignKit\Enums\AvatarFallback;
use CeplokUi\DesignKit\Enums\AvatarSize;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public AvatarSize $size = AvatarSize::Default;

    public string $content = 'auto';

    public AvatarFallback $profileFallback = AvatarFallback::Female;

    public string $name = 'Putri Handayani';

    public bool $dark = false;

    #[Computed]
    public function usage(): string
    {
        $nama = trim($this->name);
        $initials = $this->content === 'override' || ($this->content !== 'profile' && $nama === '') ? 'UI' : null;
        $lines = ['<div class="flex items-center gap-3">', '    <x-ceplok::avatar'];

        if ($this->size !== AvatarSize::Default) {
            $lines[] = '        ⟦size⟧="'.$this->size->value.'"';
        }

        if ($this->content === 'photo') {
            $lines[] = '        ⟦src⟧="/foto/putri.jpg"';
        }

        if ($nama !== '') {
            $lines[] = '        ⟦name⟧="'.e($nama).'"';
        }

        $lines[] = '        ⟦alt⟧=""';

        if ($initials !== null) {
            $lines[] = '        ⟦initials⟧="UI"';
        }

        if ($this->content === 'profile') {
            $lines[] = '        ⟦fallback⟧="'.$this->profileFallback->value.'"';
        }

        $lines[] = '    />';
        $lines[] = '    <span>'.e($nama !== '' ? $nama : 'Tanpa nama').'</span>';
        $lines[] = '</div>';
        $avatar = implode("\n", $lines);

        return $this->dark ? "<section ⟦data-theme⟧=\"dark\">\n".preg_replace('/^/m', '    ', $avatar)."\n</section>" : $avatar;
    }
};
?>

<div class="space-y-16">
    @php
        $nama = trim($name);
        $initials = $content === 'override' || ($content !== 'profile' && $nama === '') ? 'UI' : null;
    @endphp
    <x-docs.section id="playground" title="Playground">
        <x-docs.lead>Ubah tema, ukuran, isi, fallback profil, dan nama. Preview memakai komponen production yang sama.</x-docs.lead>

        <x-docs.stage max-width="max-w-[360px]" :dark="$dark">
            <div @if ($dark) data-theme="dark" @else data-theme="light" @endif class="flex min-h-30 items-center justify-center gap-3">
                <x-ceplok::avatar
                    :size="$size"
                    :src="$content === 'photo' ? '/images/avatar-sample.svg' : null"
                    :name="$nama !== '' ? $nama : null"
                    alt=""
                    :initials="$initials"
                    :fallback="$content === 'profile' ? $profileFallback : AvatarFallback::Initials"
                />
                <span class="text-sm text-content">{{ $nama !== '' ? $nama : 'Tanpa nama' }}</span>
            </div>
        </x-docs.stage>

        <x-docs.controls>
            <x-docs.control label="Theme">
                <x-docs.segmented label="Pilih theme" model="dark" :value="$dark" :options="[
                    ['value' => false, 'label' => 'Light'],
                    ['value' => true, 'label' => 'Dark'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Size">
                <x-docs.segmented label="Pilih ukuran" model="size" :value="$size" :options="[
                    ['value' => 'small', 'label' => 'S'],
                    ['value' => 'default', 'label' => 'M'],
                    ['value' => 'large', 'label' => 'L'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Isi">
                <x-docs.segmented label="Pilih isi Avatar" model="content" :value="$content" :options="[
                    ['value' => 'photo', 'label' => 'Foto'],
                    ['value' => 'auto', 'label' => 'Initials'],
                    ['value' => 'override', 'label' => 'Override'],
                    ['value' => 'profile', 'label' => 'Profile'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Profile fallback">
                <x-docs.segmented label="Pilih ilustrasi profile" model="profileFallback" :value="$profileFallback" :disabled="$content !== 'profile'" :options="[
                    ['value' => 'female', 'label' => 'Female'],
                    ['value' => 'male', 'label' => 'Male'],
                ]" />
            </x-docs.control>
            <x-docs.control label="Nama">
                <input wire:model.live="name" aria-label="Nama untuk preview Avatar" class="mt-1 w-full rounded-lg border border-field-border bg-field px-3 py-2 text-sm text-content outline-none focus:border-brand" />
                @if ($content !== 'profile' && $nama === '')
                    <p class="mt-1 text-xs text-feedback-error" role="status">
                        Nama kosong memakai initials UI agar fallback tetap valid.
                    </p>
                @endif
            </x-docs.control>
        </x-docs.controls>
    </x-docs.section>

    <x-docs.section id="penggunaan" title="Penggunaan">
        <x-docs.lead>
            Blok ini mengikuti kontrol di Playground. Prop yang masih memakai nilai bawaan sengaja tidak ditulis.
            Fallback <span class="text-primary-500">initials</span> selalu membutuhkan
            <span class="text-primary-500">name</span> atau <span class="text-primary-500">initials</span>.
        </x-docs.lead>
        <x-docs.code :code="$this->usage" flush />
    </x-docs.section>
</div>
