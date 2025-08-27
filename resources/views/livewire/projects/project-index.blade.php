<div>
    <div>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="xl">Manage projects</flux:heading>
            <div class="flex gap-2">
                <flux:button iconLeading="eye" href="#" class="hover:cursor-pointer">View</flux:button>
                <flux:button iconLeading="plus" variant="primary" href="{{ route('management.portfolio.create') }}" class="hover:cursor-pointer">Add</flux:button>
            </div>
        </div>
        <div class="space-y-6">
            @foreach($projects as $project)
                <livewire:projects.project-cell :project="$project" />
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
