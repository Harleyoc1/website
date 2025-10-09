<div>
    <flux:heading size="xl">Edit blog post</flux:heading>

    <form wire:submit="update" class="my-6 w-full space-y-6">

        <flux:input wire:model="title" :label="__('Title')" type="text" autofocus />

        <flux:input wire:model="slug" :label="__('Slug')" type="text" />

        <flux:input wire:model="summary" :label="__('Summary')" type="text" />

        <flux:textarea wire:model="content" :label="__('Content')" />

        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <flux:button iconLeading="arrow-left" href="{{ route('management.blog.index') }}" class="hover:cursor-pointer">{{ __('Cancel') }}</flux:button>
                <flux:button iconLeading="eye" href="{{ route('blog.show', $post->slug) }}" class="hover:cursor-pointer">{{ __('View') }}</flux:button>
            </div>
            <div class="flex gap-2">
                <flux:button iconLeading="bookmark" variant="primary" type="submit" class="hover:cursor-pointer">{{ __('Save') }}</flux:button>
                <livewire:posts.delete-post-button :post="$post" redirect-to="management.blog.index"/>
            </div>
        </div>
    </form>

    <livewire:attachments.attachment-manager :subheading="__('Attachments are modified directly.')"
        :attachment-writer="new \App\Attachments\DirectAttachmentWriter('blog', $post->getAttachmentsPath())"
        :path="'/blog-data/' . $post->id . '/attachments/'"/>

    <x-flash-success-error/>
</div>
