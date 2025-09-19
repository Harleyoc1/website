<article class="flex items-center justify-between border-b border-b-divider gap-4 py-4">
    <div>
        <flux:heading size="l">{{ $user->name }}</flux:heading>
        <flux:text class="text-zinc-600 dark:text-zinc-400">{{ $user->email }}</flux:text>
    </div>
    <div class="flex gap-4 items-center">
        @if (auth()->user() && $user->id == auth()->user()->id)
            <flux:checkbox disabled checked label="Admin"/>
            <flux:button disabled variant="danger" iconLeading="trash"/>
        @else
            <flux:checkbox wire:model="isAdmin" label="Admin" wire:change="toggleIsAdmin"/>
            <flux:button wire:click="delete" wire:confirm="Are you sure you want to delete {{ $user->name }}?" variant="danger" iconLeading="trash" class="hover:cursor-pointer"/>
        @endif
    </div>
</article>
