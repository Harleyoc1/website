<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:brand href="{{ route('home') }}" logo="/images/profile-picture.jpg" name="Harley O'Connor" />

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:navbar.item class="[&>div>svg]:size-5" icon="folder-git-2" href="#" :label="__('Portfolio')">
                    {{ __('Portfolio') }}
                </flux:navbar.item>
                <flux:navbar.item class="[&>div>svg]:size-5" icon="book-open-text" href="{{ route('blog.index') }}" :current="request()->routeIs('blog.*')" :label="__('Blog')">
                    {{ __('Blog') }}
                </flux:navbar.item>
            </flux:navbar>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
