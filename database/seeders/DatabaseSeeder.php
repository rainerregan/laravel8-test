<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /*
     |==================================================================================
     | Seeding
     |==================================================================================
     | Seeding berfungsi untuk mem-populate data di database kita.
     | Seeding bisa digabungkan dengan migrate dengan menambahkan flag --seed
     |
     | php artisan migrate --seed
     |
     | Untuk membuat seeding, cara nya adalah dengan menggunakan artisan dengan command:
     |
     | php artisan db:seed
     |==================================================================================
     */
    public function run()
    {
        // Making Seeder Interactive
        // Membuat interaksi di command prompt
        if ($this->command->confirm("Do you want to refresh the database?")){
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        // Memanggil Individual Seeders
        // Memanggil seeders harus memperhatikan urutan seeder agar foreign key
        // tidak terlewat.
        $this->call([
            UsersTableSeeder::class,
            BlogPostsTableSeeder::class,
            CommentsTableSeeder::class
        ]);


    }
}
