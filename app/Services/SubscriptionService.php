<?php 
namespace App\Services;

use App\Models\User;
use App\Models\Channel;
use App\Exceptions\SubscriptionException;
class SubscriptionService
{
    public function toggleSubscription(User $user, Channel $channel): array
    {
        if ($user->id === $channel->user_id) {
            throw new \Exception('You cannot subscribe to your own channel.');
        }

        $isSubscribed = $channel->subscribers()->where('user_id', $user->id)->exists();

        if ($isSubscribed) {
            $channel->subscribers()->detach($user->id);
            $message = 'Unsubscribed successfully.';
        } else {
            $channel->subscribers()->attach($user->id);
            $message = 'Subscribed successfully.';
        }

        return [
            'data' => [],
            'message' => $message,
            'code' => 200
        ];
    }
}
