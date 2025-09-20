<div class="flex justify-center h-full">
    <div class="flex flex-col lg:block mt-10 w-full max-w-6xl
                    relative mx-5 lg:mx-10 xl:mx-auto px-10 py-8 rounded-md border
                    bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
        @isAdmin
        <div id="admin-panel" class="flex justify-between items-center pb-1 mb-3 w-full border-b border-b-divider">
            <flux:subheading size="sm" class="text-zinc-400 uppercase">Admin</flux:subheading>
            <div class="flex gap-1">
                <flux:button iconLeading="eye-slash" class="h-5 text-sm" onclick="hideAdminPanel()">Hide</flux:button>
                <flux:button iconLeading="pencil" class="h-5 text-sm" href="{{ route('management.portfolio.index') }}">Edit</flux:button>
                <flux:button iconLeading="plus" class="h-5 text-sm" href="{{ route('management.portfolio.create') }}">Add</flux:button>
            </div>
            <script>
                function hideAdminPanel() {
                    document.getElementById('admin-panel').remove();
                }
            </script>
        </div>
        @endisAdmin
        <flux:heading size="xl" class="pb-4">Portfolio</flux:heading>
        <div class="project-container">
            <div class="masonry-sizer w-[50%]! md:w-[33.3333%]!"></div>
            @foreach($projects as $project)
                <div @class(['project', 'w-[50%]! md:w-[33.3333%]!' => !$project->standout, 'w-full! md:w-[66.6666%]!' => $project->standout])>
                    <article class="m-1 px-5 py-6 border border-divider rounded-lg">
                        <div class="flex justify-between items-center gap-2">
                            <flux:heading class="text-xl mb-0.5!">{{ $project->title }}</flux:heading>
                            @if($project->repo_link != null)
                                <a href="{{ $project->repo_link }}" target="_blank">
                                    <img src="/images/brands/github-mark.png" alt="GitHub Icon" class="h-6 dark:hidden" />
                                    <img src="/images/brands/github-mark-white.png" alt="GitHub Icon" class="h-6 hidden dark:block" />
                                </a>
                            @else
                                <flux:text size="sm" class="uppercase text-zinc-500">Closed source</flux:text>
                            @endif
                        </div>
                        <flux:subheading size="md" class="mt-1.5 text-zinc-700 dark:text-zinc-50">{{ $project->summary }}</flux:subheading>
                        <img src="{{ $project->getCoverImagePath() }}" alt="Cover image" class="my-3 w-full"/>
                        <flux:subheading size="md" class="mt-1.5 uppercase text-zinc-400">
                            {{ str_replace(', ', ' | ', $project->tools) }}
                        </flux:subheading>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
    <script>
        let container = document.querySelector('.project-container');
        new Masonry(container, {
            itemSelector: '.project',
            columnWidth: '.masonry-sizer',
            percentPosition: true
        });
    </script>
</div>
