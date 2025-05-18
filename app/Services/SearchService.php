<?php 


namespace App\Services;

use App\Models\Podcast;
use App\Models\Channel;
use App\Http\Resources\PodcastResource;
use App\Http\Resources\ChannelResource;
class SearchService
{
    public function search(string $query): array
    {
        $podcasts = Podcast::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('title', 'like', '%' . $query . '%')
            ->with(['media', 'user'])
            ->get();
    
        $channels = Channel::where('name', 'like', '%' . $query . '%')
            ->with('owner')
            ->get();
    
        return [
            'data' => [
                'podcasts' => PodcastResource::collection($podcasts),
                'channels' => ChannelResource::collection($channels),
            ],
            'message' => 'Search results loaded successfully.',
            'code' => 200
        ];
    }
}
