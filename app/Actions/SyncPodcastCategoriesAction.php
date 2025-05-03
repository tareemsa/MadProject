<?php
namespace App\Actions;

use App\Models\Podcast;

class SyncPodcastCategoriesAction
{
    public function execute(Podcast $podcast, array $categoryIds): void
    {
        $podcast->categories()->sync($categoryIds);
    }
}
