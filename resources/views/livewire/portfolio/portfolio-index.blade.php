<div class="flex justify-center h-full">
    <div class="sm:mt-5 sm:mx-2 md:mt-10 md:mx-5 py-6 md:py-8 px-5 md:px-10 lg:mx-10 xl:mx-auto w-full max-w-6xl
                relative rounded-md border bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
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
        <div id="project-container">
            <div class="masonry-sizer w-[50%]! md:w-[33.3333%]!"></div>
            @foreach($projects as $project)
                <div @class(['project', 'w-full! sm:w-[50%]! md:w-[33.3333%]!' => !$project->standout, 'w-full! md:w-[66.6666%]!' => $project->standout])>
                    <livewire:portfolio.project-cell :project="$project"/>
                </div>
            @endforeach
        </div>
    </div>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
    <script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.js"></script>
    <script>
        let container = document.getElementById('project-container');
        let masonry = new Masonry(container, {
            itemSelector: '.project',
            columnWidth: '.masonry-sizer',
            percentPosition: true
        });

        // Prevents items overlapping due to unloaded images
        imagesLoaded(container, function () {
            masonry.layout();
        });
    </script>
</div>
