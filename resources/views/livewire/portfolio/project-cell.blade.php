<article class="m-1 px-5 py-6 border border-divider rounded-lg">
    <div class="flex justify-between items-center gap-2">
        <flux:heading class="text-xl mb-0.5!">{{ $project->title }}</flux:heading>
        @if($project->repo_link != null)
            <a href="{{ $project->repo_link }}" target="_blank">
                <img src="{{ asset('/images/brands/github-mark.png') }}" alt="GitHub Icon" class="h-6 dark:hidden" />
                <img src="{{ asset('/images/brands/github-mark-white.png') }}" alt="GitHub Icon" class="h-6 hidden dark:block" />
            </a>
        @else
            <flux:text size="sm" class="uppercase text-zinc-500">Closed source</flux:text>
        @endif
    </div>
    <flux:subheading size="md" class="mt-1.5 text-zinc-700 dark:text-zinc-50">{{ $project->summary }}</flux:subheading>
    <img src="{{ asset($project->getCoverImagePath()) }}" alt="Cover image" class="my-3 w-full"/>
    <flux:subheading size="md" class="mt-1.5 uppercase text-zinc-400">
        {{ str_replace(', ', ' | ', $project->tools) }}
    </flux:subheading>
</article>
