<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Thread;

class Reply extends Model
{
    protected $guarded = [];
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorites::class, 'favorited');
    }

    public function favorite()
    {
        $attribute = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attribute)->exists()) {
            $this->favorites()->create($attribute);
        }
    }

}
