<div>
    <div>
        <div class="flex items-center justify-between gap-2 mb-4">
            <flux:heading size="xl">Manage projects</flux:heading>
            <div class="flex gap-2">
                <flux:button iconLeading="eye" href="{{ route('portfolio.index') }}" class="hover:cursor-pointer">View</flux:button>
                <flux:button iconLeading="bookmark" onclick="saveOrder()" id="saveOrderButton" class="hover:cursor-pointer" disabled>Save Order</flux:button>
                <flux:button iconLeading="plus" variant="primary" href="{{ route('management.portfolio.create') }}" class="hover:cursor-pointer">Add</flux:button>
            </div>
        </div>
        <div id="projects-container" class="space-y-6">
            @foreach($projects as $project)
                <livewire:projects.project-cell :project="$project" wire:key="project-cell-{{ $project->id }}" />
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
    <script src="https://SortableJS.github.io/Sortable/Sortable.js"></script>
    <script>
        let initialOrdering = getOrdering();

        function getOrdering() {
            return Array.from(document.getElementById('projects-container').children).map(function (projectElement) {
                return parseInt(projectElement.getAttribute('project-id'));
            });
        }

        function isInitialOrdering() {
            return JSON.stringify(initialOrdering) === JSON.stringify(getOrdering());
        }

        function saveOrder() {
            let ordering = getOrdering();
            Livewire.dispatch('update-project-order', { ordering: ordering });
            initialOrdering = ordering;
            document.getElementById('saveOrderButton').setAttribute('disabled', '');
        }

        Sortable.create(document.getElementById('projects-container'), {
            handle: '.handle',
            animation: 200,
            onEnd: function (_) {
                let element = document.getElementById('saveOrderButton');
                if (isInitialOrdering()) {
                    element.setAttribute('disabled', '');
                } else {
                    element.removeAttribute('disabled');
                }
            }
        });
    </script>
</div>
