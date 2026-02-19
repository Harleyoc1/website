@php use const App\Models\PERMISSION_LEVEL_DISPLAY_NAMES; @endphp
<article class="flex items-center justify-between border-b border-b-divider gap-4 py-4">
    <div>
        <flux:heading size="l">{{ $user->name }}</flux:heading>
        <flux:text class="text-zinc-600 dark:text-zinc-400">{{ $user->email }}</flux:text>
    </div>
    <div class="flex gap-4 items-center">
        @if (auth()->user() && $user->id == auth()->user()->id)
            <flux:text>(you)</flux:text>
        @else
            <flux:dropdown>
                <flux:button icon:trailing="chevron-down">{{ PERMISSION_LEVEL_DISPLAY_NAMES[$permissionLevel] }}</flux:button>
                <flux:menu>
                    <flux:menu.radio.group wire:model="permissionLevel" wire:change="updatePermissionLevel">
                        @for($i = 0; $i < sizeof(PERMISSION_LEVEL_DISPLAY_NAMES); $i++)
                            <flux:menu.radio value="{{ $i }}">{{ PERMISSION_LEVEL_DISPLAY_NAMES[$i] }}</flux:menu.radio>
                        @endfor
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>
            <flux:button wire:click="delete" wire:confirm="Are you sure you want to delete {{ $user->name }}?"
                         variant="danger" iconLeading="trash" class="hover:cursor-pointer"/>
        @endif
    </div>
</article>
