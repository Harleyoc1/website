<div class="flex justify-center h-full">
    <div class="sm:mt-5 sm:mx-2 md:mt-10 md:mx-5 py-6 md:py-8 lg:py-12 lg:mx-10 xl:mx-auto w-full max-w-6xl
                relative rounded-md border bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
        <div class="px-5 md:px-10 lg:px-15 border-b border-divider h-auto md:h-120 lg:h-116">
            <img src="{{ asset('/images/profile-cutout.png') }}" alt="Cutout of Harley O'Connor"
                 class="hidden md:block float-right md:h-120 lg:h-116"
                 style="shape-margin: 4rem; shape-outside: url({{ asset('/images/profile-cutout-shape.png') }});" />
            <flux:heading level="1" size="xl" class="font-mono font-bold! text-4xl md:text-6xl lg:text-7xl">Harley <span
                    class="font-light tracking-tighter">O'Connor</span></flux:heading>
            <flux:text class="mt-4 lg:mt-6 text-base lg:text-lg! text-zinc-600 dark:text-zinc-300">
                I am a Software Engineer and recent graduate of the <a href="https://www.manchester.ac.uk" class="text-link" target="_blank">University of Manchester</a>
                in which I undertook a Computer Science BSc.
            </flux:text>
            <div class="flex gap-3 mt-4 items-center">
                <a href="https://github.com/Harleyoc1" target="_blank">
                    <img src="{{ asset('/images/brands/github-mark.png') }}" alt="GitHub Icon" class="h-6 dark:hidden" />
                    <img src="{{ asset('/images/brands/github-mark-white.png') }}" alt="GitHub Icon" class="h-6 hidden dark:block" />
                </a>
                <a href="https://linkedin.com/in/harleyoconnor/" target="_blank"><img src="{{ asset('/images/brands/LI-In-Bug.png') }}" alt="LinkedIn Icon" class="h-6" /></a>
            </div>
            <div class="flex justify-center items-end h-full md:hidden">
                <img src="{{ asset('/images/profile-cutout.png') }}" alt="Cutout of Harley O'Connor" class="w-64" />
            </div>
        </div>
        <div class="px-5 md:px-10 lg:px-15 border-b border-b-divider">
            <flux:heading level="2" size="xl" class="mt-8 md:text-3xl lg:text-4xl">Portfolio standouts</flux:heading>
            <flux:text class="text-sm lg:text-base text-zinc-500 dark:text-zinc-400">
                A couple of the standout projects I have worked on. For more view my <a href="{{ route('portfolio.index') }}" class="text-link">portfolio page</a>.
            </flux:text>
            <div class="my-4 space-y-4! [&>*]:mx-0! md:[&>*]:m-0! md:flex md:items-start md:gap-4 md:[&>*]:w-[50%]">
                @foreach($projects as $project)
                    <livewire:portfolio.project-cell :project="$project"/>
                @endforeach
            </div>
        </div>
        <div class="px-5 md:px-10 lg:px-15">
            <flux:heading level="2" size="xl" class="mt-8 md:text-3xl lg:text-4xl">Latest blog posts</flux:heading>
            <flux:text class="text-sm lg:text-base text-zinc-500 dark:text-zinc-400">
                My most recent blog posts. For more view my <a href="{{ route('blog.index') }}" class="text-link">blog page</a>.
            </flux:text>
            @foreach($posts as $post)
                <livewire:blog.post-cell :post="$post" wire:key="post-cell-{{ $post->id }}"/>
            @endforeach
        </div>
    </div>
</div>
