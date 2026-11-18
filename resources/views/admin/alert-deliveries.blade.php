<x-layouts.admin title="Alert delivery monitoring">
<section class="w-full min-w-0">
<h2 class="text-xl font-bold">Job alert delivery monitoring</h2>
<p class="mt-3">Administrator view. Retry does not send an email immediately.</p>
@if(session('status'))<p role="status" class="mt-3">{{ session('status') }}</p>@endif
<div class="mt-6 flex flex-wrap gap-3">
<a class="min-h-11 rounded border border-neutral-500 px-3 py-2 underline" href="{{ route('admin.alert-deliveries.index') }}" @if(!$status) aria-current="page" @endif>All</a>
@foreach(['pending','failed','exhausted','sent','cancelled'] as $option)
<a class="min-h-11 rounded border border-neutral-500 px-3 py-2 underline" href="{{ route('admin.alert-deliveries.index',['status'=>$option]) }}" @if($status===$option) aria-current="page" @endif>{{ ucfirst($option) }} ({{ $counts[$option] ?? 0 }})</a>
@endforeach
</div>
<div class="mt-6 max-w-full overflow-x-auto"><table class="min-w-[44rem] w-full border-collapse text-left text-sm">
<thead><tr>
<th scope="col" class="border-b p-3">Delivery ID</th>
<th scope="col" class="border-b p-3">Alert ID</th>
<th scope="col" class="border-b p-3">Status</th>
<th scope="col" class="border-b p-3">Attempts</th>
<th scope="col" class="border-b p-3">Updated</th>
<th scope="col" class="border-b p-3">Action</th>
</tr></thead>
<tbody>
@forelse($deliveries as $delivery)
<tr>
<td class="border-b p-3">{{ $delivery->id }}</td>
<td class="border-b p-3">{{ $delivery->job_alert_id }}</td>
<td class="border-b p-3">{{ ucfirst($delivery->status) }}</td>
<td class="border-b p-3">{{ $delivery->attempts }}</td>
<td class="border-b p-3">{{ $delivery->updated_at }}</td>
<td class="border-b p-3">
@if(in_array($delivery->status,['failed','exhausted'],true))
<form method="POST" action="{{ route('admin.alert-deliveries.retry',$delivery->id) }}">
@csrf
<button class="min-h-11 rounded border border-black px-3" type="submit">Queue retry</button>
</form>
@else <span>—</span> @endif
</td>
</tr>
@empty<tr><td colspan="6" class="p-3">No delivery records found.</td></tr>@endforelse
</tbody></table></div>
<div class="mt-4">{{ $deliveries->links() }}</div>
</section></x-layouts.admin>
