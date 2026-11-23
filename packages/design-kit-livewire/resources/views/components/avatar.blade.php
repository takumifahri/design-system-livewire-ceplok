@php
    use CeplokUi\DesignKit\Enums\AvatarFallback;
    use CeplokUi\DesignKit\Enums\AvatarSize;
    use CeplokUi\DesignKit\Support\Avatar;
    use CeplokUi\DesignKit\Support\Content;
    use CeplokUi\DesignKit\Support\Prop;
@endphp

{{--
    Avatar — lingkaran identitas berisi foto, inisial, atau ilustrasi profil.
    Foto diprioritaskan. Jika kosong atau gagal, `fallback` memilih inisial,
    ilustrasi female, atau ilustrasi male. `dark-mode` dipertahankan sebagai
    alias deprecated untuk scope ambient `data-theme="dark"`. Seperti kit
    React, atribut root dari consumer dapat meng-override role, aria-label,
    dan data-theme yang dihitung; class tetap digabungkan.
--}}
@props([
    'src' => null,
    'alt' => null,
    'name' => null,
    'initials' => null,
    'fallback' => 'initials',
    'size' => 'default',
    'darkMode' => false,
])

@php
    $size = Prop::oneOf(AvatarSize::class, $size, AvatarSize::Default, 'avatar', 'size');
    $fallback = Prop::oneOf(AvatarFallback::class, $fallback, AvatarFallback::Initials, 'avatar', 'fallback');
    $darkMode = Prop::bool($darkMode, false, 'avatar', 'dark-mode');
    $accessibleName = (string) ($alt ?? $name ?? '');
    $sizes = [
        'small' => 'size-6 text-xs',
        'default' => 'size-8 text-base',
        'large' => 'size-20 text-3xl',
    ];
    $base = 'grid shrink-0 place-items-center overflow-hidden rounded-full bg-avatar-surface font-sans font-normal text-avatar-content';
    $hasImage = filled($src);
    $initials = Content::filled($initials) ? $initials : (Content::filled($slot) ? $slot : Avatar::initials($name));
    $hasInitials = Content::filled($initials);
    $profile = $fallback !== AvatarFallback::Initials->value;
    if (! $profile && ! $hasInitials) {
        Prop::violation('<x-ceplok::avatar fallback="initials"> memerlukan prop `name`, prop `initials`, atau isi slot.');
        // Production tidak memutus halaman; tanda tanya mencegah lingkaran
        // kosong sambil laporan konfigurasi yang salah tetap masuk ke log.
        $initials = '?';
        $hasInitials = true;
    }
    $rootRole = $attributes->get('role');
    $rootLabel = $attributes->get('aria-label');
    $rootTheme = $attributes->get('data-theme', $darkMode ? 'dark' : null);
    $rootAttributes = $attributes->except(['role', 'aria-label', 'data-theme']);
@endphp

@if ($hasImage)
    <div
        x-data="ceplokAvatar"
        @if ($rootRole !== null)
            role="{{ $rootRole }}"
        @elseif ($accessibleName !== '')
            x-bind:role="failed ? 'img' : null"
        @endif
        @if ($rootLabel !== null)
            aria-label="{{ $rootLabel }}"
        @elseif ($accessibleName !== '')
            x-bind:aria-label="failed ? @js($accessibleName) : null"
        @endif
        @if ($rootTheme !== null) data-theme="{{ $rootTheme }}" @endif
        {{ $rootAttributes->class([$base, $sizes[$size]]) }}
    >
        <img
            x-ref="img"
            x-show="! failed"
            x-on:error="fail(@js((string) $src))"
            x-on:load="loaded(@js((string) $src))"
            src="{{ $src }}"
            alt="{{ $accessibleName }}"
            class="size-full object-cover"
        />
        @if ($profile)
            <span class="grid size-full place-items-center" aria-hidden="true" x-show="failed" x-cloak>
                <img src="{{ Avatar::profile($fallback, 'light') }}" alt="" class="size-full [display:var(--avatar-profile-light-display)]" aria-hidden="true" />
                <img src="{{ Avatar::profile($fallback, 'dark') }}" alt="" class="size-full [display:var(--avatar-profile-dark-display)]" aria-hidden="true" />
            </span>
        @elseif ($hasInitials)
            <span class="leading-none" aria-hidden="true" x-show="failed" x-cloak>{{ $initials }}</span>
        @endif
    </div>
@else
    <div
        @if ($rootRole !== null || $accessibleName !== '') role="{{ $rootRole ?? 'img' }}" @endif
        @if ($rootLabel !== null || $accessibleName !== '') aria-label="{{ $rootLabel ?? $accessibleName }}" @endif
        @if ($rootTheme !== null) data-theme="{{ $rootTheme }}" @endif
        {{ $rootAttributes->class([$base, $sizes[$size]]) }}
    >
        @if ($profile)
            <span class="grid size-full place-items-center" aria-hidden="true">
                <img src="{{ Avatar::profile($fallback, 'light') }}" alt="" class="size-full [display:var(--avatar-profile-light-display)]" aria-hidden="true" />
                <img src="{{ Avatar::profile($fallback, 'dark') }}" alt="" class="size-full [display:var(--avatar-profile-dark-display)]" aria-hidden="true" />
            </span>
        @elseif ($hasInitials)
            <span class="leading-none" aria-hidden="true">{{ $initials }}</span>
        @endif
    </div>
@endif
