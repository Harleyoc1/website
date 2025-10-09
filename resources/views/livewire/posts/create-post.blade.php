<div>
    <flux:heading size="xl">Create blog post</flux:heading>

    <form wire:submit="store" class="my-6 w-full space-y-6">

        <flux:input wire:model="title" :label="__('Title')" type="text" autofocus />

        <flux:input wire:model="slug" :label="__('Slug')" type="text" />

        <flux:input wire:model="summary" :label="__('Summary')" type="text" />

        <flux:textarea wire:model="content" :label="__('Content')" />

        <div class="flex items-center justify-between gap-2">
            <flux:button iconLeading="arrow-left" href="{{ route('management.blog.index') }}" class="hover:cursor-pointer">{{ __('Cancel') }}</flux:button>
            <flux:button iconLeading="plus" variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Add') }}</flux:button>
        </div>
    </form>
    <livewire:attachments.attachment-manager :subheading="__('Attachments are not saved until the blog post is added.')"
        :attachment-writer="new \App\Attachments\BufferedAttachmentWriter()"
        :path="'/blog-data/' . $id . '/attachments/'" />
    <x-flash-success-error/>
</div>
