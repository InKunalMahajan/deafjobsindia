<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0">
<channel>
    <title>DeafJobsIndia</title>
    <link>{{ url('/') }}</link>
    <description>Inclusive jobs and community updates for Deaf India</description>
    @foreach($articles as $article)
    <item>
        <title>{{ $article->title }}</title>
        <link>{{ route('news.show', $article->slug) }}</link>
        <guid>{{ route('news.show', $article->slug) }}</guid>
        <description>{{ $article->description }}</description>
        <pubDate>{{ $article->published_at?->toRssString() }}</pubDate>
    </item>
    @endforeach
</channel>
</rss>
