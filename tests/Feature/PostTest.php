<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;

/*
 |-------------------------------------------------------------------------------------------
 | TESTING
 |-------------------------------------------------------------------------------------------
 | Testing akan dilakukan di database yang berbeda.
 | Jika dilihat di file config/database.php, kita menggunakan sqlite untuk melakukan testing.
 | Jadi semua yang kita lakukan di testing, seperti write, update, dan delete data
 | tidak akan berpengaruh kepada database real kita, melainkan hanya terjadi di
 | database temporary yang dibuat di memory.
 |
 */
class PostTest extends TestCase
{
    use RefreshDatabase;

    public function testNoBlogPostWhenNothingInDatabase()
    {
        $response = $this->get('/posts');
        $response->assertSeeText('No posts found!');
    }

    public function testSee1BlogPostWhenThereIs1WithComments(){

        // Arrange: Membuat dummy database model
        $post = $this->createDummyBlogPost();

        // Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText($post->title);
        $response->assertSeeText('No comments yet');

        // Mengecek apakah database punya data yang disimpan
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());
    }

    public function testSee1BlogPostWithComments(){
        // Arrange
        $post = $this->createDummyBlogPost();

        Comment::factory()->count(4)->create([
            'blog_post_id' => $post->id
        ]);

        $response = $this->get('/posts');

        $response->assertSeeText('4 comments');

    }

    public function testStoreValid(){
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];

        // Simulate POST request
        $this->post('/posts', $params)
            ->assertStatus(302) // Mengecek status redirect
            ->assertSessionHas('status'); // Mengecek apakah message status ada di session

        // Mengecek apakah message sesuai
        $this->assertEquals(session('status'), 'The blog post was created!');
    }

    public function testStoreFail(){
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];

        // Simulate POST request
        $this->post('/posts', $params)
            ->assertStatus(302) // Mengecek status redirect
            ->assertSessionHas('errors'); // Mengecek apakah message status ada di session

        $messages = session('errors')->getMessages();
        // dd($messages->getMessages());

        $this->assertEquals($messages['title'][0], "The title must be at least 5 characters.");
        $this->assertEquals($messages['content'][0], "The content must be at least 10 characters.");

    }

    public function testUpdateValid(){

        // Dummy new model
        $post = $this->createDummyBlogPost();

        // Mengecek apakah database memiliki data tersebut
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        $params = [
            'title' => 'New Title update',
            'content' => 'Content of the blog post update'
        ];

        // Simulasi edit data. Mengecek apakah ada redirect? dan apakah ada variable status pada session?
        $this->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        // Mengecek apakah ada variable status dengan value seperti dibawah pada session
        $this->assertEquals(session('status'), 'Blog post was updated');

        // Mengecek apakah data lama sudah menghilang.
        $this->assertDatabaseMissing('blog_posts', $post->getAttributes());

        // Mengecek apakah data baru sudah masuk ke database
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New Title update'
        ]);
    }

    public function testDelete(){

        // Membuat dummy model
        $post = $this->createDummyBlogPost();

        // Mengecek apakah database memiliki data tersebut
        $this->assertDatabaseHas('blog_posts', $post->getAttributes());

        // Simulasi delete data. Mengecek apakah ada redirect? dan apakah ada variable status pada session?
        $this->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        // Mengecek text deleted apakah muncul
        $this->assertEquals(session('status'), 'Blog post was deleted!');

        // Mengecek apakah data lama sudah menghilang.
        $this->assertDatabaseMissing('blog_posts', $post->getAttributes());

    }

    private function createDummyBlogPost(): BlogPost{
        // $post = new BlogPost();
        // $post->title = 'New title';
        // $post->content = 'Content of the blog post';
        // $post->save();
        // return $post;

        // Using model factory
        return BlogPost::factory()->newTitle()->create();

    }
}
