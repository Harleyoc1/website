<div class="flex justify-center h-full">
    <div class="flex flex-col lg:block mt-10 w-full max-w-6xl
                    relative mx-5 lg:mx-10 xl:mx-auto px-10 pt-8 rounded-md border
                    bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
        <flux:heading size="xl">{{ $post->title }}</flux:heading>

        <div id="content"><!-- Content will be inserted here --></div>
        <script src="https://cdn.jsdelivr.net/npm/marked/lib/marked.umd.js"></script>
        <script>
            document.getElementById('content').innerHTML = marked.parse(`{{ $post->readContent()  }}`);
        </script>
    </div>
</div>
