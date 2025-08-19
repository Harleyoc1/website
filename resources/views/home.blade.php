<x-layouts.public.page :title="__('Harley O\'Connor')" :mainClass="__('p-0!')">
    <div class="flex justify-center h-full">
        <div class="flex flex-col lg:block mt-10 md:mt-20 lg:mt-30 w-full max-w-6xl
                    relative mx-5 lg:mx-10 xl:mx-auto px-12 pt-15 rounded-t-md border-t border-x
                    bg-zinc-100 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
            <div class="hidden lg:flex items-end float-right h-full profile-cutout-shape w-128">
                <img src="/images/profile-cutout.png" />
            </div>
            <flux:heading level="1" size="xl" class="font-mono font-bold! text-6xl lg:text-7xl">Harley <span
                    class="font-light tracking-tighter">O'Connor</span></flux:heading>
            <flux:text size="xl" class="mt-4 lg:mt-6 text-lg lg:text-xl! text-zinc-600 dark:text-zinc-300">A computer science graduate currently seeking work in the technology
                sector, in Software Engineering or related roles.
            </flux:text>
            <li class="list-none mt-3 lg:mt-5 text-zinc-700 dark:text-zinc-200">
                <ul>
                    <span class="inline-flex">
                        <flux:icon.arrow-turn-down-right class="mr-3" variant="solid"/>
                        <span>View my <a href="#" class="inline-flex items-center text-accent"> <span
                                    class="underline">Portfolio</span> <flux:icon.arrow-up-right class="size-5 ml-1"/></a></span>
                    </span>
                </ul>
                <ul class="mt-2">
                    <span class="inline-flex">
                        <flux:icon.arrow-turn-down-right class="mr-3" variant="solid"/>
                        <span>Read my <a href="#" class="inline-flex items-center text-accent"> <span class="underline">Blog</span> <flux:icon.arrow-up-right class="size-5 ml-1"/></a></span>
                    </span>
                </ul>
            </li>
            <div class="flex gap-3 mt-4 items-center">
                <a href="https://github.com/Harleyoc1" target="_blank">
                    <img src="/images/brands/github-mark.png" alt="GitHub Icon" class="h-6 dark:hidden" />
                    <img src="/images/brands/github-mark-white.png" alt="GitHub Icon" class="h-6 hidden dark:block" />
                </a>
                <a href="https://linkedin.com/in/harleyoconnor/" target="_blank"><img src="/images/brands/LI-In-Bug.png" alt="LinkedIn Icon" class="h-6" /></a>
            </div>
            <div class="flex justify-center items-end h-full lg:hidden">
                <img src="/images/profile-cutout.png" class="w-128" />
            </div>
        </div>
    </div>
</x-layouts.public.page>
