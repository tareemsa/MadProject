<?php
namespace App\Services\Podcast;
use App\Models\Podcast;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class PodcastPublishService
{
    public function schedulePublish(int $podcastId, int $channelId, string $publishAt): array
    {
        $user = Auth::user();

        $channel = Channel::where('id', $channelId)
                          ->where('user_id', $user->id)
                          ->first();

        if (!$channel) {
            throw new ModelNotFoundException('Channel not found or does not belong to the user.');
        }

        $podcast = Podcast::where('id', $podcastId)
                          ->where('user_id', $user->id)
                          ->first();

        if (!$podcast) {
            throw new ModelNotFoundException('Podcast not found or does not belong to the user.');
        }

        $podcast->update([
            'channel_id' => $channel->id,
            'publish_at' => $publishAt,
        ]);

        return [
            'data' => $podcast,
            'message' => 'Podcast scheduled for publishing successfully.',
            'code' => 200,
        ];
    }
}
