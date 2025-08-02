<?php

namespace Tests\Unit\Attachments;

use App\Attachments\DirectAttachmentWriter;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tests\TestCase;

class DirectAttachmentWriterTest extends TestCase
{

    private function createFile(string $content): File
    {
        return TemporaryUploadedFile::fake()->createWithContent('placeholder', $content);
    }

    private function createTestingWriter(int $attachments): DirectAttachmentWriter {
        Storage::fake('test');
        $writer = new DirectAttachmentWriter('test', 'attachments');
        for ($i = 0; $i < $attachments; $i++) {
            $this->assertTrue($writer->add("file$i.txt", $this->createFile("file contents $i")));
        }
        return $writer;
    }

    public function test_add_attachment_updates_names_array(): void
    {
        $writer = $this->createTestingWriter(2);

        $this->assertEquals(['file0.txt', 'file1.txt'], $writer->getNames());
    }

    public function test_add_attachment_writes_files_correctly(): void
    {
        $this->createTestingWriter(2);

        $disk = Storage::disk('test');
        $disk->assertExists('attachments/file0.txt');
        $disk->assertExists('attachments/file1.txt');
        $this->assertEquals('file contents 0', $disk->get('attachments/file0.txt'));
        $this->assertEquals('file contents 1', $disk->get('attachments/file1.txt'));
    }

    public function test_add_attachment_returns_false_if_name_taken(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->add('file0.txt', '');
        $this->assertFalse($result);
    }

    public function test_edit_attachment_updates_contents(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->edit('file0.txt', 'file0.txt', $this->createFile('the new contents'));

        $this->assertTrue($result);
        $this->assertEquals('the new contents', Storage::disk('test')->get('attachments/file0.txt'));
    }

    public function test_edit_attachment_updates_name(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->edit('file0.txt', 'file1.txt', $this->createFile(''));

        $this->assertTrue($result);
        $this->assertEquals(['file1.txt'], $writer->getNames());
    }

    public function test_edit_attachment_updates_contents_and_name(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->edit('file0.txt', 'file1.txt', $this->createFile('the new contents'));

        $this->assertTrue($result);
        $disk = Storage::disk('test');
        $disk->assertMissing('attachments/file0.txt');
        $disk->assertExists('attachments/file1.txt');
        $this->assertEquals('the new contents', $disk->get('attachments/file1.txt'));

        $this->assertEquals(['file1.txt'], $writer->getNames());
    }

    public function test_edit_attachment_returns_false_if_name_not_found(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->edit('file1.txt', '', '');

        $this->assertFalse($result);
    }

    public function test_edit_attachment_returns_false_if_new_name_taken(): void
    {
        $writer = $this->createTestingWriter(2);

        $result = $writer->edit('file0.txt', 'file1.txt', '');

        $this->assertFalse($result);
        // Quick check for side effects
        $disk = Storage::disk('test');
        $disk->assertCount('attachments', 2);
        $this->assertEquals('file contents 0', $disk->get('attachments/file0.txt'));
        $this->assertEquals('file contents 1', $disk->get('attachments/file1.txt'));
    }

    public function test_rename_attachment(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->rename('file0.txt', 'file1.txt');

        $this->assertTrue($result);
        $disk = Storage::disk('test');
        $disk->assertCount('attachments', 1);
        $disk->assertMissing('attachments/file0.txt');
        $disk->assertExists('attachments/file1.txt');
        $this->assertEquals('file contents 0', $disk->get('attachments/file1.txt'));
    }

    public function test_rename_attachment_returns_false_if_name_not_found(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->rename('file1.txt', '');

        $this->assertFalse($result);
    }

    public function test_rename_attachment_returns_false_if_new_name_taken(): void
    {
        $writer = $this->createTestingWriter(2);

        $result = $writer->rename('file0.txt', 'file1.txt');

        $this->assertFalse($result);
        // Quick check for side effects
        Storage::disk('test')->assertCount('attachments', 2);
    }

    public function test_remove_attachment(): void
    {
        $writer = $this->createTestingWriter(2);

        $result = $writer->remove('file0.txt');

        $this->assertTrue($result);
        $disk = Storage::disk('test');
        $disk->assertMissing('attachments/file0.txt');
        $disk->assertExists('attachments/file1.txt');
        $this->assertEquals(['file1.txt'], $writer->getNames());
    }

    public function test_remove_attachment_returns_false_if_name_not_found(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->remove('file1.txt');

        $this->assertFalse($result);
        // Quick check for side effects
        $this->assertCount(1, $writer->getNames());
        Storage::disk('test')->assertCount('attachments', 1);
    }

    public function test_has_attachment(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->has('file0.txt');

        $this->assertTrue($result);
    }

    public function test_has_attachment_returns_false_if_name_not_found(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->has('file1.txt');

        $this->assertFalse($result);
    }

    public function test_get_names_returns_correct_names(): void
    {
        $writer = $this->createTestingWriter(3);

        $result = $writer->getNames();

        $this->assertEquals(['file0.txt', 'file1.txt', 'file2.txt'], $result);
    }

    public function test_to_livewire_returns_properties_array(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->toLivewire();

        $this->assertEquals(['disk' => 'test', 'path' => 'attachments'], $result);
    }

    public function test_from_livewire_returns_correct_object(): void
    {
        Storage::fake('test');
        $properties = ['disk' => 'test', 'path' => 'attachments'];

        $writer = DirectAttachmentWriter::fromLivewire($properties);

        $this->assertEquals('test', $this->reflectProperty($writer, 'disk'));
        $this->assertEquals('attachments', $this->reflectProperty($writer, 'path'));
    }

}
