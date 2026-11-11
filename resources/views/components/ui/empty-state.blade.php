@props(['title' => 'Nothing to show yet', 'description' => null])
<div {{ $attributes->class(['rounded-xl border border-dashed border-neutral-300 bg-neutral-50 p-10 text-center']) }}>
    <h3 class="text-lg font-bold">{{ $title }}</h3>
    @if($description)<p class="mx-auto mt-3 max-w-lg text-sm leading-6 text-neutral-600">{{ $description }}</p>@endif
    @if(trim((string) $slot) !== '')<div class="mt-5">{{ $slot }}</div>@endif
</div>
