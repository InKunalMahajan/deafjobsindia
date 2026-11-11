@props(['variant' => 'neutral'])
@php
    $classes = match($variant) {
        'strong' => 'border-neutral-900 bg-neutral-900 text-white',
        'outline' => 'border-neutral-400 bg-white text-neutral-900',
        default => 'border-neutral-200 bg-neutral-100 text-neutral-700',
    };
@endphp
<span {{ $attributes->class(['inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold', $classes]) }}>{{ $slot }}</span>
