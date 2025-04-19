<?php

namespace App\Services\podcast;

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Services\MediaService;

class PodcastService
{
    public function __construct(
        protected MediaService $mediaService
    ) {}
    public function uploadPodcast(User $user, array $data): array
    {
        $podcast = $user->podcasts()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);
    
        if (isset($data['cover_image'])) {
            $this->mediaService->storeForModel($podcast, $data['cover_image'], 'cover');
        }
    
        if (isset($data['podcast_file'])) {
            $mime = $data['podcast_file']->getMimeType();
            $fileType = str_starts_with($mime, 'video/') ? 'video' : 'audio';
    
            $this->mediaService->storeForModel($podcast, $data['podcast_file'], $fileType);
        }
    
        return [
            'data' => ['podcast' => $podcast->load('media')],
            'message' => 'Podcast uploaded successfully.',
            'code' => 201,
        ];
    }
    
}
