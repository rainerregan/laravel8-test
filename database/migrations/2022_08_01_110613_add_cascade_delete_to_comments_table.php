<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menggunakan Cascade untuk delete relations jika menghapus data.
 */
class AddCascadeDeleteToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Menambahkan cascade pada struktur SQL Database untuk
            // Menambahkan fitur cascade (ON DELETE CASCADE)
            if(env('DB_CONNECTION') != 'sqlite_testing'){
                $table->dropForeign(['blog_post_id']);
            }
            $table->foreign('blog_post_id')
                ->references('id')
                ->on('blog_posts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            // Mengembalikan foreign key tanpa CASCADE
            if(env('DB_CONNECTION') != 'sqlite_testing'){
                $table->dropForeign(['blog_post_id']);
            }

            $table->foreign('blog_post_id')
                ->references('id')
                ->on('blog_posts');
        });
    }
}
