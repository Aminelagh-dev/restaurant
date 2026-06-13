@php
    $current = app()->getLocale();
    $supported = config('locales.supported', []);
@endphp

<div class="lang-switch" role="group" aria-label="{{ __('Changer de langue') }}">
    @foreach ($supported as $code => $meta)
        <a href="{{ route('locale.switch', $code) }}"
           class="lang-opt {{ $code === $current ? 'is-active' : '' }}"
           hreflang="{{ $code }}"
           @if ($code === $current) aria-current="true" @endif
           title="{{ $meta['native'] }}">{{ $meta['short'] }}</a>
    @endforeach
</div>
