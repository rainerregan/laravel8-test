<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BlogPostFactory extends Factory
{

    protected $model = \App\Models\BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->paragraphs(5, true),
            'created_at' => $this->faker->dateTimeBetween('-3 months'),
        ];
    }

    // State
    public function newTitle(){
        return $this->state([
            'title' => 'New title',
            'content' => 'Content of the blog post'
        ]);
    }
}
