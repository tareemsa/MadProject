<?php
namespace App\Services;

use App\Models\Channel;
use App\Models\User;
class ChannelService
{
    public function create(User $user, array $data): array
    {
        $channel = $user->channel()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return [
            'data' => $channel,
            'message' => 'Channel created successfully.',
            'code' => 201
        ];
    }
}
