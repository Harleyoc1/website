<article class="flex border-b pb-4 justify-between">
    <div>
         <h3 class="text-lg font-semibold">
            <a href="" class="hover:underline">{{ $post->title }}</a>
        </h3>
        <p class="text-gray-600">{{ $post->summary }}</p>
    </div>
    <div class="flex items-center justify-center flex-col-reverse">
        <div class="flex gap-2">
            @php($modalName = 'confirm-post-' . $post->id . '-deletion')
            <flux:button href="#" class="hover:cursor-pointer">View</flux:button>
            <flux:button href="{{ route('management.blog.edit', $post->slug) }}" class="hover:cursor-pointer">Edit</flux:button>

            <flux:modal.trigger name="{{ $modalName }}">
                <flux:button variant="danger" x-data="" class="hover:cursor-pointer" x-on:click.prevent="$dispatch('open-modal', '{{ $modalName }}')">
                    {{ __('Delete') }}
                </flux:button>
            </flux:modal.trigger>

            <flux:modal name="{{ $modalName }}" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
                <form wire:submit="delete" class="space-y-6">
                    <flux:heading size="lg">{{ __('Are you sure you want to delete post \'' . $post->title . '\'?') }}</flux:heading>

                    <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                        <flux:modal.close>
                            <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
                        </flux:modal.close>

                        <flux:button variant="danger" type="submit">{{ __('Confirm') }}</flux:button>
                    </div>
                </form>
            </flux:modal>
        </div>
    </div>
</article>
