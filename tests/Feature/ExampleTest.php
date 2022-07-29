<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        // Visit URL
        $response = $this->get('/');

        // Melihat apakah response code return 200
        $response->assertStatus(200);
    }
}
