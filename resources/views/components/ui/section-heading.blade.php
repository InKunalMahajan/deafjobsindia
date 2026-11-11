@props(['eyebrow' => null, 'title', 'description' => null, 'level' => 2])
<div {{ $attributes }}>
    @if($eyebrow)<p class="text-xs font-bold uppercase tracking-[.16em] text-neutral-600">{{ $eyebrow }}</p>@endif
    @if((int) $level === 1)
    <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">{{ $title }}</h1>
    @else
    <h2 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">{{ $title }}</h2>
    @endif
    @if($description)<p class="mt-3 max-w-2xl leading-7 text-neutral-600">{{ $description }}</p>@endif
</div>
