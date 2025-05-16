<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{

    protected $fillable = ['user_id', 'name', 'description'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'subscriptions');
    }

    public function podcasts()
    {
        return $this->hasMany(Podcast::class);
    }
}


