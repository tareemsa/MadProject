<?php 
namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;


class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'body' => $this->faker->sentence,
            'commentable_type' => Podcast::class,
            'commentable_id' => Podcast::factory(),
            'parent_id' => null, 
        ];
    }
}
