<div>
    <div class="flex items-center justify-between gap-2 mb-4">
        <flux:heading size="xl">Manage blog posts</flux:heading>
        <div class="flex gap-2">
            <flux:button iconLeading="eye" href="{{ route('blog.index') }}" class="hover:cursor-pointer">View</flux:button>
            <flux:button iconLeading="plus" variant="primary" href="{{ route('management.blog.create') }}" class="hover:cursor-pointer">Add</flux:button>
        </div>
    </div>
    <div class="space-y-6">
        @foreach($posts as $post)
            <livewire:posts.post-cell :post="$post" />
        @endforeach
    </div>
    <x-flash-success-error/>
</div>
