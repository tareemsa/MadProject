<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PodcastView extends Model
{
    use HasFactory;
    protected $fillable = ['podcast_id', 'user_id', 'viewed_at'];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
