<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //ユーザーページ表示
    public function show(string $name){
        $user = User::where('name',$name)->first();

        //ユーザーの投稿した記事モデルを渡す
        $articles = $user->articles->sortByDesc('created_at');

        return view('users.show',['user' => $user,'articles' => $articles,]);
    }


    //いいねの記事の表示
    public function likes(string $name){
        $user = User::where('name', $name)->first();

        //ユーザーがいいねした記事モデルを渡す
        $articles = $user->likes->sortByDesc('created_at');

        return view('users.likes', [
            'user' => $user,
            'articles' => $articles,
        ]);
    }

    //フォロー表示画面
    public function followings(string $name){
        $user = User::where('name', $name)->first();

        //ユーザーのフォロー情報を渡す
        $followings = $user->followings->sortByDesc('created_at');

        return view('users.followings', [
            'user' => $user,
            'followings' => $followings,
        ]);
    }
    
    //フォロワー表示画面
    public function followers(string $name){
        $user = User::where('name', $name)->first();

        //ユーザーのフォロワー情報を渡す
        $followers = $user->followers->sortByDesc('created_at');

        return view('users.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    //フォロー機能処理
    public function follow(Request $request, string $name){
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id)
        {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);
        $request->user()->followings()->attach($user);

        return ['name' => $name];
    }
    
    //フォロー解除処理
    public function unfollow(Request $request, string $name){
        $user = User::where('name', $name)->first();

        if ($user->id === $request->user()->id)
        {
            return abort('404', 'Cannot follow yourself.');
        }

        $request->user()->followings()->detach($user);

        return ['name' => $name];
    }
}
