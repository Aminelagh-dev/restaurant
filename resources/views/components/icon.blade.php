@props([
    'name' => 'dot',
    'size' => 20,
    'stroke' => 1.7,
])

@php
    $icons = [
        'dashboard'   => '<path d="M3 10.5 12 3l9 7.5M5 9.5V20h5v-6h4v6h5V9.5"/>',
        'menu'        => '<path d="M4 6h16M4 12h16M4 18h10"/>',
        'utensils'    => '<path d="M5 3v7a2 2 0 0 0 2 2v9M7 3v7M9 3v7M17 3c-1.5 1-2 3-2 6 0 2 .5 3 2 3v9"/>',
        'tag'         => '<path d="M3 7v5l8 8 6-6-8-8H6a3 3 0 0 0-3 3Z"/><circle cx="7.5" cy="9.5" r="1.3"/>',
        'layers'      => '<path d="m12 3 9 5-9 5-9-5 9-5Zm9 9-9 5-9-5m18 4-9 5-9-5"/>',
        'bag'         => '<path d="M6 8h12l-1 12a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1L6 8Zm3 0V6a3 3 0 0 1 6 0v2"/>',
        'receipt'     => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Zm3 5h6M9 12h6"/>',
        'users'       => '<path d="M9 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm7 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3 20c0-2.8 2.7-5 6-5s6 2.2 6 5m1-5c2.8 0 5 1.7 5 4"/>',
        'user'        => '<path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8c0-3.3 3.1-6 7-6s7 2.7 7 6"/>',
        'cart'        => '<path d="M3 4h2l2.4 12.3a1 1 0 0 0 1 .7h8.2a1 1 0 0 0 1-.8L20 8H6m3 13a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm9 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"/>',
        'search'      => '<path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm10 2-4.5-4.5"/>',
        'plus'        => '<path d="M12 5v14M5 12h14"/>',
        'edit'        => '<path d="M4 20h4L18.5 9.5a2.1 2.1 0 0 0-3-3L5 17v3ZM13.5 6.5l3 3"/>',
        'trash'       => '<path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m2 0-1 13a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1L6 7"/>',
        'clock'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'fire'        => '<path d="M12 3c1 3-1 4-1 6a3 3 0 0 0 6 0c0-1-.5-2-1-2.5C16 9 17 11 17 13a5 5 0 0 1-10 0c0-3 3-4 5-10Z"/>',
        'chevron-right' => '<path d="m9 6 6 6-6 6"/>',
        'chevron-down'  => '<path d="m6 9 6 6 6-6"/>',
        'arrow-left'  => '<path d="M19 12H5m6-7-7 7 7 7"/>',
        'arrow-right' => '<path d="M5 12h14m-7-7 7 7-7 7"/>',
        'check'       => '<path d="M5 13l4 4L19 7"/>',
        'check-circle'=> '<circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 4.5-5"/>',
        'x'           => '<path d="M6 6l12 12M18 6 6 18"/>',
        'sun'         => '<path d="M12 3v2m0 14v2M5 5l1.5 1.5M17.5 17.5 19 19M3 12h2m14 0h2M5 19l1.5-1.5M17.5 6.5 19 5M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z"/>',
        'moon'        => '<path d="M20 14.5A8 8 0 0 1 9.5 4 8 8 0 1 0 20 14.5Z"/>',
        'bell'        => '<path d="M6 9a6 6 0 0 1 12 0c0 5 2 6 2 6H4s2-1 2-6Zm4 10a2 2 0 0 0 4 0"/>',
        'leaf'        => '<path d="M5 19c0-9 6-14 15-14 0 9-5 15-14 15-1 0-1-1-1-1Zm2-2c4-5 7-7 11-9"/>',
        'map-pin'     => '<path d="M12 21s7-6.3 7-11a7 7 0 1 0-14 0c0 4.7 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/>',
        'phone'       => '<path d="M5 4h3l2 5-2 1a11 11 0 0 0 5 5l1-2 5 2v3a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/>',
        'truck'       => '<path d="M3 6h11v9H3V6Zm11 3h4l3 3v3h-7V9ZM7.5 18a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm10 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Z"/>',
        'box'         => '<path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Zm0 0v18M4 7.5l8 4.5 8-4.5"/>',
        'star'        => '<path d="m12 4 2.4 5 5.6.6-4.2 3.8 1.2 5.6L12 16.2 6.9 19l1.2-5.6L4 9.6 9.6 9 12 4Z"/>',
        'coins'       => '<ellipse cx="9" cy="7" rx="6" ry="3"/><path d="M3 7v5c0 1.7 2.7 3 6 3s6-1.3 6-3M9 12v5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5c0-1.7-2.7-3-6-3"/>',
        'grid'        => '<path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/>',
        'sparkle'     => '<path d="M12 3c.8 5 2.2 6.2 7 7-4.8.8-6.2 2-7 7-.8-5-2.2-6.2-7-7 4.8-.8 6.2-2 7-7Z"/>',
        'logout'      => '<path d="M9 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h3m7-5 3-3-3-3M9 12h11"/>',
        'info'        => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/>',
        'eye'         => '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>',
        'pin'         => '<path d="M9 3h6l-1 6 3 3v2H7v-2l3-3-1-6Zm3 11v7"/>',
    ];
    $path = $icons[$name] ?? '<circle cx="12" cy="12" r="2"/>';
@endphp

<svg {{ $attributes->merge(['class' => 'icon']) }} width="{{ $size }}" height="{{ $size }}"
     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $stroke }}"
     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $path !!}
</svg>
