<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PodcastResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'published_at' => $this->published_at,
            'media' => $this->media,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name
            ],
        ];
    }
}
