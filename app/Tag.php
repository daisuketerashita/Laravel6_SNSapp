<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    //
    protected $fillable = [
        'name',
    ];


    //タグの先頭に#を追加
    public function getHashtagAttribute(): string{
        return '#' . $this->name;
    }

    public function articles(): BelongsToMany{
        return $this->belongsToMany('App\Article')->withTimestamps();
    }
}
