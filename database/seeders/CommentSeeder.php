<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Podcast;
use App\Models\Comment;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $podcastIds = Podcast::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        Comment::factory(100)->create([
            'user_id' => function () use ($userIds) {
                return User::inRandomOrder()->first()->id;
            },
            'commentable_type' => Podcast::class,
            'commentable_id' => function () use ($podcastIds) {
                return Podcast::inRandomOrder()->first()->id;
            },
            'parent_id' => null,
        ])->each(function ($comment) use ($userIds) {

            if (rand(0, 1)) {
                Comment::factory(rand(1, 3))->create([
                    'user_id' => User::inRandomOrder()->first()->id,
                    'commentable_type' => Podcast::class,
                    'commentable_id' => $comment->commentable_id,
                    'parent_id' => $comment->id,
                ]);
            }
        });
    }
}
