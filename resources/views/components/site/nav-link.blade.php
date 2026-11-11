@props(['href', 'active' => false, 'mobile' => false])
<a href="{{ $href }}" @if($active) aria-current="page" @endif
   {{ $attributes->class([
       'transition-colors hover:text-black hover:underline underline-offset-4',
       'block rounded-lg px-3 py-2 hover:bg-neutral-100' => $mobile,
       'text-neutral-900 font-bold' => $active,
       'text-neutral-600' => !$active,
   ]) }}>{{ $slot }}</a>
