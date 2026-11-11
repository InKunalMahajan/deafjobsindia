<?php

namespace App\Console\Commands;

use App\Models\Article;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ImportNews extends Command
{
    protected $signature = 'news:import {--dry-run : Count eligible articles without writing}';
    protected $description = 'Import original Astro Markdown articles into the Laravel database';

    public function handle(): int
    {
        $count = 0;
        $files = glob(base_path('src/content/news/*.md')) ?: [];

        foreach ($files as $path) {
            $content = file_get_contents($path);
            if ($content === false || ! preg_match('/\A---\r?\n(.*?)\r?\n---\r?\n(.*)\z/s', $content, $parts)) {
                $this->warn("Skipping invalid front matter: {$path}");
                continue;
            }

            $metadata = Yaml::parse($parts[1]);

            if (! is_array($metadata)) {
                continue;
            }

            $slug = pathinfo($path, PATHINFO_FILENAME);
            if (str_ends_with($slug, '-format')
                || ! empty($metadata['demo'])
                || ! empty($metadata['draft'])
                || ($metadata['status'] ?? 'published') === 'draft') {
                continue;
            }

            $count++;

            if ($this->option('dry-run')) {
                continue;
            }

            Article::updateOrCreate(['slug' => $slug], [
                'title' => (string) ($metadata['title'] ?? Str::headline($slug)),
                'description' => (string) ($metadata['description'] ?? ''),
                'category' => (string) ($metadata['category'] ?? 'Community'),
                'body' => trim($parts[2]),
                'status' => (string) ($metadata['status'] ?? 'published'),
                'is_demo' => (bool) ($metadata['demo'] ?? false),
                'verification_status' => (string) ($metadata['verificationStatus'] ?? 'pending'),
                'verified_at' => $this->date($metadata['verifiedAt'] ?? null),
                'source_name' => $metadata['sourceName'] ?? null,
                'source_url' => $metadata['sourceUrl'] ?? null,
                'source_type' => $metadata['sourceType'] ?? null,
                'source_note' => $metadata['sourceNote'] ?? null,
                'action_label' => $metadata['actionLabel'] ?? null,
                'action_url' => $metadata['actionUrl'] ?? null,
                'deadline' => $this->date($metadata['deadline'] ?? null),
                'location' => $metadata['location'] ?? null,
                'image' => $metadata['image'] ?? null,
                'image_alt' => $metadata['imageAlt'] ?? null,
                'author' => $metadata['author'] ?? 'DeafJobsIndia Desk',
                'tags' => $metadata['tags'] ?? [],
                'is_featured' => (bool) ($metadata['featured'] ?? false),
                'is_trending' => (bool) ($metadata['trending'] ?? false),
                'is_breaking' => (bool) ($metadata['breaking'] ?? false),
                'published_at' => $this->date($metadata['publishedAt'] ?? null),
            ]);
        }

        $this->info(($this->option('dry-run') ? 'Eligible' : 'Imported or updated')." articles: {$count}");

        return self::SUCCESS;
    }

    private function date(mixed $value): ?CarbonImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return CarbonImmutable::instance($value);
        }

        if (is_int($value) || (is_string($value) && ctype_digit($value) && strlen($value) >= 10)) {
            return CarbonImmutable::createFromTimestamp((int) $value);
        }

        return CarbonImmutable::parse((string) $value);
    }
}
