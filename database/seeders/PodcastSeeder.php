<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Podcast;
use App\Models\User;

use App\Models\Category;

class PodcastSeeder extends Seeder
{
    public function run(): void 
    {
        $userIds=User::pluck('id')->toArray();

        Podcast::factory(10)->make()->each(function ($podcast) use ($userIds){
        $podcast->user_id = User::inRandomOrder()->first()->id;
            $podcast->save();
        });

        $techCategory = Category::where('name', 'Technology')->first();
        $eduCategory = Category::where('name', 'Education')->first();

        $podcast1 = Podcast::create([
            'title'       => 'AI and the Future',
            'description' => 'A deep dive into Artificial Intelligence.',
            'user_id'     => 1, 
        ]);


        $podcast2 = Podcast::create([
            'title'       => 'Learn Smarter',
            'description' => 'Tips and strategies for better studying.',
            'user_id'     => 1,
        ]);

        $podcast1->categories()->attach([$techCategory->id]);
        $podcast2->categories()->attach([$eduCategory->id]);
    
    }
}