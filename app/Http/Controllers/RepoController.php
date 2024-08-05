<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepoUpdateRequest;
use App\Models\Article;
use App\Models\Repo;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RepoController extends Controller{
    public function index()
    {
        $repos = Repo::with('tags')->where('user_id', request()->user()->id)->get();
        $tags = Tag::all();
        return Inertia::render('Repos/Index', ['repos' => $repos, 'tags' => $tags]);
    }

    public function update(RepoUpdateRequest $request, Repo $repo){
        $repo->tags()->sync($request->tags);
        return redirect()->route('repos.index');
    }
}