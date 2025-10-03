<?php

namespace Tests\Feature;

use App\Livewire\Home;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_contains_livewire_component(): void
    {
        $this->get('/')->assertSeeLivewire(Home::class);
    }
}
