<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:brand href="{{ route('home') }}" logo="/images/profile-picture.jpg" name="Harley O'Connor" />

            <flux:spacer />

            <flux:heading size="xl" class="tracking-widest uppercase font-mono">Maven</flux:heading>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
