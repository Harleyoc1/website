<?php

namespace Tests\Feature\Post;

use App\Livewire\Posts\Cell;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PostCellTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_removes_database_row(): void
    {
        $this->actingAs(User::factory()->create());
        $post = Post::factory()->create();

        Livewire::test(Cell::class, ['post' => $post])
            ->call('delete');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_delete_removes_content_file(): void
    {
        $this->actingAs(User::factory()->create());
        $post = Post::factory()->create();
        $post->writeContent('Some test content...');

        Livewire::test(Cell::class, ['post' => $post])
            ->call('delete');

        Storage::disk('blog')->assertMissing('1.md');
    }

}
