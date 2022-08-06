<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePageIsWorkingCorrectly()
    {
        // Membuka Halaman Utama
        $response = $this->get('/');

        // Mengecek apakah test melihat sebuah text
        // $response->assertSeeText('Hello World!');
        $response->assertStatus(200);

    }

    public function testContactPageIsWorkingCorrectly(){
        $response = $this->get('/contact');
        // $response->assertSeeText('Contact');
        $response->assertStatus(200);
    }
}
