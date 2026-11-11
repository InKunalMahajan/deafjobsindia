<x-layouts.site title="Design System">
    <div class="dji-container py-16">
        <p class="text-xs font-bold uppercase tracking-widest text-neutral-600">Stage 2 / Internal preview</p>
        <h1 class="mt-3 text-4xl font-extrabold tracking-tight">Design system</h1>
        <p class="mt-4 max-w-2xl leading-7 text-neutral-600">Monochrome tokens, components, accessibility and layout patterns.</p>
        <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach([['Ink','#111111'],['Muted','#525252'],['Border','#E5E5E5'],['Surface','#FAFAFA'],['Paper','#FFFFFF']] as [$name,$hex])
                <x-ui.card>
                    <div class="h-20 rounded-lg border border-neutral-200" style="background-color: {{ $hex }}"></div>
                    <p class="mt-4 font-bold">{{ $name }}</p>
                    <p class="text-sm text-neutral-600">{{ $hex }}</p>
                </x-ui.card>
            @endforeach
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-2">
            <x-ui.card>
                <h2 class="text-xl font-bold">Buttons</h2>
                <div class="mt-5 flex flex-wrap gap-3">
                    <x-ui.button>Primary</x-ui.button>
                    <x-ui.button variant="secondary">Secondary</x-ui.button>
                    <x-ui.button variant="ghost">Ghost</x-ui.button>
                </div>
            </x-ui.card>
            <x-ui.card>
                <h2 class="text-xl font-bold">Badges</h2>
                <div class="mt-5 flex flex-wrap gap-3">
                    <x-ui.badge>News</x-ui.badge>
                    <x-ui.badge variant="outline">Verified source</x-ui.badge>
                    <x-ui.badge variant="strong">Featured</x-ui.badge>
                </div>
            </x-ui.card>
            <x-ui.card>
                <h2 class="text-xl font-bold">Flux UI controls</h2>
                <div class="mt-5 space-y-4">
                    <flux:input label="Job title" placeholder="Search jobs" />
                    <flux:select label="Category">
                        <flux:select.option value="jobs">Jobs</flux:select.option>
                        <flux:select.option value="training">Training</flux:select.option>
                    </flux:select>
                </div>
            </x-ui.card>
            <x-ui.empty-state title="No opportunities found" description="Try another keyword or location.">
                <x-ui.button variant="secondary" :href="route('jobs.index')">Browse jobs</x-ui.button>
            </x-ui.empty-state>
        </div>
    </div>
</x-layouts.site>
