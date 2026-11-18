@props(['href', 'active' => false, 'mobile' => false])
<a href="{{ $href }}" @if($active) aria-current="page" @endif
   {{ $attributes->class([
       'inline-flex min-h-11 items-center rounded-lg px-3 py-2 text-sm font-semibold leading-6 transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black',
       'w-full justify-start' => $mobile,
       'hover:bg-neutral-100 hover:text-neutral-950' => !$active,
       'bg-neutral-950 text-white' => $active,
       'text-neutral-700' => !$active,
   ]) }}>{{ $slot }}</a>
