@props(['type' => 'button', 'variant' => 'primary', 'size' => ''])

@php
    $sizeClass = $size ? "btn-{$size}" : '';
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "btn btn-{$variant} {$sizeClass}"]) }}>
    {{ $slot }}
</button>
