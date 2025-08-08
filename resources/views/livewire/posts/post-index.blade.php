<div class="p-6 text-gray-900 h-full flex flex-col justify-between">
    <div>
        <div class="flex items-center justify-end mb-4">
            <flux:button href="{{ route('management.blog.create') }}" class="hover:cursor-pointer">Add</flux:button>
        </div>
        <div class="space-y-6">
            @foreach($posts as $post)
                <livewire:posts.post-cell :post="$post" />
            @endforeach
        </div>
    </div>
    <div class="flex flex-col items-center">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
