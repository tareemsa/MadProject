<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\Media;

class MediaCreationService
{
    /**
     * Create a media record for any morphable model.
     */
    public function createMedia(Model $model, string $path, string $type): Media
    {
        return $model->media()->create([
            'file_name' => basename($path),
            'file_path' => $path,
            'file_type' => $type,
        ]);
    }
}
