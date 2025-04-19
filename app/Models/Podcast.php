<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function coverImage()
    {
        return $this->morphOne(Media::class, 'mediable')->where('file_type', 'cover');
    }

    public function audioFile()
    {
        return $this->morphOne(Media::class, 'mediable')->where('file_type', 'audio');
    }
    public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
}
