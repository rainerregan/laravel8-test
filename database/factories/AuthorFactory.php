<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }

    /**
     * Using callback afterCreating setelah model author di save.
     * Fungsi di bawah ini akan membuat profile baru setiap author dibuat.
     */
    public function newProfile(){
        return $this->afterCreating(function($author) {
            // Save Profile to Author, Creating Association
            $author->profile()->save(Profile::factory()->make());
        });
    }
}
