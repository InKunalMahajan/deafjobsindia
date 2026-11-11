@props(['padded' => true])
<div {{ $attributes->class(['rounded-xl border border-neutral-200 bg-white', 'p-6' => $padded]) }}>{{ $slot }}</div>
