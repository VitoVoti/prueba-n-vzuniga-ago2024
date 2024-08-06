<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleCreationRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Gate::denies('viewAny', Article::class)) {
            $articles = Article::with('tags')->where('user_id', request()->user()->id)->get();
        } else {
            $articles = Article::with('tags')->with('user')->get();
        }
        
        $tags = Tag::all();
        return Inertia::render('Articles/Index', ['articles' => $articles, 'tags' => $tags]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleCreationRequest $request)
    {

        $new_article = $request->user()->articles()->create([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        
        $new_article->tags()->attach($request->tags);

        return redirect()->route('articles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return Inertia::render('Articles/Show', [
            'article' => $article, 
            'tag_names' => $article->tags->pluck('name'), 
            'author_name' => $article->user->name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        if (Gate::denies('update', $article)) {
            abort(403);
        }

        return Inertia::render('Articles/Edit', ['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        if (Gate::denies('update', $article)) {
            abort(403);
        }   

        Log::debug("Actualizando el artículo {$article->title} con datos nuevos: " . json_encode($request->all()) . " request->body es " . $request->body);

        $article->title = $request->title;
        $article->body = $request->body;
        
        $article->tags()->sync($request->tags);

        $save = $article->save();
        Log::debug("Article ahora es " . json_encode($article) . " y save es: " . $save);
        

        return Redirect::route('articles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if (Gate::denies('delete', $article)) {
            abort(403);
        }

        $article->delete();
        return redirect()->route('articles.index');
    }
}
