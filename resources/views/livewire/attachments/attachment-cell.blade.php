<div>
    @php($editModalName = "edit-$name")
    @php($deleteModalName = "confirm-delete-$name")

    <div class="flex gap-4">
        <div class="flex flex-col justify-center">
            <flux:heading>{{ $name }}</flux:heading>
        </div>
        <div class="flex gap-2">
            <flux:modal.trigger name="{{ $editModalName }}">
                <flux:button class="hover:cursor-pointer" x-on:click="$wire.showEditModal = true">
                    {{ __('Edit') }}
                </flux:button>
            </flux:modal.trigger>
            <flux:modal.trigger name="{{ $deleteModalName }}">
                <flux:button variant="danger" class="hover:cursor-pointer"
                             x-on:click.prevent="$dispatch('open-modal', '{{ $deleteModalName }}')">
                    {{ __('Delete') }}
                </flux:button>
            </flux:modal.trigger>
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

    <flux:modal name="{{ $deleteModalName }}" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="delete" class="space-y-6">
            <flux:heading size="lg">{{ __('Are you sure you want to delete attachment \'' . $name . '\'?') }}</flux:heading>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Confirm') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
