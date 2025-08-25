<div class="mb-0 flex justify-between items-center gap-2">
    <flux:heading class="overflow-scroll">{{ $name }}</flux:heading>
    <div class="flex gap-2">
        @php($editModalName = "edit-$name")
        <flux:button iconLeading="clipboard-document-list" title="Copy Path" class="hover:cursor-pointer" onclick="copyToClipboard('{{ $path . $name  }}')"/>
        <flux:modal.trigger name="{{ $editModalName }}">
            <flux:button iconLeading="pencil" title="Edit" class="hover:cursor-pointer" x-on:click="$wire.showEditModal = true"/>
        </flux:modal.trigger>
        <flux:button iconLeading="trash" title="Delete" variant="danger" wire:click="delete"
                     wire:confirm="Are you sure you want to delete this attachment?" class="hover:cursor-pointer"/>

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
</div>
