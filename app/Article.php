<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    protected $fillable = [
        'title',
        'body'
    ];
    
    //Userモデルへのリレーション
    public function user(): BelongsTo{
        return $this->belongsTo('App\User');
    }

    //likesを中間テーブルとするリレーション
    public function likes(): BelongsToMany{
        return $this->belongsToMany('App\User','likes')->withTimestamps();
    }

    //ユーザーモデルを渡すとそのユーザーがこの記事をいいね済みかどうかを返すメソッド
    public function isLikedBy(?User $user): bool{
        return $user
            ? (bool)$this->likes->where('id',$user->id)->count()
            : false;
    }

}
