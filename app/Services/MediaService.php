<?php

namespace App\Services;

use App\Models\User;
use App\Models\Media;
use Illuminate\Http\UploadedFile; 
use App\Services\MediaCreationService;
use App\Exceptions\CustomException;

class MediaService
{
    public function __construct(
        protected MediaCreationService $mediaCreationService
    ) {}

    public function storeForUser(User $user, UploadedFile $file): array
    {
        $path = $file->store('media', 'public');

        if (! $path) {
            throw new CustomException('Failed to store file.');
        }

        // Replace previous media
        $user->media()?->delete();

        $media = $this->mediaCreationService->createMedia(
            $user,
            $path,
            $file->getClientMimeType()
        );

        return [
            'data' => $media,
            'message' => 'Media uploaded successfully.',
            'code' => 201
        ];
    }
}
