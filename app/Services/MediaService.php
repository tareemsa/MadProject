<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\CustomException;
use Illuminate\Database\Eloquent\Model;
use App\Models\Media; 


class MediaService
{
    public function __construct(
        protected FileNamingService $fileNamingService,
        protected FileStorageService $fileStorageService,
        protected MediaCreationService $mediaCreationService
    ) {}

   /* public function storeForUser(User $user, UploadedFile $file): array
    {
        $originalName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());

        $secureName = $this->fileNamingService->generateSecureFileName($file);

        $path = $this->fileStorageService->store($file, $secureName);

        if (! $path) {
            throw new CustomException('Failed to store file.');
        }

        $media = $this->mediaCreationService->replaceUserMedia(
            $user,
            $originalName,
            $path,
            $file->getClientMimeType()
        );

        return [
            'data' => [
                ...$media->toArray(),
                'url' => asset(Storage::url($path)),
            ],
            'message' => 'Media uploaded successfully.',
            'code' => 201,
        ];
    }
*/
    public function storeForModel(Model $model, UploadedFile $file, string $type): Media
{
    $originalName = preg_replace('/[^A-Za-z0-9\-_.]/', '_', $file->getClientOriginalName());
    $secureName = $this->fileNamingService->generateSecureFileName($file);
    $path = $this->fileStorageService->store($file, $secureName);

    if (! $path) {
        throw new CustomException('Failed to store file.');
    }

    return $model->media()->create([
        'file_name' => $originalName,
        'file_path' => $path,
        'file_type' => $type,
    ]);
}

}
