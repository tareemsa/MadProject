<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileStorageService
{
    public function store(UploadedFile $file, string $fileName, string $folder = 'media'): ?string
    {
        return $file->storeAs($folder, $fileName, 'public');
    }
}
