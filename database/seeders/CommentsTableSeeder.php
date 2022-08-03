<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Mendapatkan semua data posts
        $posts = BlogPost::all();

        // Fungsi akan mereturn null jika blog post tidak tersedia.
        if($posts->count() === 0){
            $this->command->info("There are no blog posts, so no comments will be added");
            return;
        }

        // Mendapatkan jumlah comments yang ingin dibuat oleh user.
        $commentsCount = (int)$this->command->ask('How many comments would you like to create?', 150);

        // Mendapatkan semua data users
        $users = User::all();

        /*
         |========================================================================
         | Comments Seeder
         |========================================================================
         | Seeder untuk 150 comments.
         | Dikarenakan comments membutuhkan Fk dari post_id, maka kita perlu
         | untuk assign data id post kepada comments.
         | Melakukan loop untuk tiap comment yang dibuat dan meng-assign data
         | id random dari tiap post untuk di assign kepada comment.
         |========================================================================
         */
        Comment::factory($commentsCount)
            ->make() // Membuat 150 unsaved comment
            ->each(function($comment) use($posts, $users){ // Looping
                $comment->blog_post_id = $posts->random()->id;
                $comment->user_id = $users->random()->id; // Meng-assign setiap user_id untuk setiap post dengan data user_id random dari Users yang dibuat sebelumnya.
                $comment->save();
            });
    }
}
