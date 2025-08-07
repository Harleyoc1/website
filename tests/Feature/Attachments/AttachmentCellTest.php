<?php

namespace Tests\Feature\Attachments;

use App\Livewire\Attachments\AttachmentCell;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AttachmentCellTest extends TestCase
{
    public function test_edit_dispatches_event_without_file(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentCell::class, ['name' => 'Test name'])
            ->set('newName', 'New test name')
            ->call('edit')
            ->assertDispatched('editAttachment', oldName: 'Test name', newName: 'New test name', serializedFile: null);
    }

    public function test_edit_dispatches_event_with_file(): void
    {
        Storage::fake('test');

        $file = UploadedFile::fake()->create('test.txt');

        $test = Livewire::test(AttachmentCell::class, ['name' => 'Test name'])
            ->set('newName', 'New test name')
            ->set('file', $file);

        // Grab uploaded file before its reset on edit
        $serializedFile = $test->instance()->file->serializeForLivewireResponse();

        $test
            ->call('edit')
            ->assertDispatched('editAttachment', oldName: 'Test name', newName: 'New test name',
                serializedFile: $serializedFile);
    }

    public function test_delete_dispatches_event(): void
    {
        Livewire::test(AttachmentCell::class, ['name' => 'Test name'])
            ->call('delete')
            ->assertDispatched('deleteAttachment', name: 'Test name');
    }
}
