<div>
    <flux:heading size="xl">{{ __('Manage attachments') }}</flux:heading>
    <flux:subheading class="mt-2">{{ $subheading ?? '' }}</flux:subheading>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-2 mt-6 mb-6 space-y-6">
        @foreach($attachmentNames as $name)
            <livewire:attachments.attachment-cell :name="$name" :key="$name" :path="$path"/>
        @endforeach
    </div>

    <form wire:submit="add" class="flex gap-4">
        <flux:input wire:model="attachment" iconLeading="paper-clip" :label="__('File')" type="file"
                    onchange="attachmentSelected(this.value)" class="w-1/3" required/>
        <flux:input wire:model="attachmentName" :label="__('Name')" type="text" id="filename" class="w-1/3"
                    required/>
        <div class="flex flex-col justify-end">
            <flux:button iconLeading="plus" variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Add') }}</flux:button>
        </div>
    </form>

    <x-flash-success-error/>

    <script>
        let filenameField = document.getElementById('filename');
        function attachmentSelected(filepath) {
            // Update attachment name field with the uploaded file's name
            filenameField.value = filepath.split('\\').pop().split('/').pop();
            // Dispatch input event, as otherwise Livewire does not read the new value
            filenameField.dispatchEvent(new Event('input'));
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text.replaceAll(' ', '%20'));
        }
    </script>
</div>
