<?php

namespace Database\Factories;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_name' => $this->faker->uuid . '.mp4',
            'file_path' => 'fake/uploads/' . $this->faker->uuid . '.mp4',
            'file_type' => 'video',
        ];
    }
}
