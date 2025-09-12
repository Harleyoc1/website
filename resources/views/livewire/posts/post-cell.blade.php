<article class="flex border-b border-b-divider pb-4 justify-between">
    <div>
         <h3 class="text-lg font-semibold dark:text-zinc-100">
            <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
        </h3>
        <p class="text-gray-600 dark:text-zinc-300">{{ $post->summary }}</p>
    </div>
    <div class="flex items-center justify-center flex-col-reverse">
        <div class="flex gap-2">
            <flux:button iconLeading="eye" title="View" href="{{ route('blog.show', $post->slug) }}" class="hover:cursor-pointer" />
            <flux:button iconLeading="pencil" title="Edit" href="{{ route('management.blog.edit', $post->slug) }}" class="hover:cursor-pointer" />
            <livewire:posts.delete-post-button :post="$post" redirect-to="management.blog.index" :text="false"/>
        </div>
    </div>
</article>
