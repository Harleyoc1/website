<article class="flex border-b border-b-divider pb-4 gap-2 justify-between" project-id="{{ $project->id }}">
    <div class="flex gap-4">
        <img src="{{ asset($project->getCoverImagePath()) }}" alt="Cover image" class="h-20 drop-shadow-xl" />
        <div class="flex justify-center flex-col">
            <flux:subheading size="lg" class="font-semibold text-gray-800 dark:text-zinc-100">
                <a href="" class="hover:underline">{{ $project->title }}</a>
            </flux:subheading>
            <flux:text class="text-gray-600 dark:text-zinc-300">{{ $project->summary }}</flux:text>
        </div>
    </div>
    <div class="flex items-center justify-center flex-col-reverse">
        <div class="flex gap-2 items-center">
            <flux:icon icon="bars-3" class="mr-2 handle hover:cursor-pointer"></flux:icon>
            <flux:button iconLeading="pencil" href="{{ route('management.portfolio.edit', $project->slug) }}" class="hover:cursor-pointer"/>
            <livewire:projects.delete-project-button :project="$project" redirect-to="management.portfolio.index" :text="false"/>
        </div>
    </div>
</article>
