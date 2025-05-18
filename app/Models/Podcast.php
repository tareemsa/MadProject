<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Likable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Podcast extends Model
{
    use HasFactory,Likable;

    protected $fillable = ['user_id', 'title', 'description','views',        'channel_id',
    'publish_at','published_at'];

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
        return $this->morphMany(Comment::class, 'commentable')
            ->whereNull('parent_id') 
            ->with(['replies.user', 'user']); 
    }
    

public function categories()
{
    return $this->morphToMany(Category::class, 'categorizable');
}
public function views()
{
    return $this->hasMany(PodcastView::class);
}
public function channel()
{
    return $this->belongsTo(Channel::class);
}

public function owner()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
