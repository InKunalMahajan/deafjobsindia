<x-layouts.admin title="Dashboard">
<p class="mb-6 text-neutral-600">Signed in as {{ auth()->user()->name }} · {{ str_replace('_',' ',auth()->user()->role) }}</p>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
@foreach(['Employers'=>$employers,'Total jobs'=>$jobs,'Published jobs'=>$published,'Draft jobs'=>$drafts] as $label=>$value)
<div class="rounded-xl border border-neutral-200 p-6"><p class="text-sm text-neutral-600">{{ $label }}</p><p class="mt-2 text-3xl font-bold">{{ $value }}</p></div>
@endforeach
</div>
</x-layouts.admin>
