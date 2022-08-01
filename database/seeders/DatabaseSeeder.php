<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        // \App\Models\User::factory(10)->create();

        // Seeder dapat menggunakan model factory untuk populate data,
        // atau dengan cara manual menggunakan DB Facades atau dengan menggunakan
        // States yang dibuat di factory
        User::factory()->johnDoe()->create(); // Membuat akun john doe
        User::factory(20)->create();
    }
}
