<x-layouts.public.header :title="$title ?? null">
    <flux:main class="{{ $mainClass ?? '' }}">
        {{ $slot }}
    </flux:main>
</x-layouts.public.header>
