@props(['href' => null, 'variant' => 'primary', 'type' => 'button'])
@php
    $classes = 'inline-flex min-h-11 items-center justify-center rounded-lg border px-5 py-2.5 text-sm font-semibold transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black';
    $classes .= match($variant) {
        'secondary' => ' border-neutral-300 bg-white text-neutral-900 hover:bg-neutral-100',
        'ghost' => ' border-transparent bg-transparent text-neutral-900 hover:bg-neutral-100',
        default => ' border-neutral-900 bg-neutral-900 text-white hover:bg-neutral-700',
    };
@endphp
@if($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$classes]) }}>{{ $slot }}</button>
@endif
