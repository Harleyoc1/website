<div>
    <flux:heading size="xl">Add project</flux:heading>

    <form wire:submit="store" class="my-6 w-full space-y-6">

        <flux:input wire:model="title" :label="__('Title')" type="text" autofocus />

        <flux:input wire:model="slug" :label="__('Slug')" type="text" />

        <flux:input wire:model="tools" :label="__('Tools')" type="text" />

        <flux:input wire:model="summary" :label="__('Summary')" type="text" />

        <div class="flex gap-4">
            <flux:input wire:model="coverImage" :label="__('Cover Image')" type="file" onchange="attachmentSelected(this.value)" />
            <div class="grow">
                <flux:input wire:model="coverImageFilename" id="filenameInput" :label="__('Filename')" type="text" />
            </div>
        </div>

        <flux:checkbox wire:model="openSource" :label="__('Open Source')"/>

        <div wire:show="openSource">
            <flux:input wire:model="repoLink" :label="__('Repository Link')" type="text" />
        </div>

        <div class="space-y-2">
            <flux:checkbox wire:model="standout" :label="__('Standout')"/>
            <flux:text>Standout projects are displayed on the home page and appear larger than standard projects on the portfolio page.</flux:text>
        </div>

        <div class="flex items-center justify-between gap-2">
            <flux:button iconLeading="arrow-left" href="{{ route('management.portfolio.index') }}" class="hover:cursor-pointer">{{ __('Cancel') }}</flux:button>
            <flux:button iconLeading="plus" variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Add') }}</flux:button>
        </div>
    </form>

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
