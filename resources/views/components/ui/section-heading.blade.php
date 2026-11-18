@props(['eyebrow' => null, 'title', 'description' => null, 'level' => 2])
@php
    $headingTag = (int) $level === 1 ? 'h1' : 'h2';
@endphp
<div {{ $attributes }}>
    @if($eyebrow)<p class="text-xs font-bold uppercase tracking-[.16em] text-neutral-600">{{ $eyebrow }}</p>@endif
    <{{ $headingTag }} class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">{{ $title }}</{{ $headingTag }}>
    @if($description)<p class="mt-3 max-w-2xl leading-7 text-neutral-600">{{ $description }}</p>@endif
</div>
