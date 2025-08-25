<div>
    @php($editModalName = "edit-$name")

    <div class="flex gap-4">
        <div class="flex flex-col justify-center">
            <flux:heading>{{ $name }}</flux:heading>
        </div>
        <div class="flex gap-2">
            <flux:button iconLeading="clipboard-document-list" class="hover:cursor-pointer" onclick="copyToClipboard('{{ $path . $name  }}')">
                {{ __('Copy Link') }}
            </flux:button>
            <flux:modal.trigger name="{{ $editModalName }}">
                <flux:button iconLeading="pencil" class="hover:cursor-pointer" x-on:click="$wire.showEditModal = true">
                    {{ __('Edit') }}
                </flux:button>
            </flux:modal.trigger>
            <flux:button iconLeading="trash" title="Delete" variant="danger" wire:click="delete" wire:confirm="Are you sure you want to delete this attachment?">Delete</flux:button>
        </div>
    </div>

    <flux:modal name="{{ $editModalName }}" wire:model.self="showEditModal" focusable class="max-w-lg">
        <form wire:submit="edit" class="space-y-6">
            <flux:heading size="lg">{{ __("Edit $name") }}</flux:heading>

            <flux:input wire:model="file" :label="__('File')" type="file"/>
            <flux:input wire:model="newName" value="{{ $name }}" :label="__('Name')" type="text" required/>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button>{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
