<div>
    <div>
        <div class="flex items-center justify-between mb-4">
            <flux:heading size="xl">Projects</flux:heading>
            <flux:button href="{{ route('management.portfolio.create') }}" class="hover:cursor-pointer">Add</flux:button>
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
