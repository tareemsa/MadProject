<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Media;

class MediaCreationService
{
    public function createMedia(Model $model, string $originalName, string $path, string $type): Media
    {
        return $model->media()->create([
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $type,
        ]);
    }
    
    public function replaceUserMedia(User $user, string $originalName, string $path, string $type): Media
    {
        $user->media()?->delete();

        return $user->media()->create([
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $type,
        ]);
    }
}
