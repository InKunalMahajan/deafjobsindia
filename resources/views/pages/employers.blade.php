<x-layouts.site title="Verified employers">
<section class="dji-container py-16"><h1 class="text-4xl font-bold">Verified employers</h1>
<p class="mt-3 text-neutral-600">Employer records reviewed by DeafJobsIndia. Verify current details with the organisation.</p>
<div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
@forelse($employers as $employer)<x-ui.card><h2 class="text-xl font-bold"><a class="underline" href="{{ route('employers.show',$employer->slug) }}">{{ $employer->name }}</a></h2><p class="mt-3 text-sm text-neutral-600">{{ $employer->location }}</p><p class="mt-3 text-sm">{{ \Illuminate\Support\Str::limit($employer->description,160) }}</p></x-ui.card>
@empty<p>No verified employers available yet.</p>@endforelse
</div><div class="mt-7">{{ $employers->links() }}</div>
</section></x-layouts.site>
