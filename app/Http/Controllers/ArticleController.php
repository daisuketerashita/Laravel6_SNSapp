<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Tag;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{

    public function __construct(){
        $this->authorizeResource(Article::class, 'article');
    }

    //一覧画面表示
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');
        
        return view('articles.index', ['articles' => $articles]);
    }

    //記事投稿画面を表示
    public function create(){
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.create',['allTagNames' => $allTagNames]);
    }

    //記事投稿処理
    public function store(ArticleRequest $request){
        $article = new Article();

        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();

        //タグの登録
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    //記事更新画面を表示
    public function edit(Article $article){
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.edit',['article' => $article,'tagNames' => $tagNames,'allTagNames' => $allTagNames]);
    }

    //記事更新処理
    public function update(ArticleRequest $request,Article $article){
        $article->fill($request->all())->save();

        //タグの編集
        $article->tags()->detach();
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    //記事削除処理
    public function destroy(Article $article){
        $article->delete();

        return redirect()->route('articles.index');
    }

    //詳細画面表示
    public function show(Article $article){
        return view('articles.show', ['article' => $article]);
    }

    //いいね機能処理
    public function like(Request $request,Article $article){
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    //いいね解除機能処理
    public function unlike(Request $request,Article $article){
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
