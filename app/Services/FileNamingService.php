<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileNamingService
{
    public function generateSecureFileName(UploadedFile $file): string
    {
        return (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
    }
}
