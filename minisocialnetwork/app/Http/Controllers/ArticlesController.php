<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Auth;
use DB;

class ArticlesController extends Controller
{

    public function index()
    {
        //Using Eloquent
        $articles = Article::paginate(10);
        // $articles = Article::whereLive(1)->get();

        //Using QueryBuilder
        // $articles = DB::table('articles')->get();
        // $articles = DB::table('articles')->whereLive(1)->get();

        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        /*
        $article = new Article;
        $article->user_id = Auth::user()->id;
        $article->content = $request->content;
        $article->live = $request->live;
        $article->post_on = $request->post_on;

        $article->save();
        */

        Article::create($request->all());

        // Article::create([
        //   'user_id' => Auth::user()->id,
        //   'content' => $request->content,
        //   'live' => $request->live,
        //   'post_on' => $request->post_on
        // ]);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }

    public function edit($id)
    {
      $article = Article::findOrFail($id);
      return view('articles.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        if (! isset($request->live))
          $article->update(array_merge($request->all(),['live' => false]));

        $article->update($request->all());

        return redirect('/articles');
    }

    public function destroy($id)
    {
        //
    }
}
