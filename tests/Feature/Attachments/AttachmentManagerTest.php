<?php

namespace Tests\Feature\Attachments;

use App\Attachments\BufferedAttachmentWriter;
use App\Attachments\DirectAttachmentWriter;
use App\Livewire\Attachments\AttachmentCell;
use App\Livewire\Attachments\AttachmentManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class AttachmentManagerTest extends TestCase
{

    private function uploadAttachment(string $name, UploadedFile $file): TemporaryUploadedFile
    {
        return Livewire::test(AttachmentCell::class, ['name' => $name])
            ->set('file', $file)
            ->instance()->file;
    }

    public function test_no_cells_in_empty_directory_with_direct_writer(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->assertDontSeeLivewire(AttachmentCell::class);
    }

    public function test_cells_initially_added_with_direct_writer(): void
    {
        Storage::fake('test');

        UploadedFile::fake()->create('file1.txt')->storeAs('attachments', 'file1.txt', 'test');
        UploadedFile::fake()->create('file2.txt')->storeAs('attachments', 'file2.txt', 'test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->assertSeeLivewire(AttachmentCell::class)
            ->assertSee('file1.txt')
            ->assertSee('file2.txt');
    }

    public function test_add_writes_to_given_location_with_direct_writer(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->set('attachmentName', 'file.txt')
            ->set('attachment', UploadedFile::fake()->create('file.txt'))
            ->call('add');

        Storage::disk('test')->assertExists('attachments/file.txt');
    }

    public function test_edit_updates_filename_with_direct_writer(): void
    {
        Storage::fake('test');

        UploadedFile::fake()->create('file.txt')->storeAs('attachments', 'file.txt', 'test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->dispatch('editAttachment', 'file.txt', 'file2.txt', null);

        Storage::disk('test')->assertMissing('attachments/file.txt');
        Storage::disk('test')->assertExists('attachments/file2.txt');
    }

    public function test_edit_updates_file_contents_with_direct_writer(): void
    {
        Storage::fake('test');

        UploadedFile::fake()->createWithContent('file.txt', 'Initial content')
            ->storeAs('attachments', 'file.txt', 'test');

        $modifiedFile = $this->uploadAttachment('file.txt',
            UploadedFile::fake()->createWithContent('file.txt', 'New content'));

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->dispatch('editAttachment', 'file.txt', 'file.txt', $modifiedFile->serializeForLivewireResponse());

        $this->assertEquals('New content', Storage::disk('test')->get('attachments/file.txt'));
    }

    public function test_delete_removes_file_contents_with_direct_writer(): void
    {
        Storage::fake('test');

        UploadedFile::fake()->createWithContent('file.txt', 'Initial content')
            ->storeAs('attachments', 'file.txt', 'test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->dispatch('deleteAttachment', 'file.txt');

        Storage::disk('test')->assertMissing('attachments/file.txt');
    }

    public function test_no_cells_initially_with_buffered_writer(): void
    {
        Livewire::test(AttachmentManager::class, ['attachmentWriter' => new BufferedAttachmentWriter()])
            ->assertDontSeeLivewire(AttachmentCell::class);
    }

    public function test_cells_added_with_buffered_writer(): void
    {
        Livewire::test(AttachmentManager::class, ['attachmentWriter' => new BufferedAttachmentWriter()])
            ->set('attachmentName', 'file.txt')
            ->set('attachment', UploadedFile::fake()->create('file.txt'))
            ->call('add')
            ->assertSeeLivewire(AttachmentCell::class)
            ->assertSee('file.txt');
    }

    public function test_add_and_upload_writes_to_given_location_with_buffered_writer(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentManager::class, ['attachmentWriter' => new BufferedAttachmentWriter()])
            ->set('attachmentName', 'file.txt')
            ->set('attachment', UploadedFile::fake()->create('file.txt'))
            ->call('add')
            ->dispatch('uploadAttachments', 'test', 'attachments');

        Storage::disk('test')->assertExists('attachments/file.txt');
    }

    public function test_edit_updates_uploaded_filenames_with_buffered_writer(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentManager::class, ['attachmentWriter' => new BufferedAttachmentWriter()])
            ->set('attachmentName', 'file.txt')
            ->set('attachment', UploadedFile::fake()->create('file.txt'))
            ->call('add')
            ->dispatch('editAttachment', 'file.txt', 'file2.txt', null)
            ->dispatch('uploadAttachments', 'test', 'attachments');

        Storage::disk('test')->assertMissing('attachments/file.txt');
        Storage::disk('test')->assertExists('attachments/file2.txt');
    }

    public function test_edit_updates_uploaded_files_with_buffered_writer(): void
    {
        Storage::fake('test');

        $modifiedFile = $this->uploadAttachment('file.txt',
            UploadedFile::fake()->createWithContent('file.txt', 'New content'));

        Livewire::test(AttachmentManager::class, ['attachmentWriter' => new BufferedAttachmentWriter()])
            ->set('attachmentName', 'file.txt')
            ->set('attachment', UploadedFile::fake()->create('file.txt'))
            ->call('add')
            ->dispatch('editAttachment', 'file.txt', 'file.txt',
                $modifiedFile->serializeForLivewireResponse())
            ->dispatch('uploadAttachments', 'test', 'attachments');

        $this->assertEquals('New content', Storage::disk('test')->get('attachments/file.txt'));
    }

    public function test_delete_updates_uploaded_filenames_with_buffered_writer(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentManager::class, ['attachmentWriter' => new BufferedAttachmentWriter()])
            ->set('attachmentName', 'file1.txt')
            ->set('attachment', UploadedFile::fake()->create('file1.txt'))
            ->call('add')
            ->set('attachmentName', 'file2.txt')
            ->set('attachment', UploadedFile::fake()->create('file2.txt'))
            ->dispatch('deleteAttachment', 'file2.txt')
            ->dispatch('uploadAttachments', 'test', 'attachments');

        Storage::disk('test')->assertExists('attachments/file1.txt');
        Storage::disk('test')->assertMissing('attachments/file2.txt');
    }

    /** Writer-agnostic tests - it should not matter which writer we are using for these, since we just
     *  want to check errors are being handled correctly. */

    public function test_add_fails_when_name_taken(): void
    {
        Storage::fake('test');

        UploadedFile::fake()->create('file.txt')->storeAs('attachments', 'file.txt', 'test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->set('attachmentName', 'file.txt')
            ->set('attachment', UploadedFile::fake()->create('file.txt'))
            ->call('add')
            ->assertHasErrors('attachmentName');
    }

    public function test_edit_fails_when_name_not_found(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->dispatch('editAttachment', 'file.txt', 'file.txt', null)
            ->assertSessionHas('_flash.new.0', 'error');
    }

    public function test_edit_fails_when_new_name_taken(): void
    {
        Storage::fake('test');

        UploadedFile::fake()->create('file1.txt')->storeAs('attachments', 'file1.txt', 'test');
        UploadedFile::fake()->create('file2.txt')->storeAs('attachments', 'file2.txt', 'test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->dispatch('editAttachment', 'file1.txt', 'file2.txt', null)
            ->assertSessionHas('_flash.new.0', 'error');
    }

    public function test_delete_fails_when_name_not_found(): void
    {
        Storage::fake('test');

        Livewire::test(AttachmentManager::class, [
            'attachmentWriter' => new DirectAttachmentWriter('test', 'attachments')
        ])
            ->dispatch('deleteAttachment', 'file.txt')
            ->assertSessionHas('_flash.new.0', 'error');
    }

}
