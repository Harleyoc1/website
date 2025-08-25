<flux:button iconLeading="trash" title="Delete" variant="danger" wire:click="delete" wire:confirm="Are you sure you want to delete this post?">
    {{ $text ? 'Delete' : '' }}
</flux:button>
