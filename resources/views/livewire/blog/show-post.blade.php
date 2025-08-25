<div class="flex justify-center h-full">
    <div class="flex flex-col lg:block mt-10 w-full max-w-6xl
                    relative mx-5 lg:mx-10 xl:mx-auto px-10 py-8 rounded-md border
                    bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
        @isAdmin
        <div id="admin-panel" class="flex justify-between items-center pb-1 mb-3 w-full border-b border-b-divider">
            <flux:subheading size="sm" class="text-zinc-400 uppercase">Admin</flux:subheading>
            <div class="flex gap-1">
                <flux:button iconLeading="eye-slash" class="h-5 text-sm" onclick="hideAdminPanel()">Hide</flux:button>
                <flux:button iconLeading="pencil" class="h-5 text-sm" href="{{ route('management.blog.edit', $post->slug) }}">Edit</flux:button>
                <livewire:posts.delete-post-button :post="$post" redirect-to="blog.index"/>
            </div>
            <script>
                function hideAdminPanel() {
                    document.getElementById('admin-panel').remove();
                }
            </script>
        </div>
        @endisAdmin

        <flux:heading class="text-4xl! font-bold!">{{ $post->title }}</flux:heading>
        <flux:subheading size="md" class="mb-3">Published on {{ $post->created_at->format('j F Y') }}</flux:subheading>
        <hr class="border-divider mb-3" />
        <div id="blog-body-container">
            {!! $post->readAsHtml() !!}
        </div>
    </div>
</div>
