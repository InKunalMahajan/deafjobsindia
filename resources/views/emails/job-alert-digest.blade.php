<p>Hello {{ $recipientName }},</p>
<p>Here are new job matches from DeafJobsIndia:</p>
<ul>
@foreach($jobs as $job)
<li><a href="{{ $job['url'] }}">{{ $job['title'] }}</a> — {{ $job['location'] }}</li>
@endforeach
</ul>
<p>These matches are based on your saved search. Check job requirements and deadlines before applying.</p>
<p><a href="{{ $settingsUrl }}">Manage or unsubscribe from job alerts</a>.</p>
@if($unsubscribeUrl)
<p><a href="{{ $unsubscribeUrl }}">Stop emails for this alert</a> (link expires in 30 days).</p>
@endif
<p>DeafJobsIndia</p>
