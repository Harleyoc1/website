<?php

namespace Feature;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MavenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('maven');
    }
    public function test_root_index_returns_a_successful_response(): void
    {
        $response = $this->get('/maven');
        $response->assertStatus(200);
    }

    public function test_get_directory_displays_files_in_directory(): void
    {
        $this->maven()->put('test/testing.txt', '...');
        $this->maven()->put('test/testing2.txt', '...');
        $this->maven()->put('test/testing3.txt', '...');

        $response = $this->get('/maven/test');

        $response->assertStatus(200)
            ->assertSeeText('testing.txt')
            ->assertSeeText('testing2.txt')
            ->assertSeeText('testing3.txt');
    }

    public function test_get_directory_doesnt_display_files_from_other_directories(): void
    {
        $this->maven()->makeDirectory('test');
        $this->maven()->put('testing.txt', '...');
        $this->maven()->put('test2/testing2.txt', '...');
        $this->maven()->put('test3/testing3.txt', '...');

        $response = $this->get('/maven/test');

        $response->assertStatus(200)
            ->assertDontSeeText('testing.txt')
            ->assertDontSeeText('testing2.txt')
            ->assertDontSeeText('testing3.txt');
    }

    public function test_get_redirects_to_file_download_when_path_points_to_file(): void
    {
        $this->maven()->put('testing.txt', '...');

        $response = $this->get('/maven/testing.txt');

        $response->assertRedirect('/maven/download/testing.txt');
    }

    public function test_get_file_returns_file_download(): void
    {
        $this->maven()->put('testing.txt', '...');

        $response = $this->get('/maven/download/testing.txt');

        $response->assertStatus(200)
            ->assertHeader('Content-Disposition', 'attachment; filename=testing.txt');
    }

    public function test_get_file_returns_not_found_if_file_doesnt_exist(): void
    {
        $response = $this->get('/maven/testing.txt');
        $response->assertStatus(404);
    }

    public function test_put_with_no_credentials_returns_unauthorised(): void
    {
        $response = $this->put('/maven/testing.txt');
        $response->assertStatus(401);
    }

    public function test_put_when_regular_user_returns_forbidden(): void
    {
        UserFactory::new(['email' => 'test@example.com', 'password' => 'test'])->create();

        $response = $this->withBasicAuth('test@example.com', 'test')
            ->put('/maven/testing.txt');

        $response->assertStatus(403);
    }

    public function test_put_when_maven_editor_returns_ok(): void
    {
        $file = UploadedFile::fake()->createWithContent('testing.txt', 'test');

        $response = $this->withMavenEditor()->put('/maven/testing.txt', ['file' => $file]);

        $response->assertStatus(200);
    }

    public function test_put_writes_content_correctly(): void
    {
        $file = UploadedFile::fake()->createWithContent('testing.txt', 'test');

        $response = $this->withMavenEditor()->put('/maven/testing.txt', ['file' => $file]);

        $response->assertStatus(200);
        $this->maven()->assertExists('testing.txt');
        $this->assertEquals('test', $this->maven()->get('testing.txt'));
    }

    public function test_put_creates_new_directories(): void
    {
        $file = UploadedFile::fake()->createWithContent('testing.txt', 'test');

        $response = $this->withMavenEditor()
            ->put('/maven/a/nested/directory/testing.txt', ['file' => $file]);

        $response->assertStatus(200);
        $this->maven()->assertExists('a/nested/directory/testing.txt');
    }

    private function withMavenEditor(): self
    {
        UserFactory::new([
            'email' => 'test@example.com',
            'password' => 'test',
            'permission_level' => 1
        ])->create();
        return $this->withBasicAuth('test@example.com', 'test');
    }

    private function maven(): Filesystem
    {
        return Storage::disk('maven');
    }

}
