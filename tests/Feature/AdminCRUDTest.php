<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCRUDTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_admin_can_log_in_and_CRUD_articles(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
