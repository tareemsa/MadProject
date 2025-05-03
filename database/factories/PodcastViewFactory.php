<?php
namespace Database\Factories;

use App\Models\Podcast;
use App\Models\User;
use App\Models\PodcastView;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PodcastViewFactory extends Factory
{
    protected $model = PodcastView::class;

    public function definition(): array
    {
        return [
            'podcast_id' => Podcast::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'viewed_at' => Carbon::now()->subDays(rand(0, 6)),
        ];
    }
}
