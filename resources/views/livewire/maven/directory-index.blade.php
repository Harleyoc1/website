<flux:main>
    <div class="flex justify-center h-full">
        <div class="sm:mt-5 sm:mx-2 md:mt-10 md:mx-5 py-6 md:py-8 px-5 md:px-10 lg:mx-10 xl:mx-auto w-full max-w-6xl
                relative rounded-md border bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600 space-y-4">
            @if(!$isRoot)
                <flux:heading size="xl">Index of {{ $path }}</flux:heading>
                <flux:button href="{{ route('maven.directory-index', dirname($path)) }}" iconLeading="arrow-turn-left-up">
                    Parent Directory
                </flux:button>
            @else
                <flux:heading size="xl">Root index</flux:heading>
            @endif
            <table class="w-full border-separate inset">
                <colgroup>
                    <col span="1" class="w-[3%]">
                    <col span="1" class="w-[45%]">
                    <col span="1" class="w-[25%]">
                    <col span="1" class="w-[2%]">
                    <col span="1" class="w-[25%]">
                </colgroup>
                <thead class="border-b-zinc-200 border-b">
                <tr class="[&>*]:border-r border-zinc-200 [&>*]:first:border-r-0 [&>*]:last:border-r-0 [&>*]:border-b [&>*]:px-2 [&>*]:mb-1">
                    <th></th>
                    <th>Name</th>
                    <th>Modified</th>
                    <th>Size</th>
                    <th>Hash</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr class="[&>*]:px-2">
                        <td>
                            <flux:icon name="{{ $file->isDir() ? 'folder' : 'document'}}" class="size-6"/>
                        </td>
                        <td>
                            <a href="{{ route('maven.directory-index', $path . '/' . $file->getName()) }}" class="underline">
                                {{ $file->getName() }}
                            </a>
                        </td>
                        <td class="text-right text-zinc-500">{{ $file->getModified()->format('j M Y \\a\\t H:i')  }}</td>
                        <td class="text-right text-zinc-500">{{ $file->getSizeFormatted() }}</td>
                        <td class="text-zinc-500 flex items-center @if($file->getHash() == '-') justify-center @else justify-end @endif" title="{{ $file->getHash() }}">
                            {{ strlen($file->getHash()) > 15 ? substr($file->getHash(), 0, 15) . '...' : $file->getHash() }}
                            @if($file->getHash() != '-')
                                <flux:icon name="clipboard" class="size-5 ml-1" onclick="copyToClipboard('{{ $file->getHash() }}')"/>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</flux:main>
