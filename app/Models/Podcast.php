<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Podcast extends Model
{
    use HasFactory,Likable;

    protected $fillable = ['user_id', 'title', 'description','views'];

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

public function categories()
{
    return $this->morphToMany(Category::class, 'categorizable');
}
public function views()
{
    return $this->hasMany(PodcastView::class);
}
}
