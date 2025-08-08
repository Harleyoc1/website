<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response(): void
    {
        $this->get('/')->assertStatus(200);
    }
}
