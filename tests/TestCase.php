<?php

namespace Tests;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function user()
    {
        // Create User Instance using Factory with Faker
        return User::factory()->create()->first();
    }

    protected function post_create(){
        return BlogPost::factory()->create([
            'user_id' => $this->user()->id
        ]);
    }
}
