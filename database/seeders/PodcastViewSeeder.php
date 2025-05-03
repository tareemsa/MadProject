<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PodcastView;

class PodcastViewSeeder extends Seeder
{
    public function run(): void
    {
        PodcastView::factory()->count(100)->create();
    }
}

