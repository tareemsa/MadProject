<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Podcast;
use Carbon\Carbon;

class PublishScheduledPodcasts extends Command
{
    protected $signature = 'podcasts:publish-scheduled';
    protected $description = 'Publish scheduled podcasts when time is due';

   /* public function handle()
    {
        $now = Carbon::now();

        $podcasts = Podcast::whereNotNull('publish_at')
            ->where('publish_at', '<=', $now)
            ->whereNull('published_at')
            ->get();

        foreach ($podcasts as $podcast) {
            $podcast->update(['published_at' => $now]);
            $this->info("Published podcast ID: {$podcast->id}");
        }

        return 0;
    }*/
    public function handle()
{
    $this->info('Running publish command...');

    $now = now();

    $podcasts = Podcast::whereNotNull('publish_at')
        ->where('publish_at', '<=', $now)
        ->whereNull('published_at')
        ->get();

    $this->info("Found " . $podcasts->count() . " podcast(s) to publish.");

    foreach ($podcasts as $podcast) {
        $podcast->update(['published_at' => $now]);
        $this->info(" Published podcast ID: {$podcast->id}");
    }

    return 0;
}

}
