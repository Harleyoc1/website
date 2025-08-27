<flux:button iconLeading="trash" title="Delete" variant="danger" wire:click="delete" wire:confirm="Are you sure you want to delete this project?" class="hover:cursor-pointer">
    {{ $text ? 'Delete' : '' }}
</flux:button>
