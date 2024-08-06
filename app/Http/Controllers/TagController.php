<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreationRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return Inertia::render('Tags/Index', ['tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagCreationRequest $request)
    {
        $tag = Tag::create($request->validated());
        return redirect()->route('tags.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $tag->update($request->validated());
        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index');
    }
}
