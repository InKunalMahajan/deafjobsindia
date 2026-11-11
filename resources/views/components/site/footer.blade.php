<footer class="border-t border-neutral-200 bg-neutral-50">
    <div class="dji-container flex flex-col justify-between gap-5 py-10 text-sm text-neutral-600 md:flex-row">
        <div>
            <p class="font-bold text-neutral-900">DeafJobsIndia.</p>
            <p class="mt-2">&copy; {{ date('Y') }} DeafJobsIndia. Inclusive careers for everyone.</p>
        </div>
        <nav aria-label="Footer navigation" class="flex flex-wrap items-start gap-5">
            <a class="hover:underline" href="{{ route('about') }}">About</a>
            <a class="hover:underline" href="{{ route('contact') }}">Contact</a>
            <a class="hover:underline" href="{{ route('privacy') }}">Privacy</a>
            <a class="hover:underline" href="{{ route('disclaimer') }}">Disclaimer</a>
            <a class="hover:underline" href="{{ route('rss') }}">RSS</a>
        </nav>
    </div>
</footer>
