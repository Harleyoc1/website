<div>
    <flux:heading size="xl">Create blog post</flux:heading>

    <form wire:submit="store" class="my-6 w-full space-y-6">

        <flux:input wire:model="title" :label="__('Title')" type="text" autofocus />

        <flux:input wire:model="slug" :label="__('Slug')" type="text" />

        <flux:input wire:model="summary" :label="__('Summary')" type="text" />

        <flux:textarea wire:model="content" :label="__('Content')" />

        <div class="flex items-center justify-start gap-2">
            <flux:button href="{{ route('management.blog.index') }}" class="hover:cursor-pointer">{{ __('Cancel') }}</flux:button>
            <flux:button variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Save') }}</flux:button>
        </div>
    </form>
</div>
