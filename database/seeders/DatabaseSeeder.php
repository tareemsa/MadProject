<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PodcastView;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

            $this->call([
                CategorySeeder::class,
                UserSeeder::class,
                PodcastSeeder::class,
                MediaSeeder::class,
                CommentSeeder::class,
               PodcastViewSeeder::class,

              
            ]);
        
        



        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
