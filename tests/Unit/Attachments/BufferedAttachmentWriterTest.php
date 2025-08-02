<?php

namespace Tests\Unit\Attachments;

use App\Attachments\BufferedAttachmentWriter;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tests\TestCase;

class BufferedAttachmentWriterTest extends TestCase
{
    private function getAttachments(BufferedAttachmentWriter $writer): array {
        return $this->reflectProperty($writer, 'attachments');
    }

    private function createTestingWriter(int $attachments): BufferedAttachmentWriter {
        $writer = new BufferedAttachmentWriter();
        for ($i = 0; $i < $attachments; $i++) {
            $this->assertTrue($writer->add("file$i.txt", "file contents $i"));
        }
        return $writer;
    }

    private function createTestingWriterWithMockFiles(int $attachments): BufferedAttachmentWriter {
        $writer = new BufferedAttachmentWriter();
        for ($i = 0; $i < $attachments; $i++) {
            $file = TemporaryUploadedFile::fake()->createWithContent("file$i.txt", "file contents $i");
            $this->assertTrue($writer->add("file$i.txt", $file));
        }
        return $writer;
    }

    public function test_add_attachment(): void
    {
        $writer = $this->createTestingWriter(2);

        $attachments = $this->getAttachments($writer);
        $this->assertCount(2, $attachments);
        $this->assertArrayHasKey('file0.txt', $attachments);
        $this->assertEquals('file contents 0', $attachments['file0.txt']);
        $this->assertArrayHasKey('file1.txt', $attachments);
        $this->assertEquals('file contents 1', $attachments['file1.txt']);
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

        $result = $writer->edit('file0.txt', 'file0.txt', 'the new contents');

        $this->assertTrue($result);
        $attachments = $this->getAttachments($writer);
        $this->assertEquals('the new contents', $attachments['file0.txt']);
    }

    public function test_edit_attachment_updates_contents_and_name(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->edit('file0.txt', 'file1.txt', 'the new contents');

        $this->assertTrue($result);
        $attachments = $this->getAttachments($writer);
        $this->assertCount(1, $attachments);
        $this->assertArrayHasKey('file1.txt', $attachments);
        $this->assertEquals('the new contents', $attachments['file1.txt']);
    }

    public function test_edit_attachment_updates_name(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->edit('file0.txt', 'file1.txt', '');

        $this->assertTrue($result);
        $attachments = $this->getAttachments($writer);
        $this->assertCount(1, $attachments);
        $this->assertArrayHasKey('file1.txt', $attachments);
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
        $attachments = $this->getAttachments($writer);
        $this->assertCount(2, $attachments);
    }

    public function test_rename_attachment(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->rename('file0.txt', 'file1.txt');

        $this->assertTrue($result);
        $attachments = $this->getAttachments($writer);
        $this->assertCount(1, $attachments);
        $this->assertArrayHasKey('file1.txt', $attachments);
        $this->assertEquals('file contents 0', $attachments['file1.txt']);
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
        $attachments = $this->getAttachments($writer);
        $this->assertCount(2, $attachments);
    }

    public function test_remove_attachment(): void
    {
        $writer = $this->createTestingWriter(2);

        $result = $writer->remove('file0.txt');

        $this->assertTrue($result);
        $attachments = $this->getAttachments($writer);
        $this->assertCount(1, $attachments);
        $this->assertArrayNotHasKey('file0.txt', $attachments);
    }

    public function test_remove_attachment_returns_false_if_name_not_found(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->remove('file1.txt');

        $this->assertFalse($result);
        // Quick check for side effects
        $attachments = $this->getAttachments($writer);
        $this->assertCount(1, $attachments);
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

    public function test_upload_attachment_writes_files_correctly(): void
    {
        Storage::fake('test');

        $writer = $this->createTestingWriterWithMockFiles(2);

        $result = $writer->upload('test', 'attachments');

        $this->assertTrue($result);

        Storage::disk('test')->assertExists('attachments/file0.txt');
        Storage::disk('test')->assertExists('attachments/file1.txt');
        $this->assertEquals('file contents 0', Storage::disk('test')->get('attachments/file0.txt'));
        $this->assertEquals('file contents 1', Storage::disk('test')->get('attachments/file1.txt'));
    }

    public function test_to_livewire_returns_attachments_array(): void
    {
        $writer = $this->createTestingWriter(1);

        $result = $writer->toLivewire();

        $attachments = $this->getAttachments($writer);
        $this->assertEquals($attachments, $result);
    }

    public function test_from_livewire_returns_correct_object(): void
    {
        $expectedAttachments = ['file0' => 'file contents 0'];

        $writer = BufferedAttachmentWriter::fromLivewire($expectedAttachments);

        $attachments = $this->getAttachments($writer);
        $this->assertEquals($expectedAttachments, $attachments);
    }

}
