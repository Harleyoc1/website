<div>
    <flux:heading size="xl">{{ __('Manage attachments') }}</flux:heading>
    <flux:subheading class="mt-2">{{ $subheading ?? '' }}</flux:subheading>

    <div class="mt-6 mb-6 space-y-6">
        @foreach($attachmentWriter->getNames() as $name)
            <div class="flex gap-4">
                <div class="flex flex-col justify-center">
                    <flux:heading>{{ $name }}</flux:heading>
                </div>
                <div class="flex gap-2">
                    <flux:button class="hover:cursor-pointer">{{ __('Edit') }}</flux:button>
                    <flux:button variant="danger" class="hover:cursor-pointer">{{ __('Delete') }}</flux:button>
                </div>
            </div>
        @endforeach
    </div>

    <form wire:submit="addAttachment" class="flex gap-4">
        <flux:input wire:model="attachment" :label="__('File')" type="file" onchange="attachmentAdded(this.value)"
                    class="w-1/3" required/>
        <flux:input wire:model="attachmentName" :label="__('Name')" type="text" id="filename" class="w-1/3"
                    required/>
        <div class="flex flex-col justify-end">
            <flux:button variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Add') }}</flux:button>
        </div>
    </form>

    <script>
        let filenameField = document.getElementById('filename');
        function attachmentAdded(filepath) {
            // Update attachment name field with the uploaded file's name
            filenameField.value = filepath.split('\\').pop().split('/').pop();
            // Dispatch input event, as otherwise Livewire does not read the new value
            filenameField.dispatchEvent(new Event('input'));
        }
    </script>
</div>
