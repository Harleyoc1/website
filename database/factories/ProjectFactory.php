<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->realText(20),
            'slug' => fake()->unique()->realText(20),
            'tools' => fake()->realText(20),
            'cover_img_filename' => fake()->unique()->realText(20) . '.png',
            'summary' => fake()->realText(20),
            'repo_link' => fake()->url(),
            'standout' => fake()->boolean()
        ];
    }
}
