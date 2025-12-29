<div class="flex justify-center h-full">
    <div class="sm:mt-5 sm:mx-2 md:mt-10 md:mx-5 py-6 md:py-8 px-5 md:px-10 lg:mx-10 xl:mx-auto w-full max-w-6xl
                relative rounded-md border bg-zinc-50 dark:bg-zinc-900 border-zinc-200 dark:border-zinc-600">
        <table class="w-full border-spacing-x-4 border-separate">
            <thead class="border-b-zinc-200 border-b">
            <tr class="[&>*]:border-r [&>*]:border-r-zinc-200 [&>*]:last:border-r-0">
                <th></th>
                <th>Name</th>
                <th>Date Modified</th>
                <th>Hash</th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $file)
                <tr>
                    <td><flux:icon name="{{ $file['hash'] == '-' ? 'folder' : 'document'}}"/></td>
                    <td>{{ $file['name'] }}</td>
                    <td class="text-right">{{ $file['modified']->format('H:i d/m/Y')  }}</td>
                    <td>{{ $file['hash'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
