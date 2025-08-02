<div>
    <flux:heading size="xl">{{ __('Manage attachments') }}</flux:heading>
    <flux:subheading class="mt-2">{{ $subheading ?? '' }}</flux:subheading>

    <div class="mt-6 mb-6 space-y-6">
        @foreach($attachmentNames as $name)
            <livewire:attachments.attachment-cell :name="$name" :key="$name" :path="$path"/>
        @endforeach
    </div>

    <form wire:submit="add" class="flex gap-4">
        <flux:input wire:model="attachment" :label="__('File')" type="file" onchange="attachmentSelected(this.value)"
                    class="w-1/3" required/>
        <flux:input wire:model="attachmentName" :label="__('Name')" type="text" id="filename" class="w-1/3"
                    required/>
        <div class="flex flex-col justify-end">
            <flux:button variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Add') }}</flux:button>
        </div>
    </form>

    <div class="flex flex-col items-center mt-6">
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
