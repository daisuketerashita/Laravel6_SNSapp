<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{
    //一覧画面表示
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');
        
        return view('articles.index', ['articles' => $articles]);
    }

    //記事投稿画面を表示
    public function create(){
        return view('articles.create');
    }

    //記事投稿処理
    public function store(ArticleRequest $request){
        $article = new Article();

        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        return redirect()->route('articles.index');
    }
}
