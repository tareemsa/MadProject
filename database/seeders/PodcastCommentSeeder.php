<?php 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Podcast;
use App\Models\Comment;
use App\Models\Media;

class PodcastCommentSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(5)->create();

        $podcasts = Podcast::factory(10)->create();

        foreach ($podcasts as $podcast) {
    
            $podcast->media()->create([
                'file_name' => 'sample_' . $podcast->id . '.mp4',
                'file_path' => 'fake/media/sample_' . $podcast->id . '.mp4',
                'file_type' => 'video',
            ]);


            Comment::factory(5)->create([
                'commentable_type' => Podcast::class,
                'commentable_id' => $podcast->id,
            ])->each(function ($parent) use ($podcast) {
  
                Comment::factory(2)->create([
                    'commentable_type' => Podcast::class,
                    'commentable_id' => $podcast->id,
                    'parent_id' => $parent->id,
                ]);
            });
        }
    }
}
