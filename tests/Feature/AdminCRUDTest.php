<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Article;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AdminCRUDTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_admin_can_create_a_valid_article_though_async_request(): void
    {
        $response = $this->get('/');

        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->first());

        $tags = Tag::factory(5)->create();

        $response = $this
            ->actingAs($user)
            ->from('/articles')
            ->post('/articles', [
                'title' => 'Some title',
                'body' => 'Some body',
                'tags' => $tags->pluck('id')->toArray(),
            ]);
        //Log::debug("response es " . json_encode($response));
        $possible_article = Article::where('title', 'Some title')->first();
        $this->assertTrue($possible_article && $possible_article->exists());
        
        $response->assertStatus(302);
        

    }
    public function test_admin_gets_an_error_when_creating_an_invalid_article_though_async_request(): void
    {
        $response = $this->get('/');

        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'admin')->first());

        $tags = Tag::factory(5)->create();

        $response = $this
            ->actingAs($user)
            ->from('/articles')
            ->post('/articles', [
                'title' => 'Some title A',
                'tags' => $tags->pluck('id')->toArray(),
            ]);
        Log::debug("response es " . json_encode($response));
        
        $possible_article = Article::where('title', 'Some title A')->first();

        Log::debug("possible_article es " . json_encode($possible_article));
        
        $this->assertFalse($possible_article && $possible_article->exists());
        
        $response->assertStatus(302);
    }
}
