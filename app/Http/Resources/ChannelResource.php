<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => $this->owner ? [
                'id' => $this->owner->id,
                'name' => $this->owner->name
            ] : null,
        ];

 
    }
}

