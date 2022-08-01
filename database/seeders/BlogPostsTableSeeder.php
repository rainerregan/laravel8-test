<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Custom commands for getting input from user
        $blogCount = (int)$this->command->ask('How many blog posts would you like to create?', 50);

        // Mendapatkan semua data users
        $users = User::all();

        /*
         |========================================================================
         | Blog Posts Seeder
         |========================================================================
         | Seeder untuk 50 blogpost.
         | Dikarenakan blogpost membutuhkan FK untuk user_id, maka kita perlu untuk
         | Melakukan loop untuk user yang telah di seed sebelumnya.
         |
         | Setelah melakukan loop, kita akan meng assign id random untuk tiap post
         |========================================================================
         */
        BlogPost::factory($blogCount)
            ->make() // Membuat 50 blogpost, tetapi belum ter-save
            ->each(function($post) use ($users){ // Foreach loop untuk semua post yang dibuat. Menggunakan use untuk dapat mengakses data diluar scope.
                $post->user_id = $users->random()->id; // Meng-assign setiap user_id untuk setiap post dengan data user_id random dari Users yang dibuat sebelumnya.
                $post->save(); // Save data.
            });
    }
}
