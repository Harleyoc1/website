<div class="flex justify-center h-full">
    <div class="flex flex-col lg:block mt-10 w-full max-w-6xl
                    relative mx-5 lg:mx-10 xl:mx-auto px-10 py-8 rounded-md border
                    bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
        @isAdmin
        <div id="admin-panel" class="flex justify-between items-center pb-1 mb-3 w-full border-b border-b-divider">
            <flux:subheading size="sm" class="text-zinc-400 uppercase">Admin</flux:subheading>
            <div class="flex gap-1">
                <flux:button iconLeading="eye-slash" class="h-5 text-sm" onclick="hideAdminPanel()">Hide</flux:button>
                <flux:button iconLeading="pencil" class="h-5 text-sm" href="{{ route('management.blog.index') }}">Edit</flux:button>
                <flux:button iconLeading="plus" class="h-5 text-sm" href="{{ route('management.blog.create') }}">Add</flux:button>
            </div>
            <script>
                function hideAdminPanel() {
                    document.getElementById('admin-panel').remove();
                }
            </script>
        </div>
        @endisAdmin
        <flux:heading size="xl" class="pb-4">Blog</flux:heading>
        @foreach($posts as $post)
            <article class="px-2 py-3 border-b border-b-divider">
                <flux:heading class="text-xl mb-0.5!"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></flux:heading>
                <flux:subheading size="sm">Published on {{ $post->created_at->format('j F Y') }}</flux:subheading>
                <flux:subheading size="md" class="mt-1.5 text-zinc-700 dark:text-zinc-50">{{ $post->summary }}</flux:subheading>
            </article>
        @endforeach
    </div>
</div>
