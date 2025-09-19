<div>
    <div class="flex justify-between">
        <flux:heading size="xl">Manage users</flux:heading>
        <flux:modal.trigger name="addUser">
            <flux:button variant="primary" iconLeading="plus" class="hover:cursor-pointer" x-on:click="$wire.showAddUserModel = true">Add</flux:button>
        </flux:modal.trigger>
    </div>
    <div class="mt-4">
        @foreach($users as $user)
            <livewire:users.user-cell :user="$user"/>
        @endforeach
    </div>
    <flux:modal name="addUser" wire:model.self="showAddUserModel" focusable class="max-w-2xl">
        <form wire:submit="add" class="space-y-6">
            <flux:heading size="lg">{{ __("Add user") }}</flux:heading>

            <flux:input wire:model="newUserEmail" :label="__('Email')" type="email"/>

            <flux:checkbox wire:model="newUserIsAdmin" :label="__('Admin')"/>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button>{{ __('Cancel') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" type="submit">{{ __('Send register link') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
