<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat custom commands untuk customization jumlah seeder
        $usersCount = max(
            (int)$this->command->ask('How many users would you like to create?', 20)
            , 1
        ); // Membuat default value minimal 1. Jika user memasukkan angka dibawah 1, maka akan diambil angka 1.

        /*
         |========================================================================
         | Users Seeder
         |========================================================================
         | Seeder dapat menggunakan model factory untuk populate data,
         | atau dengan cara manual menggunakan DB Facades atau dengan menggunakan
         | States yang dibuat di factory
         |========================================================================
         */
        User::factory()->johnDoe()->create(); // Membuat akun john doe
        User::factory($usersCount)->create(); // Membuat akun dengan Faker factory sejumlah input user
    }
}
