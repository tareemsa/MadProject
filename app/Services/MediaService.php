<?php
namespace App\Services;

use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Mail;
class MediaService
{
    public function storeForUser(User $user, UploadedFile $file): array
    {
        $path = $file->store('media', 'public');

        if (!$path) {
            throw new MediaUploadException('Failed to store file.');
        }

        $media = $user->media()->create([
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
        ]);

        return [
            'data' => $media,
            'message' => 'Media uploaded successfully'
        ];
    }
}

