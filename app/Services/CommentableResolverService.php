<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CommentableResolverService
{
    public function resolve(string $type, int $id): Model
    {
        $class = 'App\\Models\\' . Str::studly($type);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Model type [$type] not found.");
        }

        return $class::findOrFail($id);
    }
}
