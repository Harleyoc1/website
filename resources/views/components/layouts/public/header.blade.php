<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <a href="{{ route('home') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <x-web-logo />
            </a>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="folder-git-2" href="#" :label="__('Portfolio')">
                    {{ __('Portfolio') }}
                </flux:navbar.item>
                <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="book-open-text" href="#" :label="__('Blog')">
                    {{ __('Blog') }}
                </flux:navbar.item>
            </flux:navbar>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
