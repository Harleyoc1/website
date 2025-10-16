<div>
    <flux:heading size="xl">Edit project</flux:heading>

    <form wire:submit="update" class="my-6 w-full space-y-6">

        <flux:input wire:model="title" :label="__('Title')" type="text" autofocus />

        <flux:input wire:model="slug" :label="__('Slug')" type="text" />

        <flux:input wire:model="tools" :label="__('Tools')" type="text" />

        <flux:textarea wire:model="summary" :label="__('Summary')" />

        <div class="flex gap-4">
            <flux:input wire:model="coverImage" iconLeading="paper-clip" :label="__('Cover Image')" type="file" onchange="attachmentSelected(this.value)" />
            <div class="grow">
                <flux:input wire:model="coverImageFilename" id="filenameInput" :label="__('Filename')" type="text" />
            </div>
        </div>

        <img src="{{ asset($project->getCoverImagePath()) }}" alt="Cover image" class="w-50" />

        <flux:checkbox wire:model="openSource" :label="__('Open Source')"/>

        <div wire:show="openSource">
            <flux:input wire:model="repoLink" :label="__('Repository Link')" type="text" />
        </div>

        <div class="space-y-2">
            <flux:checkbox wire:model="standout" :label="__('Standout')"/>
            <flux:text>Standout projects are displayed on the home page and appear larger than standard projects on the portfolio page.</flux:text>
        </div>

        <div class="flex items-center justify-between gap-2">
            <div class="flex gap-2">
                <flux:button iconLeading="arrow-left" href="{{ route('management.portfolio.index') }}" class="hover:cursor-pointer">{{ __('Cancel') }}</flux:button>
                <flux:button iconLeading="eye" href="{{ route('portfolio.index') }}" class="hover:cursor-pointer">{{ __('View') }}</flux:button>
            </div>
            <div class="flex gap-2">
                <flux:button iconLeading="bookmark" variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Save') }}</flux:button>
                <livewire:projects.delete-project-button :project="$project" redirect-to="management.portfolio.index"/>
            </div>
        </div>
    </form>
    <x-flash-success-error/>

    <script>
        let filenameField = document.getElementById('filenameInput');
        function attachmentSelected(filepath) {
            // Update attachment name field with the uploaded file's name
            filenameField.value = filepath.split('\\').pop().split('/').pop();
            // Dispatch input event, as otherwise Livewire does not read the new value
            filenameField.dispatchEvent(new Event('input'));
        }
    </script>
</div>
