<?php
namespace App\Traits;
use App\Models\Like;

trait Likable {
    public function likes()
    {
        return $this->morphMany(Like::class,'likeable');
    }
    public function isLikedBy($user): bool
    {
        return $this->likes()->where('user_id',$user->id)->exists();
    }
}