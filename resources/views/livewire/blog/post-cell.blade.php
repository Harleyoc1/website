<article class="py-3 border-b border-b-divider">
    <flux:heading class="text-xl mb-0.5!"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></flux:heading>
    <flux:subheading size="sm">Published on {{ $post->created_at->format('j F Y') }}</flux:subheading>
    <flux:subheading size="md" class="mt-1.5 text-zinc-700 dark:text-zinc-50">{{ $post->summary }}</flux:subheading>
</article>

