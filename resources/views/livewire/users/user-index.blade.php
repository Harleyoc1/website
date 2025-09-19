<div>
    <div class="flex justify-between gap-2">
        <flux:heading size="xl">Manage users</flux:heading>
        <flux:modal.trigger name="addUser">
            <flux:button variant="primary" iconLeading="plus" class="hover:cursor-pointer"
                         x-on:click="$wire.showAddUserModel = true">
                Add
            </flux:button>
        </flux:modal.trigger>
    </div>
    <div class="mt-4">
        @foreach($users as $user)
            <livewire:users.user-cell :user="$user" wire:key="user-cell-{{ $user->id }}"/>
        @endforeach
    </div>
    <div class="mt-6 flex justify-center">
        @if (session()->has('success'))
            <flux:text class="py-2 px-3 rounded-lg bg-green-500 text-zinc-50 shadow-sm shadow-green-500">
                Registration link sent!
            </flux:text>
        @endif
    </div>
    <flux:modal name="addUser" wire:model.self="showAddUserModel" focusable class="max-w-2xl">
        <form wire:submit="add" class="space-y-6">
            <flux:heading size="lg">{{ __("Add user") }}</flux:heading>

            <flux:input wire:model="newUserEmail" :label="__('Email')" type="email"/>

            <flux:checkbox wire:model="newUserIsAdmin" :label="__('Admin')"/>

            <div class="flex space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button iconLeading="x-mark" class="hover:cursor-pointer">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button href="{{ route('management.users.register-email-preview') }}" target="_blank"
                             iconLeading="eye" class="hover:cursor-pointer">
                    {{ __('Preview email') }}
                </flux:button>
                <flux:button variant="primary" iconLeading="paper-airplane" class="hover:cursor-pointer" type="submit">
                    {{ __('Send register link') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</div>
